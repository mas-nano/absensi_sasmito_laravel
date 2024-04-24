<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Traits\UploadFile;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class LeaveController extends Controller
{
    use UploadFile;
    public function index(): JsonResponse
    {
        return $this->responseSuccessWithData();
    }

    public function getSingleList(Request $request): JsonResponse
    {
        $leaves = Leave::where('user_id', $request->user()->id)->latest()->get();
        return $this->responseSuccessWithData('Single leaves', $leaves);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'to_date' => 'required|date',
            'reason' => 'required',
            'type' => ['required', Rule::in(['Izin Terlambat', 'Dinas Luar', 'Sakit', 'Lainnya', 'Izin Pulang Lebih Awal'])],
            'photo' => 'nullable|image|mimes:png,jpg,jpeg',
        ], [], [
            'start_date' => "Tanggal Mulai",
            'to_date' => "Tanggal Selesai",
            'reason' => 'Alasan',
            'type' => 'Jenis Izin',
            'photo' => 'Bukti Izin'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $leaveExists = Leave::where('user_id', $request->user()->id)->where('project_id', $request->user()->project_id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('start_date', '>=', date('Y-m-d'))->where('to_date', '<=', date('Y-m-d'))->where('status', 2)->first();
        if ($leaveExists) {
            return $this->responseError('Anda sudah melakukan izin hari ini', 403);
        }

        $leave = new Leave();
        if ($request->hasFile('photo')) {
            $leave->photo = $this->upload('bukti-izin', $validated['photo']);
        }
        $leave->start_date = Carbon::parse($validated['start_date']);
        $leave->to_date = Carbon::parse($validated['to_date']);
        $leave->reason = $validated['reason'];
        $leave->type = $validated['type'];
        $leave->status = 1;
        $leave->user_id = $request->user()->id;
        $leave->project_id = $request->user()->project_id;
        $leave->save();

        return $this->responseCreated('Izin berhasil diajukan');
    }
}

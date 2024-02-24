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
        $leaves = Leave::where('user_id', $request->user()->id)->get();
        return $this->responseSuccessWithData('Single leaves', $leaves);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'to_date' => 'required|date',
            'reason' => 'required',
            'type' => ['required', Rule::in(['Izin Terlambat', 'Perjalanan Dinas', 'Sakit', 'Izin Lainnya', 'Izin Pulang Lebih Awal'])],
            'title' => 'required',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg',
        ], [], [
            'start_date' => "Tanggal Mulai",
            'to_date' => "Tanggal Selesai",
            'reason' => 'Alasan',
            'type' => 'Jenis Izin',
            'title' => 'Judul',
            'photo' => 'Bukti Izin'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $leave = new Leave();
        if ($request->hasFile('photo')) {
            $leave->photo = $this->upload('bukti-izin', $validated['photo']);
        }
        $leave->title = $validated['title'];
        $leave->start_date = Carbon::parse($validated['start_date']);
        $leave->to_date = Carbon::parse($validated['to_date']);
        $leave->reason = $validated['reason'];
        $leave->type = $validated['type'];
        $leave->status = 2;
        $leave->user_id = $request->user()->id;
        $leave->save();

        return $this->responseCreated('Izin berhasil diajukan');
    }
}

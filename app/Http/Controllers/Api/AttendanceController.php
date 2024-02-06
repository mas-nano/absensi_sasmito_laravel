<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->responseSuccessWithData();
    }

    public function show(User $user): JsonResponse
    {
        return $this->responseSuccessWithData();
    }

    public function showLogin(Request $request): JsonResponse
    {
        $attendances = Attendance::where('user_id', $request->user()->id)->whereDate('created_at', $request->get('date', Carbon::now()->toDateString()))->latest()->get();

        return $this->responseSuccessWithData("Attendance Data", $attendances);
    }

    public function checkStatus(Request $request): JsonResponse
    {
        $attendances = Attendance::where('user_id', $request->user()->id)->whereDate('created_at',  Carbon::now()->toDateString())->latest()->get();
        $data = collect();
        if (count($attendances) == 0 || $attendances[0]->type == 'out') {
            $data->put('canCheckIn', true);
            $data->put('canCheckOut', false);
        } else {
            $data->put('canCheckIn', false);
            $data->put('canCheckOut', true);
        }

        return $this->responseSuccessWithData("Attendance Data", $data);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lng' => 'required',
            'lat' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();
        $lastAttendance = Attendance::latest()->first();

        $attendace = new Attendance();
        $attendace->lng = $validated['lng'];
        $attendace->lat = $validated['lat'];
        // $time = Carbon::now('GMT+7')->toTimeString();
        if ($lastAttendance == null || $lastAttendance->type == 'out') {
            $attendace->type = 'in';
        } else {
            $attendace->type = 'out';
        }
        $attendace->status = 'On Time';
        // if (Carbon::createFromFormat('H:i:s', $time)->gte(Carbon::createFromFormat('H:i', '08:00')) && Carbon::createFromFormat('H:i:s', $time)->lt(Carbon::createFromFormat('H:i', '16:00'))) {
        //     $attendace->type = 'in';
        //     if (Carbon::now()->gte(Carbon::createFromFormat('H:i', '08:00'))) {
        //         $attendace->status = 'On Time';
        //     } else {
        //         $attendace->status = 'Late';
        //     }
        // } else {
        //     $attendace->type = 'out';
        //     if (Carbon::now()->gte(Carbon::createFromFormat('H:i', '16:00'))) {
        //         $attendace->status = 'On Time';
        //     } else {
        //         $attendace->status = 'Late';
        //     }
        // }
        $attendace->user_id = $request->user()->id;
        $attendace->save();

        return $this->responseSuccess("Attendance successfully");
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Auth;
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

    public function checkLeave(Request $request): JsonResponse
    {
        $now = Carbon::now();
        $todayLeave = Leave::where('user_id', $request->user()->id)->where('start_date', '<=', $now->toDateString())->where('to_date', '>=', $now->toDateString())->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->latest()->first();
        return $this->responseSuccessWithData("Leave Data", $todayLeave);
    }

    public function showLogin(Request $request): JsonResponse
    {
        $attendances = Attendance::where('user_id', $request->user()->id)->whereDate('created_at', $request->get('date', Carbon::now()->toDateString()))->latest()->get();
        $todayLeave = Leave::where('user_id', $request->user()->id)->where('start_date', '<=', $request->get('date', Carbon::now()->toDateString()))->where('to_date', '>=', $request->get('date', Carbon::now()->toDateString()))->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->latest()->get();
        $res = $attendances->merge($todayLeave);
        return $this->responseSuccessWithData("Attendance Data", $res);
    }

    public function checkStatus(Request $request): JsonResponse
    {
        $data = collect();
        $timeLimit = Setting::where('field', 'time')->first()->value ?? '00:00';
        $now = Carbon::now();
        $project = Project::find($request->user()->project_id);
        // $attendance = null;
        $attendance = Attendance::where('user_id', $request->user()->id)->where('date', $now->toDateString())->latest()->first();
        // if ($now->gte(Carbon::parse($timeLimit))) {
        //     $attendance = Attendance::where('user_id', $request->user()->id)->where('created_at', '>=', Carbon::parse($timeLimit))->latest()->first();
        // } else {
        //     $attendance = Attendance::where('user_id', $request->user()->id)->where('created_at', '>=', Carbon::parse($timeLimit)->subDay())->where('created_at', '<=', Carbon::parse($timeLimit))->latest()->first();
        // }

        $todayLeave = Leave::where('user_id', $request->user()->id)->where('start_date', '<=', $now->toDateString())->where('to_date', '>=', $now->toDateString())->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->latest()->first();
        if ($todayLeave) {
            $data->put('canCheckIn', false);
            $data->put('canCheckOut', false);
            return $this->responseSuccessWithData("Attendance Data", $data);
        }

        if ($attendance == null) {
            if ($project->check_in_time && $project->check_out_time) {
                if ($now->gte(Carbon::parse($timeLimit)) && $now->lte(Carbon::parse($project->check_out_time))) {
                    $data->put('canCheckIn', true);
                    $data->put('canCheckOut', false);
                } else {
                    $data->put('canCheckIn', false);
                    $data->put('canCheckOut', true);
                }
            } else {
                $data->put('canCheckIn', true);
                $data->put('canCheckOut', false);
            }
        } else {
            if ($attendance->type == 'in') {
                $data->put('canCheckIn', false);
                $data->put('canCheckOut', true);
            } else {
                $data->put('canCheckIn', false);
                $data->put('canCheckOut', false);
            }
        }

        return $this->responseSuccessWithData("Attendance Data", $data);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lng' => 'required',
            'lat' => 'required',
            'reason' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $project = Project::find(Auth::user()->project_id);
        $now = Carbon::now();
        $timeLimit = Setting::where('field', 'time')->first()->value ?? '00:00';
        $late = Setting::where('field', 'late')->first()->value ?? 0;

        if ($now->gte(Carbon::parse($timeLimit))) {
            $lastAttendance = Attendance::where('user_id', $request->user()->id)->where('created_at', '>=', Carbon::parse($timeLimit))->latest()->first();
        } else {
            $lastAttendance = Attendance::where('user_id', $request->user()->id)->where('created_at', '>=', Carbon::parse($timeLimit)->subDay())->where('created_at', '<=', Carbon::parse($timeLimit))->latest()->first();
        }

        if ($this->calculateDistance($validated['lat'], $validated['lng'], $project->lat, $project->lng) > 20) {
            return $this->responseError('Lokasi terlalu jauh dengan ' . $project->name, 403);
        }

        $attendance = new Attendance();
        $attendance->lng = $validated['lng'];
        $attendance->lat = $validated['lat'];
        if ($lastAttendance == null) {
            if ($project->check_in_time && $project->check_out_time) {
                if ($now->gte(Carbon::parse($timeLimit)) && $now->lte(Carbon::parse($project->check_out_time))) {
                    if ($now->gte(Carbon::parse($project->check_in_time)->addMinutes($late)) && $validated['reason'] == null) {
                        return $this->responseError('Anda Terlambat. Isi Alasan', 403);
                    } else {
                        $attendance->date = $now->toDateString();
                        if ($project->check_out_time < $timeLimit && $now->gt(Carbon::parse($timeLimit))) {
                            $attendance->date = Carbon::now()->addDay()->toDateString();
                        }
                        $attendance->type = 'in';
                        if ($validated['reason'] != null) {
                            $attendance->status = 'Terlambat';
                            $leave = new Leave();
                            $leave->start_date = $now->toDateString();
                            $leave->user_id = $request->user()->id;
                            $leave->project_id = $request->user()->project_id;
                            $leave->to_date = $now->toDateString();
                            $leave->status = 2;
                            $leave->reason = $validated['reason'];
                            $leave->type = 'Izin Terlambat';
                            $leave->save();
                        } else {
                            $attendance->status = 'Tepat Waktu';
                        }
                    }
                } else {
                    $attendance->date = $now->toDateString();
                    if ($project->check_in_time > $timeLimit && $now->lt(Carbon::parse($timeLimit))) {
                        $attendance->date = Carbon::now()->subDay()->toDateString();
                    }
                    $attendance->type = 'out';
                    $attendance->status = 'Tepat Waktu';
                }
            } else {
                $attendance->type = 'in';
                $attendance->status = 'Tepat Waktu';
            }
        } else {
            if ($lastAttendance->type == 'in') {
                if ($project->check_out_time) {
                    if ($now->lt(Carbon::parse($project->check_out_time)) && $validated['reason'] == null) {
                        return $this->responseError('Anda Pulang Cepat. Isi Alasan', 403);
                    } else {
                        $attendance->date = $now->toDateString();
                        if ($project->check_in_time > $timeLimit && $now->lt(Carbon::parse($timeLimit))) {
                            $attendance->date = Carbon::now()->subDay()->toDateString();
                        }
                        $attendance->type = 'out';
                        if ($validated['reason'] != null) {
                            $attendance->status = 'Pulang Cepat';
                            $leave = new Leave();
                            $leave->start_date = $now->toDateString();
                            $leave->user_id = $request->user()->id;
                            $leave->project_id = $request->user()->project_id;
                            $leave->to_date = $now->toDateString();
                            $leave->status = 2;
                            $leave->reason = $validated['reason'];
                            $leave->type = 'Izin Pulang Cepat';
                            $leave->save();
                        } else {
                            $attendance->status = 'Tepat Waktu';
                        }
                    }
                } else {
                    $attendance->type = 'out';
                    $attendance->status = 'Tepat Waktu';
                }
            } else {
                return $this->responseError('Anda tidak bisa absen karena sudah absen keluar', 403);
            }
        }
        $attendance->user_id = $request->user()->id;
        $attendance->project_id = $request->user()->project_id;
        $attendance->save();

        return $this->responseSuccess("Attendance successfully");
    }

    private function calculateDistance($lattitudeForm, $longitudeForm, $lattitudeTo, $longitudeTo): float
    {
        $latFrom = deg2rad($lattitudeForm);
        $lonFrom = deg2rad($longitudeForm);
        $latTo = deg2rad($lattitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * 6371000;
    }
}

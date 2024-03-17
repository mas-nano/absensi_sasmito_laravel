<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->responseSuccessWithData('data project', Project::all());
    }

    public function getUsers(Project $project, Request $request): JsonResponse
    {
        return $this->responseSuccessWithData('user data', ['users' => User::with(['profile', 'role', 'attendances' => function ($query) use ($request) {
            $query->where('date', $request->query('date', date('Y-m-d')));
        }, 'leaves' => function ($query) use ($request, $project) {
            $query->where('start_date', '<=', $request->query('date', date('Y-m-d')))->where('to_date', '>=', $request->query('date', date('Y-m-d')))->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('project_id', $project->id);
        }])->where('project_id', $project->id)->when($request->get('search') != "" || $request->query('search') != null, function ($query) use ($request) {
            $query->where('name', 'ilike', '%' . $request->query('name') . '%');
        })->get(), 'project' => $project]);
    }

    public function listAttendance(Project $project, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $user = User::with(['profile', 'position', 'attendances' => function ($query) use ($project, $validated) {
            $query->where('project_id', $project->id)->where('date', $validated['date']);
        }, 'leaves' => function ($query) use ($project, $validated) {
            $query->where('start_date', '<=', Carbon::parse($validated['date']))->where('to_date', '>=', Carbon::parse($validated['date']))->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('project_id', $project->id);
        }])->where('id', $validated['user_id'])->first();

        return $this->responseSuccessWithData('data user', $user);
    }

    public function selfReport(User $user, Request $request): JsonResponse
    {
        $user->load(['attendances' => function ($query) use ($request) {
            $query->whereBetween('created_at', [Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01'), Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01')->addMonth()]);
        }, 'leaves' => function ($query) use ($request) {
            $query->whereBetween('start_date', [Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01'), Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01')->addMonth()]);
        }]);
        return $this->responseSuccessWithData('data user', $user);
    }
}

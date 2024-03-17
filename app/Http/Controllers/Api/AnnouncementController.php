<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    public function index(): JsonResponse
    {
        $announcement = Announcement::latest()->get();
        return $this->responseSuccessWithData('Announcement data', $announcement);
    }

    public function show(Announcement $announcement): JsonResponse
    {
        return $this->responseSuccessWithData('Announcement data', $announcement);
    }

    public function pdf(Announcement $announcement)
    {
        return response()->file('public/storage/' . $announcement->attachment);
    }
}

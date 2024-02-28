<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function username(): string
    {
        return 'username';
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [], [
            'username' => 'Username',
            'password' => 'Password'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }
        $validated = $validator->validated();

        $token = Auth::attempt($validated);
        if (!$token) {
            return $this->responseError('Email atau password Anda salah', 401);
        }

        if ((Auth::user()->role_id == null || Auth::user()->role_id == 1) && Auth::user()->project_id == null) {
            Auth::logout();
            return $this->responseError('Tidak bisa login. Hubungi admin untuk mengatur proyek dan role', 401);
        }
        $user = User::with('role')->find(Auth::user()->getAuthIdentifier());
        $user->setAttribute('accessToken', $token);
        $user->setAttribute('type', 'Bearer');
        return $this->responseSuccessWithData('Login successfully', $user);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->responseSuccess("Successfully logged out");
    }

    public function refresh(): JsonResponse
    {
        $data = [
            "accessToken" => Auth::refresh(),
            "type" => "Bearer",
        ];

        return $this->responseSuccessWithData("Refresh success", $data);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->responseSuccessWithData('User found', $request->user()->load('role', 'profile'));
    }
}

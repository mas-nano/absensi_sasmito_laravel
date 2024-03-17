<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Traits\UploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;
use Validator;

class UserController extends Controller
{
    use UploadFile;
    public function index(): JsonResponse
    {
        return $this->responseSuccessWithData();
    }

    public function updateMe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_title' => 'nullable',
            'name' => 'required',
            'last_title' => 'nullable',
            'email' => 'required',
            'username' => 'required',
            'address' => 'required',
            'phone_number' => 'required|phone:ID',
            'password' => 'nullable|confirmed',
            'password_confirmation' => 'nullable',
        ], [], [
            'first_title' => 'Gelar Depan',
            'name' => 'Nama Lengkap',
            'last_title' => 'Gelar Belakang',
            'email' => 'Email',
            'username' => 'Username',
            'address' => 'Alamat',
            'phone_number' => 'Nomor Telepon',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $user = User::find($request->user()->id);
        $profile = Profile::where('user_id', $request->user()->id)->first();

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        if ($validated['password']) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        $phone = new PhoneNumber($validated['phone_number'], 'ID');

        if ($profile) {
            $profile->name = $validated['name'];
            $profile->first_title = $validated['first_title'];
            $profile->last_title = $validated['last_title'];
            $profile->phone_number = $phone->formatE164();
            $profile->address = $validated['address'];
        } else {
            $profile = new Profile();
            $profile->name = $validated['name'];
            $profile->first_title = $validated['first_title'];
            $profile->last_title = $validated['last_title'];
            $profile->phone_number = $phone->formatE164();
            $profile->address = $validated['address'];
            $profile->user_id = $request->user()->id;
        }
        $profile->save();

        return $this->responseSuccess('Profile updated');
    }

    public function updateProfilePicture(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), ['photo_profile' => 'required|image|mimes:png,jpg,jpeg'], [], ['photo_profile' => 'Foto Profil']);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $path = $this->upload('photo_profile', $validated['photo_profile']);

        $profile = Profile::where('user_id', $request->user()->id)->first();
        if ($profile) {
            $profile->profile_picture = $path;
        } else {
            $profile = new Profile();
            $profile->profile_picture = $path;
            $profile->user_id = $request->user()->id;
        }
        $profile->save();
        return $this->responseSuccess('Profile updated');
    }
}

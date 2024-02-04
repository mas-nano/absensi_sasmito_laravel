<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


trait UploadFile
{
    public function upload($path, $file, $isGoogle = false): string
    {
        $name = explode('.', $file->getClientOriginalName());
        $fullName = Str::replace(' ', '_', $name[0]) . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $fullName, $isGoogle ? 'google' : 'public');
    }
}

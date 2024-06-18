<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, Uuid;

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'project_id');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'project_id');
    }

    public function overtimeLimit(): HasMany
    {
        return $this->hasMany(OvertimeLimit::class, 'project_id');
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'project_id');
    }
}

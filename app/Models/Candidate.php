<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'resume',
        'skills',
        'experience',
        'qualification',
        'source',
        'status',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}

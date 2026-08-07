<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    protected $fillable = [
        'title',
        'department_id',
        'vacancies',
        'employment_type',
        'location',
        'salary_range',
        'description',
        'closing_date',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_type',
        'period',
        'rating',
        'strengths',
        'improvements',
        'review_date',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }

    public function appraisal()
    {
        return $this->hasOne(Appraisal::class);
    }
}

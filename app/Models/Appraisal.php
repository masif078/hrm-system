<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    protected $fillable = [
        'employee_id',
        'performance_review_id',
        'rating_class',
        'action_type',
        'previous_salary',
        'new_salary',
        'previous_designation_id',
        'new_designation_id',
        'effective_date',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function performanceReview()
    {
        return $this->belongsTo(PerformanceReview::class);
    }

    public function previousDesignation()
    {
        return $this->belongsTo(Designation::class, 'previous_designation_id');
    }

    public function newDesignation()
    {
        return $this->belongsTo(Designation::class, 'new_designation_id');
    }
}

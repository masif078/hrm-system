<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type',
        'allocated',
        'used',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

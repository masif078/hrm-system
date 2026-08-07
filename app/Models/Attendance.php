<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'late_arrival',
        'early_checkout',
        'working_hours',
        'overtime_hours',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedCheckInAttribute()
    {
        if (!$this->check_in) {
            return '-';
        }
        try {
            return \Carbon\Carbon::parse($this->check_in)->format('h:i A');
        } catch (\Exception $e) {
            return $this->check_in;
        }
    }

    public function getFormattedCheckOutAttribute()
    {
        if (!$this->check_out) {
            return '-';
        }
        try {
            return \Carbon\Carbon::parse($this->check_out)->format('h:i A');
        } catch (\Exception $e) {
            return $this->check_out;
        }
    }
}

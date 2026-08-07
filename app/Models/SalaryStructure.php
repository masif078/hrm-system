<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'house_allowance',
        'medical_allowance',
        'transport_allowance',
        'other_allowance',
        'tax',
        'provident_fund',
        'other_deduction',
        'net_salary',
        'effective_from',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

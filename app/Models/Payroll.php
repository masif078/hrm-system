<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'salary_structure_id',
        'month',
        'year',
        'gross_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'payment_date',
        'payment_status',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }
}

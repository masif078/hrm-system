<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiAssignment extends Model
{
    protected $fillable = [
        'employee_id',
        'kpi_id',
        'assigned_date',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class);
    }
}

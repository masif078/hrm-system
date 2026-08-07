<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiScore extends Model
{
    protected $fillable = [
        'employee_id',
        'kpi_id',
        'score',
        'period_month',
        'period_year',
        'comments',
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

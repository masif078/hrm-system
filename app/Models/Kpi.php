<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $fillable = [
        'name',
        'description',
        'target_value',
        'unit',
    ];

    public function assignments()
    {
        return $this->hasMany(KpiAssignment::class);
    }

    public function scores()
    {
        return $this->hasMany(KpiScore::class);
    }
}

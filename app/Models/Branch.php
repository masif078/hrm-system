<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'location',
        'manager_id',
        'status',
    ];

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
}

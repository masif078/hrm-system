<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'late_mark_after',
        'early_checkout_before',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

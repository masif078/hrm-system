<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPolicy extends Model
{
    protected $fillable = [
        'title',
        'type',
        'content',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'login_histories';

    protected $fillable = [
        'user_id',
        'login_time',
        'logout_time',
        'ip',
        'browser',
        'device',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

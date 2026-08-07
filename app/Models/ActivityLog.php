<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $details = null)
    {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'details' => $details,
            'ip' => request()->ip(),
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'date',
        'time',
        'interviewer_id',
        'meeting_link',
        'notes',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(Employee::class, 'interviewer_id');
    }

    public function feedback()
    {
        return $this->hasOne(InterviewFeedback::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewFeedback extends Model
{
    protected $table = 'interview_feedback';

    protected $fillable = [
        'interview_id',
        'rating_technical',
        'rating_communication',
        'rating_behavior',
        'rating_confidence',
        'rating_overall',
        'comments',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}

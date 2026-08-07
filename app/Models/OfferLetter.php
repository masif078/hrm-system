<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    protected $fillable = [
        'application_id',
        'salary_offered',
        'joining_date',
        'status',
        'sent_date',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}

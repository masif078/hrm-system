<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'candidate_id',
        'job_opening_id',
        'status',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function offerLetter()
    {
        return $this->hasOne(OfferLetter::class);
    }
}

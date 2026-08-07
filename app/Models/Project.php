<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_code',
        'project_name',
        'client_id',
        'project_manager_id',
        'start_date',
        'end_date',
        'budget',
        'status',
        'description',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'project_manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Designation;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'department_id',
        'designation_id',
        'joining_date',
        'salary',
        'status',
        'shift_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function salaryStructures()
    {
        return $this->hasMany(SalaryStructure::class);
    }

    public function activeSalaryStructure()
    {
        return $this->hasOne(SalaryStructure::class)->where('status', 'active');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function kpiAssignments()
    {
        return $this->hasMany(KpiAssignment::class);
    }

    public function kpiScores()
    {
        return $this->hasMany(KpiScore::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }
}
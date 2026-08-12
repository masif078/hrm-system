<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ReportController;
use App\Models\Department;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SalaryStructureController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;

use App\Http\Controllers\KpiController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\AppraisalController;

use App\Http\Controllers\JobOpeningController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\OfferLetterController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetAssignmentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompanyPolicyController;
use App\Http\Controllers\SettingController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);


/*
|--------------------------------------------------------------------------
| Dashboard Route
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect('/admin/dashboard');
    if ($role === 'employee') return redirect('/employee/dashboard');
    if ($role === 'client') return redirect('/client/dashboard');
    return redirect('/admin/dashboard');
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');


    // Departments
    Route::resource('departments', DepartmentController::class);


    // Employees
    Route::resource('employees', EmployeeController::class);


    // Designations
    Route::resource('designations', DesignationController::class);


    // Clients
    Route::resource('clients', ClientController::class);


    // Projects
    Route::resource('projects', ProjectController::class);


    // Tasks
    Route::resource('tasks', TaskController::class);


    // Attendance
    Route::resource('attendances', AttendanceController::class);


    // Leaves
    Route::resource('leaves', LeaveController::class);


    /*
    |--------------------------------------------------------------------------
    | Leave Approval
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/leaves/{leave}/approve',
        [LeaveController::class, 'approve']
    )->name('leaves.approve');


    /*
    |--------------------------------------------------------------------------
    | Leave Rejection
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/leaves/{leave}/reject',
        [LeaveController::class, 'reject']
    )->name('leaves.reject');


    /*
    |--------------------------------------------------------------------------
    | Reports Index
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');


    /*
    |--------------------------------------------------------------------------
    | Employee Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/employees',
        [ReportController::class, 'employees']
    )->name('reports.employees');


    /*
    |--------------------------------------------------------------------------
    | Attendance Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/attendance',
        [ReportController::class, 'attendance']
    )->name('reports.attendance');


    /*
    |--------------------------------------------------------------------------
    | Leave Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/leaves',
        [ReportController::class, 'leaves']
    )->name('reports.leaves');


    /*
    |--------------------------------------------------------------------------
    | Project Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/projects',
        [ReportController::class, 'projects']
    )->name('reports.projects');


    /*
    |--------------------------------------------------------------------------
    | Employee Excel
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/employees/excel',
        [ReportController::class, 'employeeExcel']
    )->name('reports.employees.excel');


    /*
    |--------------------------------------------------------------------------
    | Employee PDF
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/employees/pdf',
        [ReportController::class, 'employeePdf']
    )->name('reports.employees.pdf');


    /*
    |--------------------------------------------------------------------------
    | Reports Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/dashboard',
        [ReportController::class, 'dashboard']
    )->name('reports.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Department Designations
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/departments/{department}/designations',
        function (Department $department) {
            return response()->json(
                $department->designations
            );
        }
    );

    // Salary Structures
    Route::resource('salary-structures', SalaryStructureController::class);

    // Payroll
    Route::get('/payrolls/dashboard', [PayrollController::class, 'dashboard'])->name('payrolls.dashboard');
    Route::resource('payrolls', PayrollController::class);

    // Payroll Report
    Route::get('/reports/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');

    // Shifts
    Route::resource('shifts', ShiftController::class);

    // Holidays
    Route::resource('holidays', HolidayController::class);

    // Leave Balances
    Route::resource('leave-balances', LeaveBalanceController::class);

    // KPIs
    Route::get('/kpis/assign', [KpiController::class, 'showAssign'])->name('kpis.assign');
    Route::post('/kpis/assign', [KpiController::class, 'storeAssign'])->name('kpis.assign.store');
    Route::get('/kpis/score', [KpiController::class, 'showScore'])->name('kpis.score');
    Route::post('/kpis/score', [KpiController::class, 'storeScore'])->name('kpis.score.store');
    Route::resource('kpis', KpiController::class);

    // Performance Report
    Route::get('/reports/performance', [ReportController::class, 'performance'])->name('reports.performance');

});


/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:employee'])->group(function () {

    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])
        ->name('employee.dashboard');

    // My Attendance
    Route::get('/employee/attendances', [AttendanceController::class, 'employeeIndex'])
        ->name('employee.attendances.index');
    Route::post('/employee/attendances/checkin', [AttendanceController::class, 'checkIn'])
        ->name('employee.attendances.checkin');
    Route::post('/employee/attendances/checkout', [AttendanceController::class, 'checkOut'])
        ->name('employee.attendances.checkout');

    // My Leaves
    Route::get('/employee/leaves', [LeaveController::class, 'employeeIndex'])
        ->name('employee.leaves.index');
    Route::get('/employee/leaves/create', [LeaveController::class, 'employeeCreate'])
        ->name('employee.leaves.create');
    Route::post('/employee/leaves', [LeaveController::class, 'employeeStore'])
        ->name('employee.leaves.store');

    // My Tasks
    Route::get('/employee/tasks', [TaskController::class, 'employeeIndex'])
        ->name('employee.tasks.index');
    Route::patch('/employee/tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('employee.tasks.update-status');

});


/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:client'])->group(function () {

    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
        ->name('client.dashboard');

    // My Projects
    Route::get('/client/projects', [ProjectController::class, 'clientIndex'])
        ->name('client.projects.index');

    // My Tasks
    Route::get('/client/tasks', [TaskController::class, 'clientIndex'])
        ->name('client.tasks.index');

});


/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/global-search', [\App\Http\Controllers\GlobalSearchController::class, 'search'])->name('global.search');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Payslip routes (accessible by both Admin and Employee)
    Route::resource('payslips', PayslipController::class)->only(['index', 'show']);
    Route::get('/payslips/{payslip}/download', [PayslipController::class, 'download'])->name('payslips.download');
    Route::get('/payslips/{payslip}/print', [PayslipController::class, 'print'])->name('payslips.print');

    // Attendance Calendar
    Route::get('/attendance/calendar', [CalendarController::class, 'index'])->name('attendance.calendar');

    // Goals Tracking
    Route::resource('goals', GoalController::class);

    // Performance Reviews
    Route::resource('performance-reviews', PerformanceReviewController::class);

    // Appraisals & Increments
    Route::resource('appraisals', AppraisalController::class);

    // ==========================================
    // Lecture 16: Recruitment Routes
    // ==========================================
    Route::resource('job-openings', JobOpeningController::class);
    Route::resource('candidates', CandidateController::class);
    Route::resource('applications', ApplicationController::class);
    Route::post('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
    
    Route::resource('interviews', InterviewController::class);
    Route::get('/interviews/{interview}/feedback', [InterviewController::class, 'feedback'])->name('interviews.feedback');
    Route::post('/interviews/{interview}/feedback', [InterviewController::class, 'storeFeedback'])->name('interviews.storeFeedback');
    
    Route::resource('offer-letters', OfferLetterController::class);
    Route::get('/offer-letters/{offer_letter}/print', [OfferLetterController::class, 'print'])->name('offer-letters.print');
    Route::post('/offer-letters/{offer_letter}/status', [OfferLetterController::class, 'updateStatus'])->name('offer-letters.status');

    // ==========================================
    // Lecture 17: Asset Management Routes
    // ==========================================
    Route::resource('asset-categories', AssetCategoryController::class);
    Route::resource('assets', AssetController::class);
    Route::post('/assets/{asset}/maintenance', [AssetController::class, 'addMaintenanceLog'])->name('assets.maintenance');
    Route::resource('asset-assignments', AssetAssignmentController::class);

    // ==========================================
    // Lecture 19: System Admin & Settings Routes
    // ==========================================
    Route::resource('branches', BranchController::class);
    Route::resource('company-policies', CompanyPolicyController::class);
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::get('/settings/login-history', [SettingController::class, 'loginHistory'])->name('settings.login-history');
    Route::get('/settings/activity-logs', [SettingController::class, 'activityLogs'])->name('settings.activity-logs');
    Route::get('/settings/permissions', [SettingController::class, 'permissionMatrix'])->name('settings.permissions');
    Route::post('/settings/permissions', [SettingController::class, 'updatePermissionMatrix'])->name('settings.permissions.update');

    // ==========================================
    // Additional Recruitment & Asset Reports
    // ==========================================
    Route::get('/reports/recruitment', [ReportController::class, 'recruitment'])->name('reports.recruitment');
    Route::get('/reports/assets', [ReportController::class, 'assets'])->name('reports.assets');
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
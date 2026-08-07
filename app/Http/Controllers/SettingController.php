<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\LoginHistory;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SettingController extends Controller
{
    public function index()
    {
        // Fetch current settings
        $companyName = CompanySetting::get('company_name', 'HRM System');
        $companyEmail = CompanySetting::get('company_email', 'admin@hrm.com');
        $companyPhone = CompanySetting::get('company_phone', '000-000-000');
        $companyAddress = CompanySetting::get('company_address', '123 Main St');
        $companyTimezone = CompanySetting::get('company_timezone', 'UTC');
        $companyCurrency = CompanySetting::get('company_currency', 'PKR');
        $smtpHost = CompanySetting::get('smtp_host', 'smtp.mailtrap.io');
        $smtpPort = CompanySetting::get('smtp_port', '2525');

        // Health Checks
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();

        // Database status
        $dbStatus = 'Connected';
        $dbLatencyStart = microtime(true);
        try {
            DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $dbLatencyStart) * 1000, 2) . ' ms';
        } catch (\Exception $e) {
            $dbStatus = 'Disconnected';
            $dbLatency = 'N/A';
        }

        // Storage status
        $diskTotal = disk_total_space(base_path());
        $diskFree = disk_free_space(base_path());
        $diskUsed = $diskTotal - $diskFree;
        $diskUsagePercent = round(($diskUsed / $diskTotal) * 100, 1);
        $storageText = round($diskUsed / (1024 * 1024 * 1024), 2) . ' GB / ' . round($diskTotal / (1024 * 1024 * 1024), 2) . ' GB (' . $diskUsagePercent . '%)';

        // Mail Status
        $mailStatus = config('mail.mailers.smtp.host') ? 'Configured' : 'Not Configured';

        // Backups directory status
        $backupDir = storage_path('app/backups');
        $backupStatus = file_exists($backupDir) ? 'Directory Active' : 'Directory Not Initialized';

        $activeUsersCount = User::count();

        $health = [
            'php_version' => $phpVersion,
            'laravel_version' => $laravelVersion,
            'db_status' => $dbStatus,
            'db_latency' => $dbLatency,
            'storage' => $storageText,
            'mail_status' => $mailStatus,
            'backup_status' => $backupStatus,
            'active_users' => $activeUsersCount,
        ];

        return view('settings.index', compact(
            'companyName', 'companyEmail', 'companyPhone', 'companyAddress',
            'companyTimezone', 'companyCurrency', 'smtpHost', 'smtpPort', 'health'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string',
            'company_timezone' => 'required|string',
            'company_currency' => 'required|string|max:10',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            CompanySetting::set($key, $value);
        }

        ActivityLog::log('Updated System Settings', 'Updated company profile details');

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    public function loginHistory()
    {
        $logs = LoginHistory::with('user')->latest()->get();
        return view('settings.login-history', compact('logs'));
    }

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->latest()->get();
        return view('settings.activity-logs', compact('logs'));
    }

    public function permissionMatrix()
    {
        $permissions = json_decode(CompanySetting::get('role_permissions', '[]'), true);
        if (empty($permissions)) {
            // Default permission list structure
            $permissions = [
                'admin' => [
                    'Recruitment' => true,
                    'Assets' => true,
                    'Settings' => true,
                    'Payroll' => true,
                ],
                'employee' => [
                    'Recruitment' => false,
                    'Assets' => true,
                    'Settings' => false,
                    'Payroll' => false,
                ]
            ];
        }

        return view('settings.permissions', compact('permissions'));
    }

    public function updatePermissionMatrix(Request $request)
    {
        $inputPermissions = $request->input('permissions', []);
        
        // Ensure keys represent current roles
        $roles = ['admin', 'employee'];
        $modules = ['Recruitment', 'Assets', 'Settings', 'Payroll'];
        
        $permissions = [];
        foreach ($roles as $role) {
            foreach ($modules as $module) {
                $permissions[$role][$module] = isset($inputPermissions[$role][$module]);
            }
        }

        CompanySetting::set('role_permissions', json_encode($permissions));
        ActivityLog::log('Updated Role-Permission Matrix', 'Custom permissions checkboxes saved');

        return redirect()->route('settings.permissions')->with('success', 'Custom permission matrix saved.');
    }
}

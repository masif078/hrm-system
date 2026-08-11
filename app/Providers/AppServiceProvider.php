<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\Blade::if('admin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
        });

        \Illuminate\Support\Facades\Blade::if('employee', function () {
            return auth()->check() && auth()->user()->role === 'employee';
        });

        \Illuminate\Support\Facades\Blade::if('client', function () {
            return auth()->check() && auth()->user()->role === 'client';
        });

        // Track user login history
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function ($event) {
                \App\Models\LoginHistory::create([
                    'user_id' => $event->user->id,
                    'login_time' => now(),
                    'ip' => request()->ip(),
                    'browser' => substr(request()->userAgent(), 0, 180),
                    'device' => str_contains(strtolower(request()->userAgent()), 'mobile') ? 'Mobile' : 'Desktop',
                ]);
                \App\Models\ActivityLog::log('User Logged In', "User: {$event->user->email}");
            }
        );

        // Track user logout history
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            function ($event) {
                if ($event->user) {
                    $latest = \App\Models\LoginHistory::where('user_id', $event->user->id)
                        ->whereNull('logout_time')
                        ->latest()
                        ->first();
                    if ($latest) {
                        $latest->update(['logout_time' => now()]);
                    }
                    \App\Models\ActivityLog::log('User Logged Out', "User: {$event->user->email}");
                }
            }
        );
    }
}

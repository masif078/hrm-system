<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel HRM System') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
                min-height: 100vh;
                font-family: system-ui, -apple-system, sans-serif;
            }
            .auth-card {
                background: #ffffff;
                border-radius: 16px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.5), 0 15px 15px -5px rgba(0, 0, 0, 0.3);
            }
            .brand-laravel-red {
                color: #FF2D20;
            }
            .logo-glow {
                filter: drop-shadow(0 0 14px rgba(255, 45, 32, 0.65));
                transition: transform 0.3s ease;
            }
            .logo-glow:hover {
                transform: scale(1.06);
            }
            /* Filled Highlighted Green Primary Button */
            .btn-laravel-primary {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                color: #ffffff !important;
                font-weight: 700 !important;
                border: none !important;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4) !important;
                transition: all 0.25s ease-in-out !important;
            }
            .btn-laravel-primary:hover {
                background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
                box-shadow: 0 6px 22px rgba(16, 185, 129, 0.6) !important;
                transform: translateY(-2px);
            }
            /* Custom focus highlights on input fields */
            input:focus, select:focus {
                border-color: #10b981 !important;
                box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25) !important;
            }
        </style>
    </head>
    <body>
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5 px-3">

            <!-- Highlighted Laravel Branding Header -->
            <a href="/" class="d-flex align-items-center gap-3 text-decoration-none mb-4">
                <div class="logo-glow">
                    <x-application-logo style="width: 58px; height: 58px; fill: #FF2D20;" />
                </div>
                <div class="text-white text-start">
                    <h3 class="fw-bold mb-0 lh-1" style="letter-spacing: -0.5px;">
                        <span class="brand-laravel-red">Laravel</span> HRM
                    </h3>
                    <small class="text-light opacity-75" style="font-size: 0.85rem;">Human Resource Management System</small>
                </div>
            </a>

            <!-- Auth Form Card Container -->
            <div class="card auth-card" style="width: 100%; max-width: 450px;">
                <div class="card-body p-4 p-sm-5">
                    {{ $slot }}
                </div>
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HRM System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #3B82F6;
            --primary-dark: #1D4ED8;
            --bg-page: #F8FAFC;
            --surface: #FFFFFF;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
            --border-color: #F1F5F9;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-primary);
            font-family: system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        #wrapper {
            overflow-x: hidden;
            display: flex;
            min-height: 100vh;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            transition: margin 0.25s ease-out;
            z-index: 1000;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: -270px;
        }

        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                margin-left: -270px;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.08) !important;
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        /* ----------------------------------------------------
           ICON-ONLY ACTION BUTTONS SYSTEM (CYAN, YELLOW, RED)
           ---------------------------------------------------- */
        .btn-action-view, .table .btn-info, .table .btn-outline-info {
            background-color: #00D2FE !important;
            color: #000000 !important;
            font-weight: 600 !important;
            border: none !important;
            border-radius: 10px !important;
            width: 34px !important;
            height: 34px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            box-shadow: 0 2px 4px rgba(0, 210, 254, 0.25) !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
        }
        .btn-action-view:hover, .table .btn-info:hover, .table .btn-outline-info:hover {
            background-color: #00B8E6 !important;
            color: #000000 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(0, 210, 254, 0.4) !important;
        }

        .btn-action-edit, .table .btn-warning, .table .btn-outline-warning {
            background-color: #FFC107 !important;
            color: #000000 !important;
            font-weight: 600 !important;
            border: none !important;
            border-radius: 10px !important;
            width: 34px !important;
            height: 34px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            box-shadow: 0 2px 4px rgba(255, 193, 7, 0.25) !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
        }
        .btn-action-edit:hover, .table .btn-warning:hover, .table .btn-outline-warning:hover {
            background-color: #E0A800 !important;
            color: #000000 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4) !important;
        }

        .btn-action-delete, .table .btn-danger, .table .btn-outline-danger {
            background-color: #E11D48 !important;
            color: #FFFFFF !important;
            font-weight: 600 !important;
            border: none !important;
            border-radius: 10px !important;
            width: 34px !important;
            height: 34px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            box-shadow: 0 2px 4px rgba(225, 29, 72, 0.25) !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
        }
        .btn-action-delete:hover, .table .btn-danger:hover, .table .btn-outline-danger:hover {
            background-color: #BE123C !important;
            color: #FFFFFF !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(225, 29, 72, 0.4) !important;
        }
    </style>
</head>

<body>

@auth
<div id="wrapper">

    {{-- Sidebar --}}
    <div id="sidebar-wrapper">
        @include('layouts.sidebar')
    </div>

    {{-- Main Content --}}
    <div class="flex-grow-1 d-flex flex-column" id="page-content" style="background-color: #F8FAFC;">

        @include('layouts.navigation')

        <main class="container-fluid py-4 px-3 px-md-4 px-xl-5 flex-grow-1">
            @yield('content')
        </main>

        @include('layouts.footer')

    </div>

</div>
@else

    @include('layouts.navigation')

    <main class="container py-5">
        @yield('content')
    </main>

@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sidebar Toggle
        var menuToggle = document.getElementById("menu-toggle");
        if (menuToggle) {
            menuToggle.addEventListener("click", function (e) {
                e.preventDefault();
                var wrapper = document.getElementById("wrapper");
                if (wrapper) {
                    wrapper.classList.toggle("toggled");
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener("click", function (e) {
            // Profile Dropdown
            var profileBtn = document.getElementById("navbarProfileBtn");
            var profileMenu = document.getElementById("navbarProfileMenu");
            if (profileBtn && profileMenu && !profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.remove("show");
                profileMenu.style.display = "none";
            }

            // Notification Bell Dropdown
            var notifBtn = document.getElementById("notificationBellBtn");
            var notifMenu = document.getElementById("notificationDropdownMenu");
            if (notifBtn && notifMenu && !notifBtn.contains(e.target) && !notifMenu.contains(e.target)) {
                notifMenu.classList.remove("show");
                notifMenu.style.display = "none";
            }

            // Chat Dropdown
            var chatBtn = document.getElementById("chatIconBtn");
            var chatMenu = document.getElementById("chatDropdownMenu");
            if (chatBtn && chatMenu && !chatBtn.contains(e.target) && !chatMenu.contains(e.target)) {
                chatMenu.classList.remove("show");
                chatMenu.style.display = "none";
            }
        });
    });
</script>

</body>
</html>
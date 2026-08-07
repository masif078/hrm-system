<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'HRM System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

@auth

<div class="d-flex" id="wrapper">

    {{-- Sidebar --}}
    <div id="sidebar-wrapper">

        @include('layouts.sidebar')

    </div>

    {{-- Main Content --}}
    <div class="flex-grow-1" id="page-content">

        @include('layouts.navigation')

        <div class="container-fluid py-4 px-5">

            @yield('content')

            @include('layouts.footer')

        </div>

    </div>

</div>

@else

    @include('layouts.navigation')

    <div class="container mt-5">

        @yield('content')

    </div>

@endauth



</body>
</html>
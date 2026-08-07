<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

    <div class="container-fluid">

        <button class="btn btn-outline-light me-3" id="menu-toggle">
            ☰
        </button>

        <span class="navbar-brand fw-bold">
            HRM System
        </span>

        @auth

        <div class="ms-auto d-flex align-items-center gap-3">

            <div class="dropdown">
                <button class="btn btn-dark dropdown-toggle text-white border-0" type="button" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

        @endauth

        @guest

        <div class="ms-auto d-flex align-items-center">
            <a href="/login" class="btn btn-light btn-sm me-2">Login</a>
            <a href="/register" class="btn btn-warning btn-sm">Register</a>
        </div>

        @endguest

    </div>

</nav>

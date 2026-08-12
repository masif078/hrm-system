<nav class="navbar navbar-expand-lg bg-white border-bottom border-light-subtle sticky-top shadow-sm py-2.5 px-3 px-md-4">

    <div class="container-fluid">

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light border-0 shadow-none rounded-3 d-flex align-items-center justify-content-center p-2" id="menu-toggle" aria-label="Toggle Sidebar" style="width: 38px; height: 38px;">
                <i class="bi bi-list fs-5 text-dark"></i>
            </button>

            <span class="navbar-brand fw-extrabold text-dark tracking-tight mb-0" style="font-size: 1.15rem;">
                HRM System
            </span>
        </div>

        @auth

        {{-- Center Global Search Bar Pill with Categorized Dropdown --}}
        <div class="d-none d-md-block mx-auto position-relative" style="width: 360px;" id="globalSearchContainer">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 ps-3 text-secondary" style="border-radius: 20px 0 0 20px;">
                    <i class="bi bi-search" style="font-size: 0.85rem;"></i>
                </span>
                <input type="text"
                       id="globalSearchInput"
                       class="form-control bg-light border-0 small text-dark py-2"
                       placeholder="Search anything..."
                       autocomplete="off"
                       style="border-radius: 0 20px 20px 0; font-size: 0.85rem;">
            </div>
            <span class="position-absolute end-0 top-50 translate-middle-y me-3 badge bg-white text-secondary border shadow-2xs small fw-normal cursor-pointer" id="ctrlKBadge" style="font-size: 0.68rem; border-radius: 6px; z-index: 5;">Ctrl + K</span>

            {{-- Categorized Search Results Dropdown Container --}}
            <div id="globalSearchResults"
                 class="dropdown-menu shadow-lg border border-light-subtle rounded-3 mt-2 p-0 position-absolute start-0 w-100 bg-white overflow-hidden"
                 style="display: none; z-index: 1050; max-height: 420px; overflow-y: auto;">
            </div>
        </div>

        {{-- Right Side Notification Icons & Profile Dropdown --}}
        <div class="ms-auto d-flex align-items-center gap-3">

            {{-- Interactive Notification Bell Dropdown --}}
            <div class="dropdown position-relative">
                <button type="button"
                        class="position-relative text-secondary border-0 bg-light rounded-circle d-flex align-items-center justify-content-center cursor-pointer shadow-none"
                        id="notificationBellBtn"
                        aria-label="Notifications"
                        style="width: 38px; height: 38px;"
                        onclick="let nm = document.getElementById('notificationDropdownMenu'); if(nm){ if(nm.style.display==='block'){ nm.style.display='none'; nm.classList.remove('show'); } else { nm.style.display='block'; nm.classList.add('show'); } }">
                    <i class="bi bi-bell fs-6 text-dark"></i>
                    @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-white" style="font-size: 0.58rem; padding: 0.25em 0.45em;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                {{-- Notification Dropdown Menu --}}
                <div class="dropdown-menu dropdown-menu-end shadow-lg border border-light-subtle rounded-3 mt-2 p-0 position-absolute end-0 bg-white"
                     id="notificationDropdownMenu"
                     style="min-width: 320px; max-width: 360px; z-index: 99999; display: none;">
                    
                    {{-- Header --}}
                    <div class="p-3 bg-light border-bottom border-light-subtle d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-bell-fill text-primary"></i>
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Notifications</h6>
                        </div>
                        @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-primary text-decoration-none fw-semibold border-0 bg-transparent" style="font-size: 0.75rem;">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Body: Notification List --}}
                    <div class="notification-list overflow-y-auto" style="max-height: 280px;">
                        @php
                            $userNotifications = Auth::user() ? Auth::user()->notifications->take(5) : collect();
                        @endphp

                        @forelse($userNotifications as $notification)
                            <div class="p-3 border-bottom border-light-subtle d-flex align-items-start gap-2.5 transition-all {{ $notification->read_at ? 'bg-white' : 'bg-primary-subtle bg-opacity-10' }}">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0 mt-0.5" style="width: 30px; height: 30px; font-size: 0.85rem;">
                                    <i class="bi bi-info-circle-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 text-dark fw-medium small lh-sm" style="font-size: 0.82rem;">
                                        {{ $notification->data['message'] ?? $notification->data['title'] ?? 'New notification received.' }}
                                    </p>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @if(!$notification->read_at)
                                    <span class="badge bg-primary rounded-circle p-1 mt-1" title="Unread" style="width: 8px; height: 8px;"></span>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-bell-slash fs-4 d-block mb-1 text-secondary opacity-50"></i>
                                <span class="small fw-medium">No notifications yet</span>
                            </div>
                        @endforelse
                    </div>

                    {{-- Footer --}}
                    <div class="p-2.5 bg-light border-top border-light-subtle text-center">
                        <a href="{{ route('notifications.index') }}" class="text-decoration-none text-primary fw-bold small hover-link d-block" style="font-size: 0.8rem;">
                            View all notifications &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- Interactive Chat Messages Dropdown --}}
            <div class="dropdown position-relative">
                <button type="button"
                        class="position-relative text-secondary border-0 bg-light rounded-circle d-flex align-items-center justify-content-center cursor-pointer shadow-none"
                        id="chatIconBtn"
                        aria-label="Messages"
                        style="width: 38px; height: 38px;"
                        onclick="let cm = document.getElementById('chatDropdownMenu'); if(cm){ if(cm.style.display==='block'){ cm.style.display='none'; cm.classList.remove('show'); } else { cm.style.display='block'; cm.classList.add('show'); } }">
                    <i class="bi bi-chat-text fs-6 text-dark"></i>
                </button>

                {{-- Chat Preview Dropdown Menu --}}
                <div class="dropdown-menu dropdown-menu-end shadow-lg border border-light-subtle rounded-3 mt-2 p-0 position-absolute end-0 bg-white"
                     id="chatDropdownMenu"
                     style="min-width: 320px; max-width: 360px; z-index: 99999; display: none;">
                    
                    {{-- Header --}}
                    <div class="p-3 bg-light border-bottom border-light-subtle d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-chat-dots-fill text-primary"></i>
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Messages & Chat</h6>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Recent Activity</small>
                    </div>

                    {{-- Body: Message List --}}
                    <div class="chat-message-list overflow-y-auto" style="max-height: 280px;">
                        @php
                            $recentEmployees = \App\Models\Employee::latest()->take(3)->get();
                        @endphp

                        @forelse($recentEmployees as $emp)
                            <a href="{{ route('employees.show', $emp) }}" class="d-flex align-items-start gap-2.5 p-3 border-bottom border-light-subtle text-decoration-none transition-all hover-bg-light">
                                <x-avatar :name="$emp->first_name . ' ' . $emp->last_name" size="sm" />
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="fw-bold text-dark text-truncate small">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                                        <small class="text-muted" style="font-size: 0.68rem;">{{ $emp->created_at ? $emp->created_at->diffForHumans() : 'Recently' }}</small>
                                    </div>
                                    <p class="mb-0 text-secondary text-truncate small" style="font-size: 0.76rem;">
                                        Staff member profile registered in {{ $emp->department?->name ?? 'General' }} department.
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-chat-square-dots fs-4 d-block mb-1 text-secondary opacity-50"></i>
                                <span class="small fw-medium">No recent messages</span>
                            </div>
                        @endforelse
                    </div>

                    {{-- Footer --}}
                    <div class="p-2.5 bg-light border-top border-light-subtle text-center">
                        <a href="{{ route('notifications.index') }}" class="text-decoration-none text-primary fw-bold small hover-link d-block" style="font-size: 0.8rem;">
                            View all messages &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- Guaranteed Clickable Profile Badge Dropdown --}}
            <div class="dropdown position-relative">
                <button class="btn btn-light border-0 bg-transparent dropdown-toggle text-dark d-flex align-items-center gap-2 p-1 rounded-3 shadow-none cursor-pointer"
                        type="button"
                        id="navbarProfileBtn"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        onclick="let m = document.getElementById('navbarProfileMenu'); if(m){ if(m.style.display==='block'){ m.style.display='none'; m.classList.remove('show'); } else { m.style.display='block'; m.classList.add('show'); } }">
                    <x-avatar :name="Auth::user()->name" size="sm" />
                    <div class="text-start d-none d-md-block lh-1 me-1">
                        <span class="fw-bold text-dark d-block" style="font-size: 0.88rem;">{{ Auth::user()->name }}</span>
                        <small class="text-muted text-uppercase d-block" style="font-size: 0.68rem; color: #64748B;">{{ Auth::user()->role }}</small>
                    </div>
                </button>
                
                <div class="dropdown-menu dropdown-menu-end shadow-lg border border-light-subtle rounded-3 mt-2 p-2 position-absolute end-0 bg-white"
                     id="navbarProfileMenu"
                     style="min-width: 210px; z-index: 99999; display: none;">
                    <div class="px-2 py-1.5 mb-2 bg-light rounded-2 border-bottom border-light-subtle">
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Signed in as</small>
                        <span class="fw-bold text-dark text-truncate d-block small">{{ Auth::user()->name }}</span>
                        <span class="text-secondary small d-block text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger fw-bold rounded-2 py-2 d-flex align-items-center gap-2 cursor-pointer">
                            <i class="bi bi-box-arrow-right fs-6"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        @endauth

        @guest

        <div class="ms-auto d-flex align-items-center gap-2">
            <a href="/login" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-semibold">Login</a>
            <a href="/register" class="btn btn-primary btn-sm px-3 rounded-3 fw-semibold text-white shadow-sm">Register</a>
        </div>

        @endguest

    </div>

</nav>

{{-- Global Search Interactive Script --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("globalSearchInput");
    const searchResults = document.getElementById("globalSearchResults");
    const searchContainer = document.getElementById("globalSearchContainer");
    let searchDebounceTimer = null;

    if (searchInput && searchResults) {

        // 1. Keyboard Shortcut (Ctrl + K or Cmd + K)
        document.addEventListener("keydown", function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k") {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });

        // 2. Perform Categorized Search on Typing
        searchInput.addEventListener("input", function () {
            const query = this.value.trim();

            clearTimeout(searchDebounceTimer);

            if (query.length < 2) {
                searchResults.style.display = "none";
                searchResults.innerHTML = "";
                return;
            }

            // Show loading indicator
            searchResults.style.display = "block";
            searchResults.innerHTML = `<div class="p-3 text-center text-muted small"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Searching...</div>`;

            searchDebounceTimer = setTimeout(function () {
                fetch(`/global-search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = "";
                        let totalResults = 0;

                        const categories = [
                            { key: 'employees', title: 'Employees', icon: 'bi-people-fill' },
                            { key: 'projects', title: 'Projects', icon: 'bi-briefcase-fill' },
                            { key: 'departments', title: 'Departments', icon: 'bi-building-fill' },
                            { key: 'leaves', title: 'Leaves', icon: 'bi-calendar-event-fill' }
                        ];

                        categories.forEach(cat => {
                            const items = data[cat.key] || [];
                            if (items.length > 0) {
                                totalResults += items.length;
                                html += `<div class="px-3 py-2 bg-light border-bottom border-top border-light-subtle d-flex align-items-center gap-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                            <i class="bi ${cat.icon} text-primary"></i>
                                            <span class="fw-bold text-uppercase text-secondary">${cat.title} (${items.length})</span>
                                         </div>`;

                                items.forEach(item => {
                                    html += `<a href="${item.url}" class="d-flex align-items-center justify-content-between text-decoration-none p-2.5 px-3 border-bottom border-light-subtle search-result-item">
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <div class="rounded-2 bg-light text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.95rem;">
                                                        <i class="bi ${item.icon}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">${item.title}</div>
                                                        <small class="text-secondary" style="font-size: 0.72rem;">${item.subtitle}</small>
                                                    </div>
                                                </div>
                                                <small class="text-muted fw-semibold">&rarr;</small>
                                             </a>`;
                                });
                            }
                        });

                        if (totalResults === 0) {
                            html = `<div class="p-3 text-center text-muted small">
                                        <i class="bi bi-search me-1"></i> No results found for "<strong>${escapeHtml(query)}</strong>"
                                    </div>`;
                        }

                        searchResults.innerHTML = html;
                    })
                    .catch(err => {
                        searchResults.innerHTML = `<div class="p-3 text-center text-danger small">Error fetching search results.</div>`;
                    });
            }, 250);
        });

        // 3. Hide Dropdown when clicking outside
        document.addEventListener("click", function (e) {
            if (searchContainer && !searchContainer.contains(e.target)) {
                searchResults.style.display = "none";
            }
        });

        // 4. Re-open on focus if query has length
        searchInput.addEventListener("focus", function () {
            if (this.value.trim().length >= 2 && searchResults.children.length > 0) {
                searchResults.style.display = "block";
            }
        });
    }

    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }
});
</script>

<style>
.search-result-item {
    transition: background-color 0.15s ease;
}
.search-result-item:hover {
    background-color: #F8FAFC !important;
}
.hover-bg-light:hover {
    background-color: #F8FAFC !important;
}
</style>

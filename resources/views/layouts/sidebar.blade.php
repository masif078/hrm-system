<div class="sidebar text-white p-3 shadow-lg d-flex flex-column" style="width: 270px; min-height: 100vh; background-color: #0B0F19; font-family: system-ui, -apple-system, sans-serif;">

    {{-- Brand Logo Header --}}
    <div class="d-flex align-items-center gap-3 mb-3 px-2 pt-2">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); font-size: 1.2rem;">
            <i class="bi bi-people-fill"></i>
        </div>
        <div>
            <h4 class="mt-0 mb-0 fw-extrabold text-white tracking-tight" style="font-size: 1.25rem;">HRM System</h4>
            @if(auth()->user()->role === 'admin')
                <small class="text-secondary opacity-75" style="font-size: 0.72rem;">Admin Panel</small>
            @elseif(auth()->user()->role === 'employee')
                <small class="text-secondary opacity-75" style="font-size: 0.72rem;">Employee Panel</small>
            @elseif(auth()->user()->role === 'client')
                <small class="text-secondary opacity-75" style="font-size: 0.72rem;">Client Panel</small>
            @endif
        </div>
    </div>

    {{-- Navigation Group Items --}}
    <div class="sidebar-nav flex-grow-1 overflow-y-auto pe-1">
        @if(auth()->user()->role === 'admin')

            <!-- MAIN -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-2 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Main</p>

            <a href="/dashboard"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('dashboard') || request()->is('admin/dashboard') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-grid-fill me-1"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <a href="{{ route('employees.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('employees*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-people-fill me-1"></i>
                    <span>Employees</span>
                </div>
                <small class="opacity-50">&rsaquo;</small>
            </a>

            <a href="{{ route('departments.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('departments*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-building me-1"></i>
                    <span>Departments</span>
                </div>
            </a>

            <a href="{{ route('designations.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('designations*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-award me-1"></i>
                    <span>Designations</span>
                </div>
            </a>

            <a href="{{ route('clients.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('clients*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-briefcase-fill me-1"></i>
                    <span>Clients</span>
                </div>
            </a>


            <!-- HR MANAGEMENT -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-4 tracking-wider px-2 d-flex align-items-center justify-content-between" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">
                <span>HR Management</span>
                <small class="opacity-50">&rsaquo;</small>
            </p>

            <a href="{{ route('attendances.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('attendances*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-clock-history me-1"></i>
                    <span>Attendance</span>
                </div>
            </a>

            <a href="{{ route('leaves.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('leaves*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-calendar-event me-1"></i>
                    <span>Leaves</span>
                </div>
            </a>

            <a href="{{ route('shifts.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('shifts*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    <span>Shift Management</span>
                </div>
            </a>

            <a href="{{ route('holidays.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('holidays*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-umbrella me-1"></i>
                    <span>Holiday Management</span>
                </div>
            </a>

            <a href="{{ route('leave-balances.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('leave-balances*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-card-checklist me-1"></i>
                    <span>Leave Balances</span>
                </div>
            </a>

            <a href="{{ route('attendance.calendar') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('attendance/calendar*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-calendar3 me-1"></i>
                    <span>Attendance Calendar</span>
                </div>
            </a>


            <!-- PAYROLL & SALARY -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-4 tracking-wider px-2 d-flex align-items-center justify-content-between" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">
                <span>Payroll & Salary</span>
                <small class="opacity-50">&rsaquo;</small>
            </p>

            <a href="{{ route('salary-structures.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('salary-structures*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-cash-stack me-1"></i>
                    <span>Salary Structures</span>
                </div>
            </a>

            <a href="{{ route('payrolls.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('payrolls*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-bank me-1"></i>
                    <span>Payroll Processing</span>
                </div>
            </a>

            <a href="{{ route('payslips.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('payslips*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-receipt me-1"></i>
                    <span>Payslips</span>
                </div>
            </a>


            <!-- PERFORMANCE -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-4 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Performance</p>

            <a href="{{ route('goals.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('goals*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-trophy me-1"></i>
                    <span>Goal Tracking</span>
                </div>
            </a>

            <a href="{{ route('performance-reviews.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('performance-reviews*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-star me-1"></i>
                    <span>Performance Reviews</span>
                </div>
            </a>

            <a href="{{ route('appraisals.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('appraisals*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-graph-up-arrow me-1"></i>
                    <span>Appraisals & Increments</span>
                </div>
            </a>


            <!-- RECRUITMENT -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-4 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Recruitment</p>

            <a href="{{ route('job-openings.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('job-openings*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-person-badge me-1"></i>
                    <span>Job Openings</span>
                </div>
            </a>

            <a href="{{ route('candidates.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('candidates*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-person-lines-fill me-1"></i>
                    <span>Candidates</span>
                </div>
            </a>

            <a href="{{ route('applications.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('applications*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-file-earmark-person me-1"></i>
                    <span>Applications</span>
                </div>
            </a>

            <a href="{{ route('interviews.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('interviews*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-calendar-check me-1"></i>
                    <span>Interviews</span>
                </div>
            </a>

            <a href="{{ route('offer-letters.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('offer-letters*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    <span>Offer Letters</span>
                </div>
            </a>


            <!-- OPERATIONS -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-4 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Operations</p>

            <a href="{{ route('projects.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('projects*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-kanban me-1"></i>
                    <span>Projects</span>
                </div>
            </a>

            <a href="{{ route('tasks.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('tasks*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-check2-square me-1"></i>
                    <span>Tasks</span>
                </div>
            </a>

            <a href="{{ route('asset-categories.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('asset-categories*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-tags me-1"></i>
                    <span>Asset Categories</span>
                </div>
            </a>

            <a href="{{ route('assets.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('assets*') && !request()->is('asset-categories*') && !request()->is('asset-assignments*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-laptop me-1"></i>
                    <span>Assets Inventory</span>
                </div>
            </a>

            <a href="{{ route('asset-assignments.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('asset-assignments*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    <span>Asset Assignments</span>
                </div>
            </a>


            <!-- ADMINISTRATION -->
            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-4 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Administration</p>

            <a href="{{ route('company-policies.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('company-policies*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-shield-check me-1"></i>
                    <span>Company Policies</span>
                </div>
            </a>

            <a href="{{ route('branches.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('branches*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-geo-alt me-1"></i>
                    <span>Branch Management</span>
                </div>
            </a>

            <a href="{{ route('settings.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('settings*') && !request()->is('settings/activity-logs*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-gear me-1"></i>
                    <span>System Settings</span>
                </div>
            </a>

            <a href="{{ route('reports.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('reports*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-bar-chart me-1"></i>
                    <span>Reports & Analytics</span>
                </div>
            </a>

            <a href="{{ route('settings.activity-logs') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('settings/activity-logs*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-list-columns-reverse me-1"></i>
                    <span>Activity Logs</span>
                </div>
            </a>

        @elseif(auth()->user()->role === 'employee')

            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-2 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Employee Menu</p>

            <a href="{{ route('employee.dashboard') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('employee/dashboard') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-grid-fill me-1"></i>
                    <span>My Dashboard</span>
                </div>
            </a>

            <a href="{{ route('employee.attendances.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('employee/attendances*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-clock-history me-1"></i>
                    <span>My Attendance</span>
                </div>
            </a>

            <a href="{{ route('employee.leaves.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('employee/leaves*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-calendar-event me-1"></i>
                    <span>My Leaves</span>
                </div>
            </a>

            <a href="{{ route('employee.tasks.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('employee/tasks*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-check2-square me-1"></i>
                    <span>My Tasks</span>
                </div>
            </a>

            <a href="{{ route('payslips.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('payslips*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-receipt me-1"></i>
                    <span>My Payslips</span>
                </div>
            </a>

            <a href="{{ route('attendance.calendar') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('attendance/calendar*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-calendar3 me-1"></i>
                    <span>My Calendar</span>
                </div>
            </a>

        @elseif(auth()->user()->role === 'client')

            <p class="text-uppercase text-secondary fw-bold small mb-2 mt-2 tracking-wider px-2" style="font-size: 0.68rem; letter-spacing: 0.8px; color: #64748B !important;">Client Menu</p>

            <a href="{{ route('client.dashboard') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('client/dashboard') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-grid-fill me-1"></i>
                    <span>Client Dashboard</span>
                </div>
            </a>

            <a href="{{ route('client.projects.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('client/projects*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-kanban me-1"></i>
                    <span>My Projects</span>
                </div>
            </a>

            <a href="{{ route('client.tasks.index') }}"
                class="nav-link-saas d-flex align-items-center justify-content-between text-decoration-none px-3 py-2.5 rounded-3 mb-1 fw-semibold {{ request()->is('client/tasks*') ? 'active-saas' : 'text-slate' }}">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi bi-check2-square me-1"></i>
                    <span>Project Tasks</span>
                </div>
            </a>

        @endif
    </div>

    {{-- Fixed Bottom Integrated User Profile Display Component --}}
    <div class="mt-auto pt-3 border-top border-secondary border-opacity-25">
        <div class="p-3 d-flex align-items-center gap-3 text-white shadow-sm" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 14px; gap: 12px;">
            <div class="flex-shrink-0">
                <x-avatar :name="Auth::user()->name" size="md" />
            </div>
            <div class="lh-sm overflow-hidden flex-grow-1">
                <h6 class="mt-0 mb-1 fw-bold text-white text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</h6>
                <small class="text-secondary opacity-75 text-uppercase d-block text-truncate fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                    @if(auth()->user()->role === 'admin') Administrator
                    @elseif(auth()->user()->role === 'employee') Employee
                    @elseif(auth()->user()->role === 'client') Client
                    @endif
                </small>
            </div>
        </div>
    </div>

</div>

<style>
    .text-slate {
        color: #94A3B8 !important;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }
    .text-slate:hover {
        color: #FFFFFF !important;
        background-color: rgba(255, 255, 255, 0.06) !important;
    }
    .active-saas {
        background: linear-gradient(135deg, #4F46E5 0%, #3B82F6 100%) !important;
        color: #FFFFFF !important;
        font-size: 0.88rem;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35) !important;
    }
</style>

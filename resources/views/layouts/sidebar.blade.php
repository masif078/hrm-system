<div class="sidebar bg-dark text-white p-3 shadow" style="width:270px;">

    <div class="text-center mb-4">
        <h4 class="mt-2 mb-0">HRM System</h4>
        @if(auth()->user()->role === 'admin')
            <small class="text-secondary">Admin Panel</small>
        @elseif(auth()->user()->role === 'employee')
            <small class="text-secondary">Employee Panel</small>
        @elseif(auth()->user()->role === 'client')
            <small class="text-secondary">Client Panel</small>
        @endif
    </div>

    <hr class="text-secondary">

    <div class="card bg-secondary text-white mb-4">
        <div class="card-body text-center d-flex flex-column align-items-center">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle mb-2 shadow-sm border border-2 border-light" style="width: 60px; height: 60px; object-fit: cover;">
            @else
                <div class="rounded-circle mb-2 bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm border border-2 border-light" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            <h6 class="mt-2 mb-0">{{ Auth::user()->name }}</h6>
            <small>
                @if(auth()->user()->role === 'admin') Administrator
                @elseif(auth()->user()->role === 'employee') Employee
                @elseif(auth()->user()->role === 'client') Client
                @endif
            </small>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')

        <p class="text-uppercase text-secondary small mb-2">Main Menu</p>

        <a href="/dashboard"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('employees.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('employees*') ? 'active' : '' }}">
            Employees
        </a>

        <a href="{{ route('departments.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('departments*') ? 'active' : '' }}">
            Departments
        </a>

        <a href="{{ route('designations.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('designations*') ? 'active' : '' }}">
            Designations
        </a>

        <a href="{{ route('clients.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('clients*') ? 'active' : '' }}">
            Clients
        </a>

        <a href="{{ route('projects.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('projects*') ? 'active' : '' }}">
            Projects
        </a>

        <a href="{{ route('tasks.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('tasks*') ? 'active' : '' }}">
            Tasks
        </a>

        <a href="{{ route('attendances.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('attendances*') ? 'active' : '' }}">
            Attendance
        </a>

        <a href="{{ route('leaves.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('leaves*') ? 'active' : '' }}">
            Leaves
        </a>

        <a href="{{ route('shifts.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('shifts*') ? 'active' : '' }}">
            Shift Management
        </a>

        <a href="{{ route('holidays.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('holidays*') ? 'active' : '' }}">
            Holiday Management
        </a>

        <a href="{{ route('leave-balances.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('leave-balances*') ? 'active' : '' }}">
            Leave Balances
        </a>

        <a href="{{ route('attendance.calendar') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('attendance/calendar*') ? 'active' : '' }}">
            Attendance Calendar
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">Payroll & Salary</p>

        <a href="{{ route('salary-structures.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('salary-structures*') ? 'active' : '' }}">
            Salary Structure
        </a>

        <a href="{{ route('payrolls.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('payrolls*') ? 'active' : '' }}">
            Payroll
        </a>

        <a href="{{ route('payslips.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('payslips*') ? 'active' : '' }}">
            Payslips
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">Reports</p>

        <a href="{{ route('reports.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('reports') ? 'active' : '' }}">
            Reports Dashboard
        </a>

        <a href="{{ route('reports.payroll') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('reports/payroll*') ? 'active' : '' }}">
            Salary Reports
        </a>

        <a href="{{ route('reports.performance') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('reports/performance*') ? 'active' : '' }}">
            Performance Reports
        </a>

        <a href="{{ route('reports.recruitment') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('reports/recruitment*') ? 'active' : '' }}">
            Recruitment Reports
        </a>

        <a href="{{ route('reports.assets') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('reports/assets*') ? 'active' : '' }}">
            Asset Reports
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">Performance</p>

        <a href="{{ route('kpis.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('kpis*') ? 'active' : '' }}">
            KPI Management
        </a>

        <a href="{{ route('goals.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('goals*') ? 'active' : '' }}">
            Goals Tracking
        </a>

        <a href="{{ route('performance-reviews.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('performance-reviews*') ? 'active' : '' }}">
            Performance Reviews
        </a>

        <a href="{{ route('appraisals.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('appraisals*') ? 'active' : '' }}">
            Appraisals & Increments
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">Recruitment</p>

        <a href="{{ route('job-openings.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('job-openings*') ? 'active' : '' }}">
            Job Openings
        </a>

        <a href="{{ route('candidates.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('candidates*') ? 'active' : '' }}">
            Candidates List
        </a>

        <a href="{{ route('applications.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('applications*') ? 'active' : '' }}">
            Job Applications
        </a>

        <a href="{{ route('interviews.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('interviews*') ? 'active' : '' }}">
            Interviews Schedule
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">Asset Management</p>

        <a href="{{ route('asset-categories.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('asset-categories*') ? 'active' : '' }}">
            Asset Categories
        </a>

        <a href="{{ route('assets.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('assets*') ? 'active' : '' }}">
            Company Assets
        </a>

        <a href="{{ route('asset-assignments.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('asset-assignments*') ? 'active' : '' }}">
            Asset Assignments
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">System Admin</p>

        <a href="{{ route('branches.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('branches*') ? 'active' : '' }}">
            Branch List
        </a>

        <a href="{{ route('company-policies.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('company-policies*') ? 'active' : '' }}">
            Company Policies
        </a>

        <a href="{{ route('settings.permissions') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('settings/permissions*') ? 'active' : '' }}">
            Custom Permissions
        </a>

        <a href="{{ route('settings.login-history') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('settings/login-history*') ? 'active' : '' }}">
            Login History
        </a>

        <a href="{{ route('settings.activity-logs') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('settings/activity-logs*') ? 'active' : '' }}">
            Activity Logs
        </a>

        <a href="{{ route('settings.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('settings') ? 'active' : '' }}">
            System Settings
        </a>

    @elseif(auth()->user()->role === 'employee')

        <p class="text-uppercase text-secondary small mb-2">Main Menu</p>

        <a href="{{ route('employee.dashboard') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('employee/dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('employee.attendances.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('employee/attendances*') ? 'active' : '' }}">
            My Attendance
        </a>

        <a href="{{ route('attendance.calendar') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('attendance/calendar*') ? 'active' : '' }}">
            My Calendar
        </a>

        <a href="{{ route('employee.leaves.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('employee/leaves*') ? 'active' : '' }}">
            My Leaves
        </a>

        <a href="{{ route('employee.tasks.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('employee/tasks*') ? 'active' : '' }}">
            My Tasks
        </a>

        <a href="{{ route('payslips.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('payslips*') ? 'active' : '' }}">
            My Payslips
        </a>

        <p class="text-uppercase text-secondary small mt-4 mb-2">My Performance</p>

        <a href="{{ route('goals.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('goals*') ? 'active' : '' }}">
            My Goals
        </a>

        <a href="{{ route('performance-reviews.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('performance-reviews*') ? 'active' : '' }}">
            My Reviews
        </a>

        <a href="{{ route('appraisals.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('appraisals*') ? 'active' : '' }}">
            My Appraisals
        </a>

    @elseif(auth()->user()->role === 'client')

        <p class="text-uppercase text-secondary small mb-2">Main Menu</p>

        <a href="{{ route('client.dashboard') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('client/dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('client.projects.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('client/projects*') ? 'active' : '' }}">
            My Projects
        </a>

        <a href="{{ route('client.tasks.index') }}"
            onclick="if(window.innerWidth<992){document.getElementById('wrapper').classList.remove('toggled')}"
            class="btn btn-dark w-100 text-start mb-2 {{ request()->is('client/tasks*') ? 'active' : '' }}">
            My Tasks
        </a>

    @endif

</div>

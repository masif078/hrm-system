@extends('layouts.app')

@section('content')

<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>
    @endif

    <div class="card shadow mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h3>Task Management</h3>

                <p class="text-muted mb-0">
                    Manage all project tasks
                </p>

            </div>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                + Add Task
            </a>
            @endif

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Total Tasks</h5>

                    <h2>{{ $tasks->total() }}</h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>To Do</h5>

                    <h2>

                        {{ \App\Models\Task::where('status','To Do')->count() }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Completed</h5>

                    <h2>

                        {{ \App\Models\Task::where('status','Completed')->count() }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>High Priority</h5>

                    <h2>

                        {{ \App\Models\Task::where('priority','High')->count() }}

                    </h2>

                </div>

            </div>

        </div>

    </div>

    <form method="GET" class="row mb-4">

        <div class="col-md-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search Task">

        </div>

        <div class="col-md-2">

            <select
                name="status"
                class="form-select">

                <option value="">All Status</option>
                <option value="To Do" {{ request('status') == 'To Do' ? 'selected' : '' }}>To Do</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Doing" {{ request('status') == 'Doing' ? 'selected' : '' }}>Doing</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>

            </select>

        </div>

        <div class="col-md-2">

            <select
                name="priority"
                class="form-select">

                <option value="">All Priority</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>

            </select>

        </div>

        @if(auth()->user()->role === 'admin')
        <div class="col-md-3">

            <select
                name="employee_id"
                class="form-select">

                <option value="">All Employees</option>
                <option value="unassigned" {{ request('employee_id') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->first_name }} {{ $emp->last_name }}
                    </option>
                @endforeach

            </select>

        </div>
        @endif

        <div class="col-md-2">

            <button class="btn btn-primary w-100">

                Filter

            </button>

        </div>

    </form>

    <a
    href="{{ route('tasks.index') }}"
    class="btn btn-secondary mt-2">

    Reset Filters

    </a>

    <table class="table table-hover align-middle">

        <thead class="table-dark">

        <tr>

            <th>ID</th>
            <th>Title</th>
            <th>Project</th>
            <th>Employee</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Action</th>

        </tr>

        </thead>

        <tbody>

        @forelse($tasks as $task)

        <tr>

            <td>{{ $task->id }}</td>

            <td><strong>{{ $task->title }}</strong></td>

            <td>{{ $task->project->project_name }}</td>

            <td>
                @if($task->employee)
                    {{ $task->employee->first_name }}
                    {{ $task->employee->last_name }}
                @else
                    <span class="text-muted">Unassigned</span>
                @endif
            </td>

            <td>
                <span class="badge bg-{{
                    $task->priority=='High'
                    ?'danger'
                    :($task->priority=='Medium'
                    ?'warning'
                    :'secondary')
                }}">

                    {{ $task->priority }}

                </span>
            </td>

            <td>
                <span class="badge bg-{{
                    $task->status=='Completed'
                    ?'success'
                    :($task->status=='To Do'
                    ?'secondary'
                    :($task->status=='Doing'
                    ?'info'
                    :'primary'))
                }}">

                    {{ $task->status }}

                </span>
            </td>

            <td>

                @if(\Carbon\Carbon::parse($task->due_date)->isPast()
                && $task->status!='Completed')

                <span class="badge bg-danger">

                    {{ $task->due_date }}

                </span>

                @else

                {{ $task->due_date }}

                @endif

            </td>

            <td>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('tasks.show',$task) }}" class="btn btn-info btn-sm">View</a>

                <a href="{{ route('tasks.edit',$task) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete Task?')">Delete</button>
                </form>

                @elseif(auth()->user()->role === 'employee')
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('tasks.show',$task) }}" class="btn btn-info btn-sm">View</a>
                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto;">
                            <option value="To Do" {{ $task->status === 'To Do' ? 'selected' : '' }}>To Do</option>
                            <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Doing" {{ $task->status === 'Doing' ? 'selected' : '' }}>Doing</option>
                            <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </form>
                </div>

                @else
                <span class="badge bg-secondary">{{ $task->status }}</span>
                @endif

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="8" class="text-center">

                No Tasks Found.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

    {{ $tasks->links() }}

</div>

@endsection

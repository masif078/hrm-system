@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row g-3">
        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Employees</h6>
                    <h2>{{ $employees }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Departments</h6>
                    <h2>{{ $departments }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Clients</h6>
                    <h2>{{ $clients }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Projects</h6>
                    <h2>{{ $projects }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Tasks</h6>
                    <h2>{{ $tasks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Leaves</h6>
                    <h2>{{ $leaves }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow">
        <div class="card-header">Recent Projects</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Project</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentProjects as $project)
                        <tr>
                            <td>{{ $project->project_code }}</td>
                            <td>{{ $project->project_name }}</td>
                            <td>{{ $project->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4 shadow">
        <div class="card-header">Recent Employees</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEmployees as $employee)
                        <tr>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>{{ $employee->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

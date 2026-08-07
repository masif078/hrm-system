@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Reports Dashboard</h2>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>Employee Report</h5>
                    <a href="{{ route('reports.employees') }}" class="btn btn-primary">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>Attendance Report</h5>
                    <a href="{{ route('reports.attendance') }}" class="btn btn-success">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>Leave Report</h5>
                    <a href="{{ route('reports.leaves') }}" class="btn btn-warning">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>Project Report</h5>
                    <a href="{{ route('reports.projects') }}" class="btn btn-dark">Open</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

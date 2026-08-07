@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h4 class="mb-0">Employee Report</h4>

<div>
<a href="{{ route('reports.employees.pdf') }}" class="btn btn-danger mb-0">Download PDF</a>
<a href="{{ route('reports.employees.excel') }}" class="btn btn-success mb-0 ms-2">Download Excel</a>
</div>

</div>

<div class="card-body">

<form method="GET" class="row mb-4">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Employee"
value="{{ request('search') }}">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

Search

</button>

</div>

</form>
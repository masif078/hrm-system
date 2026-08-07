@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Add Client</h4>

</div>

<div class="card-body">

@if($errors->any())

<div class="alert alert-danger">

<ul>

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form action="{{ route('clients.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Company Name</label>

<input type="text" name="company_name" class="form-control" required>

</div>

<div class="mb-3">

<label>Contact Person</label>

<input type="text" name="contact_person" class="form-control" required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email" name="email" class="form-control" required>

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text" name="phone" class="form-control" required>

</div>

<div class="mb-3">
<label>Address</label>
<textarea name="address" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Link User Account</label>
<select name="user_id" class="form-select">
    <option value="">-- Select User (optional) --</option>
    @foreach($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
    @endforeach
</select>
</div>

<button class="btn btn-success">

Save Client

</button>

<a href="{{ route('clients.index') }}"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

@endsection

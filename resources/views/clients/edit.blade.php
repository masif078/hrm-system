@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Edit Client</h4>

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

<form action="{{ route('clients.update',$client) }}" method="POST">

@csrf

@method('PUT')

<div class="mb-3">

<label>Company Name</label>

<input type="text" name="company_name" class="form-control" value="{{ old('company_name',$client->company_name) }}" required>

</div>

<div class="mb-3">

<label>Contact Person</label>

<input type="text" name="contact_person" class="form-control" value="{{ old('contact_person',$client->contact_person) }}" required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email" name="email" class="form-control" value="{{ old('email',$client->email) }}" required>

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text" name="phone" class="form-control" value="{{ old('phone',$client->phone) }}" required>

</div>

<div class="mb-3">
<label>Address</label>
<textarea name="address" class="form-control">{{ old('address',$client->address) }}</textarea>
</div>

<div class="mb-3">
<label>Link User Account</label>
<select name="user_id" class="form-select">
    <option value="">-- Select User (optional) --</option>
    @foreach($users as $user)
        <option value="{{ $user->id }}" {{ $client->user_id == $user->id ? 'selected' : '' }}>
            {{ $user->name }} ({{ $user->email }})
        </option>
    @endforeach
</select>
</div>

<button class="btn btn-success">

Update Client

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

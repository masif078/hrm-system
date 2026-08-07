@extends('layouts.app')

@section('content')

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h3>Clients</h3>

                <p class="text-muted mb-0">
                    Manage Company Clients
                </p>

            </div>

            <a href="{{ route('clients.create') }}"
               class="btn btn-primary">

                + Add Client

            </a>

        </div>

    </div>

    <table class="table table-hover align-middle">

        <thead class="table-dark">

        <tr>

            <th>ID</th>
            <th>Company</th>
            <th>Contact Person</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>

        </tr>

        </thead>

        <tbody>

        @forelse($clients as $client)

        <tr>

            <td>{{ $client->id }}</td>

            <td>
                <strong>{{ $client->company_name }}</strong>
            </td>

            <td>{{ $client->contact_person }}</td>

            <td>{{ $client->email }}</td>

            <td>{{ $client->phone }}</td>

            <td>

                <a href="{{ route('clients.edit',$client) }}"
                   class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form
                    action="{{ route('clients.destroy',$client) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete Client?')">

                        Delete

                    </button>

                </form>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="6" class="text-center">

                No Clients Found.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

    {{ $clients->links() }}

</div>

@endsection

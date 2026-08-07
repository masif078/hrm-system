<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $users = \App\Models\User::where('role', 'client')->get();
        return view('clients.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name'   => 'required|max:255',
            'contact_person' => 'required|max:255',
            'email'          => 'required|email|unique:clients,email',
            'phone'          => 'required|max:20',
            'address'        => 'nullable|max:255',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function edit(Client $client)
    {
        $users = \App\Models\User::where('role', 'client')->get();
        return view('clients.edit', compact('client', 'users'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'company_name'   => 'required|max:255',
            'contact_person' => 'required|max:255',
            'email'          => 'required|email|unique:clients,email,' . $client->id,
            'phone'          => 'required|max:20',
            'address'        => 'nullable|max:255',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}

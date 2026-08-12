<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        $users = \App\Models\User::where('role', 'client')->get();

        return view('clients.index', compact('clients', 'users'));
    }

    public function create()
    {
        $users = \App\Models\User::where('role', 'client')->get();
        return view('clients.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'company_name'   => 'required|max:255',
            'contact_person' => 'required|max:255',
            'email'          => 'required|email|unique:clients,email',
            'phone'          => 'required|max:20',
            'address'        => 'nullable|max:255',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $client = Client::create($validator->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Client created successfully.',
                'client'  => $client
            ]);
        }

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

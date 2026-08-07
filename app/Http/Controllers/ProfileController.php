<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load(['employee', 'client']);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Basic User fields validation
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];

        // 2. Add validation based on role
        if ($user->role === 'employee' && $user->employee) {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        } elseif ($user->role === 'client' && $user->client) {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['contact_person'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules);

        // 3. Handle Avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 4. Update User fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 5. Update role-specific fields
        if ($user->role === 'employee' && $user->employee) {
            $employee = $user->employee;
            $employee->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'], // Synchronize email
            ]);

            // Sync user name
            $user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            ]);
        } elseif ($user->role === 'client' && $user->client) {
            $client = $user->client;
            $client->update([
                'company_name' => $validated['company_name'],
                'contact_person' => $validated['contact_person'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'], // Synchronize email
            ]);

            // Sync user name
            $user->update([
                'name' => $validated['contact_person'],
            ]);
        }

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}

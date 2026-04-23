<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',

            'backup_email' => 'required|email|max:191',
            'phone_number' => 'required|string|max:255',
            'cell_phone_number' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'hire_date' => 'nullable|date',
            'facebook_profile' => 'nullable|string|max:255',
            'instagram_account' => 'nullable|string|max:255',
            'twitter_account' => 'nullable|string|max:255',
            'time_zone' => 'nullable|string|max:255',
            'email_as_username' => 'nullable|integer',
            'ssn' => 'required|string|max:255',
            'ein' => 'nullable|string|max:255',
            'first_address_line' => 'required|string|max:255',
            'second_address_line' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'profile_photo' => 'nullable|string',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($user->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        $data['last_modified_by'] = auth()->id();
        $data['updated_at'] = now();

        $user->update($data);

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
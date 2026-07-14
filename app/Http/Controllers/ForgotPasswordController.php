<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->where('isDeleted', 0)->where('is_disabled', '!=', 1)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email Address Not Found. (Error Code: 107)'
            ]);
        }

        $forgot = UserForgotPassword::create([
            'user_id' => $user->id,
            'requested_on' => now()->timestamp,
            'status' => 'P',
        ]);

        $token = base64_encode(
            $forgot->id . config('app.invitation_salt')
        );

        Mail::to($user->email)->send(
            new ForgotPasswordMail($user, $token)
        );

        return back()->with('status', 'Password reset email sent.');
    }

    public function showResetForm($token)
    {
        $decoded = base64_decode($token);

        $id = str_replace(config('app.invitation_salt'),'',$decoded);

        if (!is_numeric($id)) {
            abort(403, 'This link is invalid or has expired.');
        }

        $forgot = UserForgotPassword::find($id);

        if (!$forgot) {
            abort(403, 'This link is invalid or has expired.');
        }

        if ($forgot->status !== 'P') {
            abort(403, 'This link is invalid or has expired.');
        }

        if (now()->timestamp > ($forgot->requested_on + 600)) {

            $forgot->update([
                'status' => 'C'
            ]);

            abort(403, 'This link is invalid or has expired.');
        }

        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d).*$/'
            ],
            'token' => 'required'
        ]);

        $decoded = base64_decode($request->token);

        $id = str_replace(config('app.invitation_salt'), '', $decoded);

        $forgot = UserForgotPassword::find($id);

        if (
            !$forgot ||
            $forgot->status !== 'P' ||
            now()->timestamp > ($forgot->requested_on + 600)
        ) {
            abort(403, 'This link is invalid or has expired.');
        }

        $user = User::findOrFail($forgot->user_id);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $forgot->update([
            'status' => 'C',
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Password changed successfully.');
    }
}

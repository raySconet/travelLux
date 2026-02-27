<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemUsersController extends Controller
{
    public function index()
    {
       $users = User::select('id','fname', 'lname' ,'email', 'role', 'is_disabled', 'phone_number', 'city', 'state')
                      ->where('isDeleted', 0)
                      ->get();
       return view('systemUsers', compact('users'));
    }

    public function edit(User $user)
    {
        $isNewUser = false;
        return view('system-users.edit' , compact('user', 'isNewUser'));
    }

    public function create()
    {
        $user = new User(); 
        $isNewUser = true;
        return view('system-users.edit', compact('user', 'isNewUser'));
    }

    public function store(Request $request)
    {
        $messages = [
            'fname.required' => 'First Name Is required.',
            'lname.required' => 'Last Name Is required.',
            'email.required' => 'Email Is required.',
            'email.email' => 'Email Address Not Valid.',
            'username.required' => 'Username Is Required.',
            'password.required' => 'Password Is required.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required' => 'Role Is Required.',
            'commission.required' => 'Commission Is Required.'
        ];

        $data = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:191|unique:users,email',
            'username' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|integer',
            'commission' => 'required|integer',

            'backup_email' => 'nullable|email|max:191',
            'email_as_username' => 'nullable|integer',
            'phone_number' => 'nullable|string|max:255',
            'cell_phone_number' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'hire_date' => 'nullable|date',
            'facebook_profile' => 'nullable|string|max:255',
            'instagram_account' => 'nullable|string|max:255',
            'twitter_account' => 'nullable|string|max:255',
            'time_zone' => 'nullable|string|max:255',
            'ssn' => 'nullable|string|max:255',
            'ein' => 'nullable|string|max:255',
            'alternate_commission' => 'nullable|integer',
            'agent_title' => 'nullable|string|max:255',
            'automated_emails_page_access' => 'nullable|integer',
            'include_in_alias_reports' => 'nullable|integer',
            'alias' => 'nullable|string|max:255',
            'first_address_line' => 'nullable|string|max:255',
            'second_address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'mentor_ids' => 'nullable|string',
            'reservation_email_enabled' => 'nullable|integer',
            'reservation_agent_owns_email' => 'nullable|integer',
            'reservation_cc_email_address' => 'nullable|string|max:255',
            'reservation_bcc_email_address' => 'nullable|string|max:255',
            'task_enable_daily_tasks_email' => 'nullable|integer',
            'task_seperate_email_per_task' => 'nullable|integer',
            'task_send_email_at' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string',
            'profile_photo' => 'nullable|string',
            'profile_submit_flag' => 'nullable|integer',
            'is_disabled' => 'nullable|integer',
            'isDeleted' => 'nullable|integer',
        ], $messages);

        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = auth()->id();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        User::create($data);

        return redirect()
            ->route('system-users')
            ->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        // dd($request->all());
        $data = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|integer',
            'commission' => 'required|integer',

            'backup_email' => 'nullable|email|max:191',
            'email_as_username' => 'nullable|integer',
            'phone_number' => 'nullable|string|max:255',
            'cell_phone_number' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'hire_date' => 'nullable|date',
            'facebook_profile' => 'nullable|string|max:255',
            'instagram_account' => 'nullable|string|max:255',
            'twitter_account' => 'nullable|string|max:255',
            'time_zone' => 'nullable|string|max:255',
            'ssn' => 'nullable|string|max:255',
            'ein' => 'nullable|string|max:255',
            'alternate_commission' => 'nullable|integer',
            'agent_title' => 'nullable|string|max:255',
            'automated_emails_page_access' => 'nullable|integer',
            'include_in_alias_reports' => 'nullable|integer',
            'alias' => 'nullable|string|max:255',
            'first_address_line' => 'nullable|string|max:255',
            'second_address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'mentor_ids' => 'nullable|string',
            'reservation_email_enabled' => 'nullable|integer',
            'reservation_agent_owns_email' => 'nullable|integer',
            'reservation_cc_email_address' => 'nullable|string|max:255',
            'reservation_bcc_email_address' => 'nullable|string|max:255',
            'task_enable_daily_tasks_email' => 'nullable|integer',
            'task_seperate_email_per_task' => 'nullable|integer',
            'task_send_email_at' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string',
            'profile_photo' => 'nullable|string',
            'profile_submit_flag' => 'nullable|integer',
            'is_disabled' => 'nullable|integer',
            'isDeleted' => 'nullable|integer',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['last_modified_by'] = auth()->id();
        $data['updated_at'] = now();

        $user->update($data);

        return redirect()
            ->route('system-users')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->update([
            'isDeleted' => 1,
            'last_modified_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('system-users')
            ->with('success', 'User deleted successfully');
    }
}

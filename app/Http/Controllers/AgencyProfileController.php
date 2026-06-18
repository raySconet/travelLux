<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgencyProfile;

class AgencyProfileController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
       $this->checkAdmin();
       $agencyProfile = AgencyProfile::select('company_name', 'contact_person_name', 'agency_tag_line', 'new_customer_invite_email_subject', 'cell_phone_number', 'home_phone_number')
                                        ->where('is_deleted',0)
                                        ->first();


       return view('agencyProfile', compact('agencyProfile'));
    }

    public function update(Request $request)
    {
        $this->checkAdmin();
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'agency_tag_line' => 'nullable|string|max:255',
            'new_customer_invite_email_subject' => 'nullable|string|max:255',
            'cell_phone_number' => 'nullable|string|max:20',
            'home_phone_number' => 'nullable|string|max:20',
        ]);

        $agencyProfile = AgencyProfile::where('is_deleted', 0)->firstOrFail();


        $agencyProfile->update($data);

        return redirect()
                ->route('agencyProfile')
                ->with('success', 'Agency profile updated successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::latest()->get();
        return view('insurance.index', compact('insurances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'insurance_name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        Insurance::create($request->all());

        return response()->json(['success' => true, 'message' => 'Insurance added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->update($request->all());
        return response()->json(['success' => true, 'message' => 'Insurance updated successfully.']);
    }

    public function destroy($id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->delete();
        return response()->json(['success' => true, 'message' => 'Insurance deleted successfully.']);
    }
}

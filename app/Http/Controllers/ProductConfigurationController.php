<?php

namespace App\Http\Controllers;

use App\Models\ProductConfiguration;
use Illuminate\Http\Request;

class ProductConfigurationController extends Controller
{
    public function index()
    {
        // $productConfiguation = productConfiguation::latest()->get();
        // return view('productConfiguation.index', compact('productConfiguation'));
        return view('productConfiguration');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'insurance_name' => 'required|string|max:255|unique:insurances,insurance_name',
                'email' => 'nullable|email',
            ]);

            Insurance::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Insurance added successfully.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'This insurance name already exists.',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $insurance = Insurance::findOrFail($id);

            $request->validate([
                'insurance_name' => 'required|string|max:255|unique:insurances,insurance_name,' . $id,
                'email' => 'nullable|email',
            ]);

            $insurance->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Insurance updated successfully.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'This insurance name already exists.',
                'errors' => $e->errors()
            ], 422);
        }
    }


    public function destroy($id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->delete();
        return response()->json(['success' => true, 'message' => 'Insurance deleted successfully.']);
    }

    public function fetch($id)
    {
        $insurance = Insurance::where('insurance_name', $id)->first();

        if(!$insurance) {
            return response()->json(['error' => 'Insurance not found'], 404);
        }

        return response()->json($insurance);
    }
}

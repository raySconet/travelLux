<?php

namespace App\Http\Controllers;

use App\Models\CruiseItinerary;
use App\Models\ResortShip;
use Illuminate\Http\Request;

class CruiseItinerariesController extends Controller
{
    public function index()
    {
        // $productConfiguation = productConfiguation::latest()->get();
        // return view('productConfiguation.index', compact('productConfiguation'));
        // $products = Product::where('is_deleted', 0)
        //                     ->orderByRaw('display_order = 0, display_order ASC')
        //                     ->get();
        // return view('productConfiguration', compact('products'));
    }

    public function cruiseItineraries(ResortShip $resortShip)
    {
        return response()->json(
            $resortShip->cruiseItineraries()
                ->orderBy('cruise_name')
                ->get()
        );
    }
    public function store(Request $request, ResortShip $resortShip)
    {
         $userId = auth()->id();
         $validated = $request->validate([
            'nameOtherModal' => 'required|string|max:255|unique:cruise_itineraries,cruise_name',
        ]);

        $cruise = $resortShip->cruiseItineraries()->create([
            'cruise_name' => $validated['nameOtherModal'],
            'created_by' => $userId,
            'created_on' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'cruise added successfully.',
            'resortid' => $resortShip->id,
            'type' => 'resortShip',
        ]);
    }

    public function show(CruiseItinerary $cruise)
    {
        return response()->json($cruise);
    }


    public function update(Request $request, CruiseItinerary $cruise)
    {
        try {

            $validated = $request->validate(
                [
                    'nameOtherModal' => 'required|string|max:255|unique:cruise_itineraries,cruise_name,' . $cruise->id,
                ],
                [
                    'nameOtherModal.required' => 'Cruise name is required.',
                    'nameOtherModal.unique'   => 'This cruise already exists.',
                ]
            );

            $cruise->update([
                'cruise_name' => $validated['nameOtherModal'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cruise updated successfully.',
                'product_id' => $cruise->product_id,
                'type' => 'product',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Destination;
use App\Models\ResortShip;
class ResortShipsController extends Controller
{
    public function index()
    {
        // $productConfiguation = productConfiguation::latest()->get();
        // return view('productConfiguation.index', compact('productConfiguation'));
        // $products = ResortsShips::where('is_deleted', 0)
        //                     ->orderByRaw('display_order = 0, display_order ASC')
        //                     ->get();
        // return view('productConfiguration', compact('products'));
    }
    public function resortShips(Destination $destination)
    {
        return response()->json(
            $destination->resortShips()
                ->orderBy('resort_ship_name')
                ->get()
        );
    }

    public function store(Request $request, Destination $destination)
    {
        $userId = auth()->id();
        $validated = $request->validate([
            'nameOtherModal' => 'required|string|max:255|unique:resort_ships,resort_ship_name',
        ]);

        $resort = $destination->resortShips()->create([
            'resort_ship_name' => $validated['nameOtherModal'],
            'created_on' => now(),
            'created_by' => $userId,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Resort added successfully.',
            'destination_id' => $destination->id,
            'type' => 'destination',

        ]);
    }
    public function show(ResortShip $resort)
    {
        return response()->json($resort);
    }


    public function update(Request $request, ResortShip $resort)
    {
        try {

            $validated = $request->validate(
                [
                    'nameOtherModal' => 'required|string|max:255|unique:resort_ships,resort_ship_name,' . $resort->id,
                ],
                [
                    'nameOtherModal.required' => 'Resort name is required.',
                    'nameOtherModal.unique'   => 'This resort already exists.',
                ]
            );

            $resort->update([
                'resort_ship_name' => $validated['nameOtherModal'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resort updated successfully.',
                'product_id' => $resort->product_id,
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

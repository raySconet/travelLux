<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Product;
use Illuminate\Http\Request;

class DestinationsController extends Controller
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
    public function destinations(Product $product)
    {
        return response()->json(
            $product->destinations()
                ->orderBy('destination_name')
                ->get()
        );
    }

    public function store(Request $request, Product $product)
    {
         $userId = auth()->id();
         $validated = $request->validate([
            'nameOtherModal' => 'required|string|max:255|unique:destinations,destination_name',
        ]);

        $destination = $product->destinations()->create([
            'destination_name' => $validated['nameOtherModal'],
            'created_by' => $userId,
            'created_on' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Destination added successfully.',
            'product_id' => $product->id,
            'type' => 'product',
        ]);
    }

    public function show(Destination $destination)
    {
        return response()->json($destination);
    }


    public function update(Request $request, Destination $destination)
    {
        try {

            $validated = $request->validate(
                [
                    'nameOtherModal' => 'required|string|max:255|unique:destinations,destination_name,' . $destination->id,
                ],
                [
                    'nameOtherModal.required' => 'Destination name is required.',
                    'nameOtherModal.unique'   => 'This destination already exists.',
                ]
            );

            $destination->update([
                'destination_name' => $validated['nameOtherModal'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Destination updated successfully.',
                'product_id' => $destination->product_id,
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

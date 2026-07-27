<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductConfigurationController extends Controller
{
    public function index()
    {
        // $productConfiguation = productConfiguation::latest()->get();
        // return view('productConfiguation.index', compact('productConfiguation'));
        $products = Product::where('is_deleted', 0)
                            ->orderByRaw('display_order = 0, display_order ASC')
                            ->get();
        return view('productConfiguration', compact('products'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'product_name'        => 'required|string|max:255|unique:products,product_name',
                'display_order'       => 'required|nullable|integer',
                'product_type'        => 'nullable|string|max:255',
                'currency'            => 'required|nullable|max:50',
                'tax'                 => 'required|nullable|numeric',
                'vendorBDM'           => 'nullable|string|max:255',
                'bdm_phone_number'    => 'nullable|string|max:50',
                'bdm_email'           => 'nullable|email|max:255',
                'phone_number'        => 'nullable|string|max:50',
                'first_address_line'  => 'nullable|string|max:255',
                'second_address_line' => 'nullable|string|max:255',
                'city'                => 'nullable|string|max:100',
                'state'               => 'nullable|string|max:100',
                'postal_code'         => 'nullable|string|max:50',
                'country'             => 'required|nullable|string|max:100',
                'notes'               => 'nullable|string',
            ],
            [
                'product_name.required' => 'Product name is required.',
                'display_order.required' => 'Display order is required.',
                'currency.required' => 'Currency is required.',
                'country.required' => 'Country is required.',
                'tax.required' => 'Tax is required.',
                'product_name.string'   => 'Product name must be text.',
                'bdm_email.email'       => 'Please enter a valid email address.',
            ]);
            $userId = auth()->id();
            Product::create(array_merge(
                $request->all(),
                [
                    'created_on' => now(),
                    'created_by' => $userId,
                ]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully.'
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);

        }
    }

    public function update(Request $request, $id)
    {
        try {

            $product = Product::findOrFail($id);

            $request->validate([
                'product_name'        => 'required|string|max:255|unique:products,product_name,' . $id,
                'display_order'       => 'nullable|integer',
                'product_type'        => 'nullable|string|max:255',
                'currency'            => 'nullable|string|max:50',
                'tax'                 => 'nullable|numeric',
                'vendorBDM'           => 'nullable|string|max:255',
                'bdm_phone_number'    => 'nullable|string|max:50',
                'bdm_email'           => 'nullable|email|max:255',
                'phone_number'        => 'nullable|string|max:50',
                'first_address_line'  => 'nullable|string|max:255',
                'second_address_line' => 'nullable|string|max:255',
                'city'                => 'nullable|string|max:100',
                'state'               => 'nullable|string|max:100',
                'postal_code'         => 'nullable|string|max:50',
                'country'             => 'nullable|string|max:100',
                'notes'               => 'nullable|string',
            ]);

            $product->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.'
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'This product name already exists.',
                'errors' => $e->errors()
            ], 422);

        }
    }

    public function show(Product $product)
    {
        return response()->json($product);
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

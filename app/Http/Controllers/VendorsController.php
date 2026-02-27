<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class VendorsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $productsQuery = Product::orderBy('product_name')
                                ->where('is_deleted', 0);

        if ($search) {
            $productsQuery->where('product_name', 'like', "%{$search}%")
                          ->orwhere('phone_number', 'like', "%{$search}%")
                          ->orwhere('vendor_bdm', 'like', "%{$search}%")
                          ->orwhere('bdm_phone_number', 'like', "%{$search}%")
                          ->orwhere('bdm_email', 'like', "%{$search}%")
                          ->orwhere('notes', 'like', "%{$search}%");
        }

        $products = $productsQuery->get();

        return view('vendors.vendorList', compact('products'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'vendor_bdm' => 'nullable|string|max:255',
            'bdm_phone_number' => 'nullable|string|max:255',
            'bdm_email' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $product->update($data);

        return redirect()
            ->route('vendors.vendorList', $product->id)
            ->with('success', 'Vendor updated successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Categorie::getActiveCategories();
        return response()->json($categories);
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            ]);

            $category = Categorie::create([
                'categoryName' => $request->name,
                'color' => $request->color ?? null,
                'isDeleted' => false,
            ]);

            return response()->json([
                'message' => 'Category created successfully!',
                'category' => $category,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

?>

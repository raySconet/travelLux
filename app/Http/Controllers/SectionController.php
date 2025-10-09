<?php

namespace App\Http\Controllers;

use App\Models\todoSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SectionController extends Controller
{
    public function index(): JsonResponse
    {
        // $categories = Categorie::getActiveCategories();
        // return response()->json($categories);
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'todoSectionTitle' => 'nullable|string|max:255',
                'sectionDescription' => 'nullable|string',
            ]);

            $section = TodoSection::create([
                'title' => $request->todoSectionTitle ?? null,
                'description' => $request->sectionDescription ?? null,
                'caseId' => '1',
            ]);

             return response()->json([
                    'message' => 'Event created successfully!',
                    'event' => $section,
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

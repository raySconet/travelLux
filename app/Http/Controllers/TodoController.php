<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TodoController extends Controller
{
    public function index($caseId)
    {
        $sections = TodoSection::with(['Categorie:id,color'])
                    ->where('caseId', $caseId)->get();

        return response()->json([
            'success' => true,
            'sections' => $sections
        ]);
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'todoSectionTitle' => 'nullable|string|max:255',
                'sectionDescription' => 'nullable|string',
                'todoSectionCategory' => 'nullable|integer',
            ]);

            $section = TodoSection::create([
                'title' => $request->todoSectionTitle ?? null,
                'description' => $request->sectionDescription ?? null,
                'categoryId' => $request->todoSectionCategory ?? null,
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

    //  public function show($id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'User not found.',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'user' => $user,
    //     ]);
    // }
}
?>

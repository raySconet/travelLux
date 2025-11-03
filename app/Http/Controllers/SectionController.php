<?php

namespace App\Http\Controllers;

use App\Models\TodoSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SectionController extends Controller
{
    public function index($caseId)
    {
        $sections = TodoSection::with(['Categorie:id,color',
                                        'todos' => function ($query) {
                                            $query->orderBy('id', 'asc');
                                        }
                                    ])
                    ->where('caseId', $caseId)
                    ->orderBy('id', 'asc')
                    ->get();

                     // Optionally separate today's + completed todos
        $todays = [];
        $completed = [];

        foreach ($sections as $section) {
            foreach ($section->todos as $todo) {
                if ($todo->toDoStatus === 'toBeDone') {
                    $todays[] = $todo;
                } elseif ($todo->toDoStatus === 'completed') {
                    $completed[] = $todo;
                }
            }
        }
        return response()->json([
            'success' => true,
            'sections' => $sections,
            'todays' => $todays,
            'completed' => $completed,
        ]);
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'todoSectionTitle' => 'nullable|string|max:255',
                'sectionDescription' => 'nullable|string',
                'todoSectionCategory' => 'required|integer|not_in:-1',
            ]);

            $section = TodoSection::create([
                'title' => $request->todoSectionTitle ?? null,
                'description' => $request->sectionDescription ?? null,
                'categoryId' => $request->todoSectionCategory ,
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

    public function show($id)
    {
        try {
            $section = TodoSection::with([
                'Categorie:id,color',
                'todos' => function ($query) {
                    $query->orderBy('id', 'asc');
                }
            ])->find($id);

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'section' => $section,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'todoSectionTitle' => 'nullable|string|max:255',
                'sectionDescription' => 'nullable|string',
                'todoSectionCategory' => 'required|integer|not_in:-1',
            ]);

            $section = TodoSection::find($id);

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found.',
                ], 404);
            }

            $section->update([
                'title' => $request->todoSectionTitle ?? NULL,
                'description' => $request->sectionDescription ?? NULL,
                'categoryId' => $request->todoSectionCategory,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully!',
                'section' => $section,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
?>

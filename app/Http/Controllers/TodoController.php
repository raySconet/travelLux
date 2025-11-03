<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TodoController extends Controller
{
    // public function index($caseId)
    // {
    //     $sections = Todo::with(['Categorie:id,color'])
    //                 ->where('caseId', $caseId)->get();

    //     return response()->json([
    //         'success' => true,
    //         'sections' => $sections
    //     ]);
    // }

    public function store(Request $request) {
        try {
            $request->validate([
                'todoTitle' => 'nullable|string|max:255',
                'todoDescription' => 'nullable|string',
                'todoDate' => '',
            ]);
            $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('todoDate'));

            $section = Todo::create([
                'title' => $request->todoTitle ?? null,
                'description' => $request->todoDescription ?? null,
                'completeDate' => $fromDateCarbon->format('Y-m-d') ?? null,
                'sectionId' => $request->sectionId,
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
    //     $Todo = Todo::find($id);

    //     if (!$Todo) {
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


    public function toggleComplete(Request $request)
    {
        $todo = Todo::findOrFail($request->id);

        // Toggle completed value (0 -> 1 or 1 -> 0)
        $statusFlow = [
            'pending' => 'toBeDone',
            'toBeDone' => 'completed',
            'completed' => 'completed',
        ];
        $todo->toDoStatus = $statusFlow[$todo->toDoStatus] ?? 'pending';
        if ($todo->toDoStatus === 'completed') {
            $todo->completeDate = now();
        }
        $todo->save();

        return response()->json([
            'success' => true,
            'completed' => $todo->toDoStatus,
        ]);
    }
}
?>

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

    public function destroy($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            $todo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todo deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete todo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus($id)
    {
        try {
            $todo = Todo::findOrFail($id);

            // Define the flow
            $statusFlow = [
                'toBeDone' => 'pending',
                'completed' => 'toBeDone',
            ];

            $todo->toDoStatus = $statusFlow[$todo->toDoStatus] ;

            $todo->save();

            return response()->json([
                'success' => true,
                'newStatus' => $todo->toDoStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        return response()->json($todo);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        $inputDate = $request->input('todoDate');

        // Only parse if date is provided
        if ($inputDate) {
            try {
                $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y', $inputDate);
                $formattedDate = $fromDateCarbon->format('Y-m-d');
            } catch (\Exception $e) {
                // fallback if the format is wrong or empty
                $formattedDate = null;
            }
        } else {
            $formattedDate = null;
        }

        $todo->update([
            'title' => $request->todoTitle,
            'description' => $request->todoDescription,
            'completeDate' => $formattedDate,
        ]);

        return response()->json(['success' => true, 'todo' => $todo]);
    }
}
?>

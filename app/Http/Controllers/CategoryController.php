<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\CourtCase;
use App\Models\Event;
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

    public function getEventsAndCases(Request $request)
    {
        $requestUserId = $request->input('user_id');
        $authUser = auth()->user();

        $targetUserId = $requestedUserId ?? $authUser->id;

        if($authUser->userPermission !== 'admin' && $authUser->id != $targetUserId) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $cases = CourtCase::where('isDeleted', 0)
                ->whereHas('userCases', function ($query) use ($targetUserId) {
                    $query->where('user_id', $targetUserId)
                            ->where('isDeleted', 0);
                })
                ->get()
                ->map(function ($case) {
                    return [
                        'id' => $case->id,
                        'type' => 'case',
                        'title' => $case->caseTitle,
                        'date' => $case->dateFrom,
                        'raw' => $case,
                    ];
                });

        $events = Event::where('isDeleted', 0)
                ->where('user_id', $targetUserId)
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'type' => 'event',
                        'title' => $event->title,
                        'date' => $event->date_from,
                        'raw' => $event,
                    ];
                });

        $combined = $cases->merge($events)->sortByDesc('date')->values();

        return response()->json([
            'data' => $combined
        ]);
    }
}

?>

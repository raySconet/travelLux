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

        $targetUserId = $requestUserId ?? $authUser->id;

        if($authUser->userPermission !== 'admin' && $authUser->id != $targetUserId) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $categories = Categorie::getActiveCategories();

        $cases = CourtCase::where('isDeleted', 0)
            ->whereHas('userCases', function ($query) use ($targetUserId) {
                $query->where('user_id', $targetUserId)
                    ->where('isDeleted', 0);
            })
            ->get();

        $events = Event::where('isDeleted', 0)
            ->where('user_id', $targetUserId)
            ->get();

        $result = $categories->map(function ($category) {
            return [
                'id' => (string)$category->id,
                'panelId' => 'panel' . $category->id,
                'label' => $category->categoryName,
                'colorClass' => $category->color ?? '#fff',
                'items' => [],
            ];
        });

        $categoryIndex = $result->keyBy('id')->toArray();

        foreach ($cases as $case) {
            $catId = (string)$case->categoryId;
            if(isset($categoryIndex[$catId])) {
                $categoryIndex[$catId]['items'][] = [
                    'tag' => strtoupper($case->type ?? 'case'),
                    'description' => $case->caseTitle,
                    'from' => $case->dateFrom,
                    'to' => $case->dateTo,
                ];
            }
        }

        foreach ($events as $event) {
            $catId = (string)$event->categoryId;
            if(isset($categoryIndex[$catId])) {
                $categoryIndex[$catId]['items'][] = [
                    'tag' => strtoupper($event->type ?? 'event'),
                    'description' => $event->title,
                    'from' => $event->date_from,
                    'to' => $event->date_to,
                ];
            }
        }

        return response()->json(collect($categoryIndex)->values());
    }
}
?>

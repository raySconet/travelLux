<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\CourtCase;
use App\Models\Event;
use App\Models\UserAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categoryId = $request->input('category_id');

        if($categoryId) {
            $categories = Categorie::where('isDeleted', 0)
                        ->where('id', $categoryId)
                        ->get();

            return response()->json($categories);
        }

        $categories = Categorie::getActiveCategories();
        return response()->json($categories);
    }

    public function store(Request $request) {
        try {
            $authUser = auth()->user();

            if (!$authUser->canAddCategory()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            $request->validate([
                'name' => ['required', 'string', 'max:255', 'regex:/^[^\d]+$/',
                    Rule::unique('categories', 'categoryName')->where(function ($query) {
                        return $query->where('isDeleted', 0);
                    }),
                ],
                'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/',
                    Rule::unique('categories', 'color')->where(function ($query) {
                        return $query->where('isDeleted', 0);
                    }),
                ],
            ], [
                'name.regex' => 'The name field must not contain numbers.',
                'name.unique' => 'The name has already been taken.',
                'color.unique' => 'The color has already been taken.',
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

    public function update(Request $request, Categorie $categorie) {
        try {
            $authUser = auth()->user();

            if (!$authUser->canEditCategory()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            $rules = [
                'nameEditCategory' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[^\d]+$/',
                ],
                'color' => [
                    'nullable',
                    'regex:/^#([A-Fa-f0-9]{6})$/',
                ],
            ];

            if (trim($request->name) !== trim($categorie->categoryName)) {
                $rules['nameEditCategory'][] = Rule::unique('categories', 'categoryName')->ignore($categorie->id);
            }

            if (trim($request->color) !== trim($categorie->color)) {
                $rules['color'][] = Rule::unique('categories', 'color')->ignore($categorie->id);
            }

            $validated = $request->validate($rules, [
                'nameEditCategory.regex' => 'The name field must not contain numbers.',
                'nameEditCategory.unique' => 'The name has already been taken.',
                'color.unique' => 'The color has already been taken.',
            ]);

            $category = $categorie->update([
                'categoryName' => $validated['nameEditCategory'],
                'color' => $validated['color'] ?? null,
            ]);

            return response()->json([
                'message' => 'Category edited successfully!',
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

    public function delete(Request $request)
    {
        $authUser = auth()->user();

        if (!$authUser->canDeleteCategory()) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $categoryId = $request->input('category_id');

        $categorie = Categorie::find($categoryId);

        if(!$categorie) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }

        if ($categorie->hasCasesOrEvents()) {
            return response()->json([
                'message' => 'Category cannot be deleted because it has associated items!',
            ], 409);
        }

        $category = $categorie->update(['isDeleted' => 1]);

        return response()->json([
            'message' => 'Category deleted successfully!',
            'category' => $category,
        ]);

    }

    public function getEventsAndCases(Request $request)
    {
        // $requestUserId = $request->input('user_id');
        $authUser = auth()->user();

        $assignedUserIds = UserAssignment::where('user_id', $authUser->id)
            ->where('isDeleted', false)
            ->pluck('assigned_id')
            ->toArray();

        // $targetUserId = $requestUserId ?? $authUser->id;

        // if($authUser->userPermission !== 'admin' && $authUser->id != $targetUserId) {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        $targetUserId = $request->input('user_id');
        if (!$targetUserId) {
            $targetUserId = [$authUser->id];
        } elseif (!is_array($targetUserId)) {
            $targetUserId = [(int)$targetUserId];
        } else {
            $targetUserId = array_map('intval', $targetUserId);
        }

        // if ($authUser->isAdmin() || $authUser->isRegularUser()) {
        //     $targetUserId = array_unique(array_merge($targetUserId, $assignedUserIds));
        // }

        if ($authUser->isRegularUser()) {
            $allowedIds = array_merge([$authUser->id], $assignedUserIds);
            if (count(array_intersect($targetUserId, $allowedIds)) !== count($targetUserId)) {
            // if (count($targetUserId) !== 1 || $targetUserId[0] !== $authUser->id) {
                $categories = Categorie::getActiveCategories();
                $result = $categories->map(function ($category) {
                    return [
                        'id' => (string)$category->id,
                        'panelId' => 'panel' . $category->id,
                        'label' => $category->categoryName,
                        'colorClass' => $category->color ?? '#fff',
                        'items' => [],
                    ];
                });

                // return response()->json($result);
                return response()->json([
                    'categories' => $result,
                    'permissions' => [
                        'can_edit' => $authUser->canEditCategory(),
                        'can_delete' => $authUser->canDeleteCategory(),
                    ],
                ]);
            }

            $targetUserId = array_unique(array_merge($targetUserId, $assignedUserIds));
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
                    'description' => [
                        'atty_initials' => $case->atty_initials,
                        'stage_of_process' => $case->stage_of_process,
                        'client_name' => $case->client_name,
                    ],
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
                    'description' => [
                        'atty_initials' => $event->atty_initials,
                        'stage_of_process' => $event->stage_of_process,
                        'client_name' => $event->client_name,
                    ],
                    'from' => $event->date_from,
                    'to' => $event->date_to,
                ];
            }
        }

        // return response()->json(collect($categoryIndex)->values());
        return response()->json([
            'categories' => collect($categoryIndex)->values(),
            'permissions' => [
                'can_edit' => $authUser->canEditCategory(),
                'can_delete' => $authUser->canDeleteCategory(),
            ],
        ]);
    }
}
?>

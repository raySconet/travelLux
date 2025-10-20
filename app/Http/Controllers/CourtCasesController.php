<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\CourtCase;
use App\Models\User;
use App\Models\UserCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourtCasesController extends Controller
{
    public function index(Request $request)
    {
        $caseId = $request->input('event_case_id');
        $currentUser = auth()->user();

        if ($request->has('user_id')) {
            $requestedUserIds = $request->input('user_id');

            // Normalize to array
            if (!is_array($requestedUserIds)) {
                $requestedUserIds = [(int) $requestedUserIds];
            } else {
                $requestedUserIds = array_map('intval', $requestedUserIds);
            }

            if ($currentUser->isRegularUser()) {
                if (!in_array($currentUser->id, $requestedUserIds)) {
                    return response()->json([]);
                }
                $userIds = [$currentUser->id];
            } else {
                $userIds = $requestedUserIds;
            }
        } else {
            $userIds = [$currentUser->id];
        }

        if ($caseId) {
            $userCases = UserCase::with([
                'user:id,name',
                'case.categorie:id,categoryName,color'
            ])
            ->where('case_id', $caseId)
            ->where('isdeleted', 0)
            ->get();

            if ($userCases->isEmpty()) {
                return response()->json(['error' => 'Case not found or has no users assigned'], 404);
            }

            $case = $userCases->first()->case;

            // $assignedUserIds = $userCases->pluck('user_id')->toArray();

            // if (!in_array($currentUser->id, $assignedUserIds) && $currentUser->userPermission !== 'admin') {
            //     return response()->json(['error' => 'Unauthorized access'], 403);
            // }

            if (!$currentUser->canEditCase($case)) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $transformed = [
                'id' => $case->id,
                'atty_initials' => $case->atty_initials ?? '',
                'stage_of_process' => $case->stage_of_process ?? '',
                'client_name' => $case->client_name ?? '',
                'categoryId' => $case->categoryId,
                'date_from' => $case->dateFrom,
                'date_to' => $case->dateTo,
                'categorie' => $case->categorie ? [
                    'id' => $case->categorie->id,
                    'categoryName' => $case->categorie->categoryName,
                    'color' => $case->categorie->color,
                ] : null,
                'users' => $userCases->map(function ($userCase) {
                    return [
                        'id' => $userCase->user->id,
                        'name' => $userCase->user->name,
                    ];
                })->values(),
            ];

            $users = User::getActiveUsers(); // You already have this
            $categories = Categorie::getActiveCategories(); // You already have this

            return response()->json([
                'eventCase' => $transformed,
                'users' => $users,
                'categories' => $categories,
                'auth_user_id' => $currentUser->id,
            ]);

            // return response()->json($transformed);
        }

        // $userIds = $request->input('user_id');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $viewMode = $request->input('view_mode');

        // if (!$userIds) {
        //     $userIds = [auth()->id()];
        // } elseif (!is_array($userIds)) {
        //     $userIds = [(int) $userIds];
        // }

        try {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Fetch from user_cases table with related case and user
        $userCount = count($userIds);
        if ($viewMode === 'Month View' || ($viewMode === 'Day View' && $userCount !== 2) || ($viewMode === 'Week View' && $userCount !== 2)) {
            $userCases = UserCase::with([
                'user:id,name',
                'case.categorie:id,categoryName,color'
            ])
                ->whereIn('user_id', $userIds)
                ->whereHas('case', function ($query) use ($startDate, $endDate) {
                    $query->where('isDeleted', 0)
                        ->whereBetween('dateFrom', [$startDate, $endDate]);
                })
                ->distinct()
                ->get();
        } else {
            $userCases = UserCase::with([
                'user:id,name',
                'case.categorie:id,categoryName,color'
            ])
                ->whereIn('user_id', $userIds)
                ->whereHas('case', function ($query) use ($startDate, $endDate) {
                    $query->where('isDeleted', 0)
                        ->whereBetween('dateFrom', [$startDate, $endDate]);
                })
                // ->where('isdeleted', 0)
                ->get();
        }

        $userCases->transform(function ($event) use ($currentUser) {
            if (!$event->case) {
                $event->editable = false;
                return $event;
            }

            // $assignedUserIds = $event->case->users->pluck('id')->toArray();
            // $isAdmin = $currentUser->userPermission === 'admin';
            // $isAssigned = in_array($currentUser->id, $assignedUserIds);
            // $isCreator = $event->case->created_by === $currentUser->id;
            // $event->editable = $isAdmin || $isAssigned || $isCreator;
            $event->editable = $currentUser->canEditCase($event->case);

            return $event;
        });

        // Users who had at least one case
        $userIdsWithCases = $userCases->pluck('user_id')->unique()->toArray();

        // Users with no cases
        $missingUserIds = array_diff($userIds, $userIdsWithCases);

        $missingUsers = User::whereIn('id', $missingUserIds)->select('id', 'name')->get();

        // Build dummy events for users with no cases
        $dummyEvents = $missingUsers->map(function ($user) {
            return (object)[
                'id' => null,
                'user_id' => $user->id,
                'case_id' => null,
                'case' => null,
                'user' => (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ];
        });

        // Merge both
        $all = $userCases->concat($dummyEvents);

        return response()->json($all);
    }

    public function store(Request $request) {
        $currentUser = auth()->user();

        if (!$currentUser->canCreateCase()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $fromDateRaw = $request->input('fromDate');
        $toDateRaw = $request->input('toDate');

        // Merge corrected input back into request
        $request->merge([
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
        ]);

        // Validate input dates in correct format
        $request->validate([
            'atty_initials' => 'required|string|max:255',
            'type' => 'required|in:case',
            'fromDate' => ['required', 'date_format:m-d-Y'],
            'toDate' => ['required', 'date_format:m-d-Y', 'after_or_equal:fromDate'],
            'category' => ['required', 'not_in:-1', 'exists:categories,id'],
            // 'user' => ['required', 'array', 'min:1'],
            'user.*' => ['required', 'exists:users,id'],
        ], [
            'category.not_in' => 'The category field is required.',
        ]);

        // Convert to Carbon date for saving
        $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('fromDate'));
        $toDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('toDate'));

        // create case
        $case = CourtCase::create([
            'atty_initials' => $request['atty_initials'],
            'stage_of_process' => $request['stage_of_process'],
            'client_name' => $request['client_name'],
            'categoryId' => $request['category'],
            'dateFrom' => $fromDateCarbon->format('Y-m-d'),
            'dateTo' => $toDateCarbon->format('Y-m-d'),
        ]);

        $userIds = $request->input('user', []);

        if (!is_array($userIds)) {
            $userIds = [];
        }

        $userIds[] = auth()->id();

        $userIds = array_unique($userIds);

        if (!empty($userIds)) {
            $case->users()->attach($userIds);
        }

        return response()->json([
            'message' => 'Case created successfully!',
            'case' => $case,
        ]);
    }

    public function update(Request $request, CourtCase $case)
    {
        $user = auth()->user();

        if (!$user->canEditCase($case)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // $isAssigned = UserCase::where('case_id', $case->id)
        //     ->where('user_id', $user->id)
        //     ->where('isDeleted', 0)
        //     ->exists();

        // if (!$isAssigned && $user->userPermission !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        // Step 1: Prepare and validate
        $request->merge([
            'fromDate' => $request->input('fromDate'),
            'toDate' => $request->input('toDate'),
        ]);

        $request->validate([
            'atty_initials' => 'required|string|max:255',
            'type' => 'required|in:case',
            'fromDate' => ['required', 'date_format:m-d-Y'],
            'toDate' => ['required', 'date_format:m-d-Y', 'after_or_equal:fromDate'],
            'category' => ['required', 'not_in:-1', 'exists:categories,id'],
            'user.*' => ['required', 'exists:users,id'],
        ], [
            'category.not_in' => 'The category field is required.',
        ]);

        // Step 2: Parse and update case
        $fromDate = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('fromDate'))->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('toDate'))->format('Y-m-d');

        $case->update([
            'atty_initials' => $request['atty_initials'],
            'stage_of_process' => $request['stage_of_process'],
            'client_name' => $request['client_name'],
            'categoryId' => $request['category'],
            'dateFrom' => $fromDate,
            'dateTo' => $toDate,
        ]);

        // Step 3: Prepare users
        $newUserIds = $request->input('user', []);
        $newUserIds[] = auth()->id(); // always include auth user
        $newUserIds = array_unique($newUserIds);

        // Step 4: Get current user-case assignments
        $existingUserCases = UserCase::where('case_id', $case->id)->get()->keyBy('user_id');

        $existingUserIds = $existingUserCases->keys()->toArray();

        // Step 5: Determine changes
        $toSoftDelete = array_diff($existingUserIds, $newUserIds);
        $toReEnable   = array_intersect($existingUserIds, $newUserIds);
        $toInsert     = array_diff($newUserIds, $existingUserIds);

        // Step 6: Perform updates (batch)
        if (!empty($toSoftDelete)) {
            UserCase::where('case_id', $case->id)
                ->whereIn('user_id', $toSoftDelete)
                ->update(['isDeleted' => 1]);
        }

        if (!empty($toReEnable)) {
            UserCase::where('case_id', $case->id)
                ->whereIn('user_id', $toReEnable)
                ->update(['isDeleted' => 0]);
        }

        // Step 7: Bulk insert new rows
        if (!empty($toInsert)) {
            $insertData = [];
            $timestamp = now();

            foreach ($toInsert as $userId) {
                $insertData[] = [
                    'case_id' => $case->id,
                    'user_id' => $userId,
                    'isDeleted' => 0,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            UserCase::insert($insertData);
        }

        return response()->json([
            'message' => 'Case updated successfully!'
        ]);
    }

    public function delete(Request $request, CourtCase $case)
    {
        $user = auth()->user();

        if (!$user->canDeleteCase($case)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // $isAssigned = UserCase::where('case_id', $case->id)
        //     ->where('user_id', $user->id)
        //     ->where('isDeleted', 0)
        //     ->exists();

        // if (!$isAssigned && $user->userPermission !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        UserCase::where('case_id', $case->id)->update(['isDeleted' => 1]);

        $updated = $case->update([
            'isDeleted' => 1,
        ]);

        if (!$updated) {
            return response()->json(['error' => 'Failed to delete the case'], 500);
        }

        return response()->json([
            'message' => 'Case has been deleted!',
        ]);
    }

    public function updateCaseUser(Request $request)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $caseId = $request->input('case_id');
        $currentUserId = $request->input('current_user_id');
        $newUserId = $request->input('new_user_id');

        $request->validate([
            'case_id' => 'required|integer|exists:court_cases,id',
            'new_user_id' => 'required|integer|exists:users,id',
        ]);

        $case = UserCase::where('user_id', $currentUserId)
                ->where('case_id', $caseId)
                ->first();

        if (!$case) {
            return response()->json(['error' => 'Case not found or not assigned to current user'], 404);
        }

        $alreadyAssigned = UserCase::where('user_id', $newUserId)
            ->where('case_id', $caseId)
            ->exists();

        if ($alreadyAssigned) {
            return response()->json(['error' => 'Case is already assigned to the target user'], 422);
        }

        $case->user_id = $newUserId;
        $case->save();

        return response()->json([
            'message' => 'Case reassigned successfully.',
            'event' => $case,
        ]);
    }

    public function canCreateCase()
    {
        $user = auth()->user();

        return response()->json([
            'can_create' => $user->canCreateCase(),
        ]);
    }
}
?>

<?php

namespace App\Http\Controllers;

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

        if ($caseId) {
            $userCases = UserCase::with([
                'user:id,name',
                'case.categorie:id,categoryName,color'
            ])
            ->where('case_id', $caseId)
            ->get();

            if ($userCases->isEmpty()) {
                return response()->json(['error' => 'Case not found or has no users assigned'], 404);
            }

            $case = $userCases->first()->case;

            $assignedUserIds = $userCases->pluck('user_id')->toArray();

            if (!in_array($currentUser->id, $assignedUserIds) && $currentUser->userPermission !== 'admin') {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $transformed = [
                'id' => $case->id,
                'title' => $case->caseTitle ?? '',
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

            return response()->json($transformed);
        }

        $userIds = $request->input('user_id');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $viewMode = $request->input('view_mode');

        if (!$userIds) {
            $userIds = [auth()->id()];
        } elseif (!is_array($userIds)) {
            $userIds = [(int) $userIds];
        }

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
                ->get();
        }

        $userCases->transform(function ($event) use ($currentUser) {
            if (!$event->case) {
                $event->editable = false;
                return $event;
            }

            // Get all assigned user IDs for this case (via loaded relationship)
            $assignedUserIds = $event->case->users->pluck('id')->toArray();  // assuming case->users relationship

            $isAdmin = $currentUser->userPermission === 'admin';
            $isAssigned = in_array($currentUser->id, $assignedUserIds);

            // Optionally, check if creator if you track it
            $isCreator = $event->case->created_by === $currentUser->id;

            $event->editable = $isAdmin || $isAssigned || $isCreator;
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
        $fromDateRaw = $request->input('fromDate');
        $toDateRaw = $request->input('toDate');

        // Merge corrected input back into request
        $request->merge([
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
        ]);

        // Validate input dates in correct format
        $request->validate([
            'title' => 'required|string|max:255',
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
            'caseTitle' => $request['title'],
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
}

?>

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
        $userIds = $request->input('user_id');

        if (!$userIds) {
            $userIds = [auth()->id()];
        } elseif (!is_array($userIds)) {
            $userIds = [(int) $userIds];
        }

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Fetch from user_cases table with related case and user
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
            'event' => $case,
        ]);
    }
}

?>

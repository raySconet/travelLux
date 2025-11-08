<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventCourtCaseController extends Controller
{
    // public function index(Request $request)
    // {
    //     $currentUser = auth()->user();

    //     // Normalize user_ids (single or multiple)
    //     if ($request->has('user_id')) {
    //         $requestedUserIds = $request->input('user_id');
    //         if (!is_array($requestedUserIds)) {
    //             $requestedUserIds = [(int) $requestedUserIds];
    //         } else {
    //             $requestedUserIds = array_map('intval', $requestedUserIds);
    //         }
    //         if ($currentUser->isRegularUser() && !in_array($currentUser->id, $requestedUserIds)) {
    //             return response()->json([]);
    //         }
    //         $userIds = $requestedUserIds;
    //     } else {
    //         $userIds = [$currentUser->id];
    //     }

    //     // Parse dates safely
    //     try {
    //         $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    //         $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Invalid date format'], 400);
    //     }

    //     // Fetch events
    //     $events = Event::with(['categorie:id,categoryName,color', 'user:id,name'])
    //         ->select(['id', 'title', 'user_id', 'categoryId', 'date_from', 'date_to'])
    //         ->whereIn('user_id', $userIds)
    //         ->where('isDeleted', 0)
    //         ->whereBetween('date_from', [$startDate, $endDate])
    //         ->get();

    //     $events->transform(function ($event) use ($currentUser) {
    //         $event->editable = $currentUser->canEditEvent($event);
    //         return $event;
    //     });

    //     // Fetch cases (from user_cases relation)
    //     $userCases = UserCase::with([
    //             'user:id,name',
    //             'case.categorie:id,categoryName,color'
    //         ])
    //         ->whereIn('user_id', $userIds)
    //         ->whereHas('case', function ($query) use ($startDate, $endDate) {
    //             $query->where('isDeleted', 0)
    //                 ->whereBetween('dateFrom', [$startDate, $endDate]);
    //         })
    //         ->get();

    //     $userCases->transform(function ($userCase) use ($currentUser) {
    //         $userCase->editable = $userCase->case ? $currentUser->canEditCase($userCase->case) : false;
    //         return $userCase;
    //     });

    //     // Return combined response
    //     return response()->json(array_merge($events->toArray(), $userCases->toArray()));
    // }

    public function index(Request $request)
    {
        $currentUser = auth()->user();

        $assignedUserIds = UserAssignment::where('user_id', $currentUser->id)
            ->where('isDeleted', false)
            ->pluck('assigned_id')
            ->toArray();

        // Normalize user_ids (single or multiple)
        if ($request->has('user_id')) {
            $requestedUserIds = $request->input('user_id');
            if (!is_array($requestedUserIds)) {
                $requestedUserIds = [(int) $requestedUserIds];
            } else {
                $requestedUserIds = array_map('intval', $requestedUserIds);
            }

            // if ($currentUser->isRegularUser() && !in_array($currentUser->id, $requestedUserIds)) {
            //     return response()->json([]);
            // }

            if ($currentUser->isRegularUser() || $currentUser->isAdmin()) { // added new || $currentUser->isAdmin()
                $allowedUserIds = array_merge([$currentUser->id], $assignedUserIds);
                foreach ($requestedUserIds as $reqId) {
                    if (!in_array($reqId, $allowedUserIds)) {
                        return response()->json([], 403); // Forbidden
                    }
                }
            }

            $userIds = $requestedUserIds;
        } else {
            // $userIds = [$currentUser->id];
            if ($currentUser->isRegularUser() || $currentUser->isAdmin()) { // added new || $currentUser->isAdmin()
                $userIds = array_merge([$currentUser->id], $assignedUserIds);
            } else {
                $userIds = User::pluck('id')->toArray(); // Admin or other roles can see all users
            }
        }

        // Parse dates safely
        try {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Fetch events
        $events = Event::with(['categorie:id,categoryName,color', 'user:id,name'])
            ->select(['id', 'atty_initials', 'stage_of_process', 'client_name', 'user_id', 'categoryId', 'date_from', 'date_to', 'all_day'])
            ->whereIn('user_id', $userIds)
            ->where('isDeleted', 0)
            // ->where(function ($q) use ($userIds) {
            //     $q->whereIn('user_id', $userIds)
            //     ->orWhere('all_day', 1); // 👈 include all-day events for everyone
            // })
            ->whereBetween('date_from', [$startDate, $endDate])
            ->get();

        $events->transform(function ($event) use ($currentUser) {
            $event->editable = $currentUser->canEditEvent($event);
            return $event;
        });

        // Fetch cases
        $userCases = UserCase::with([
            'user:id,name',
            'case.categorie:id,categoryName,color'
        ])
            ->whereIn('user_id', $userIds)
            ->where('isDeleted', 0)
            ->whereHas('case', function ($query) use ($startDate, $endDate) {
                $query->where('isDeleted', 0)
                    ->whereBetween('dateFrom', [$startDate, $endDate]);
            })
            ->get();

        $userCases->transform(function ($userCase) use ($currentUser) {
            $userCase->editable = $userCase->case ? $currentUser->canEditCase($userCase->case) : false;
            return $userCase;
        });

        // Users who had at least one event or case
        $usersWithEvents = $events->pluck('user_id')->toArray();
        $usersWithCases = $userCases->pluck('user_id')->toArray();
        $usersWithData = array_unique(array_merge($usersWithEvents, $usersWithCases));

        // Users with no events or cases
        $missingUserIds = array_diff($userIds, $usersWithData);

        $missingUsers = User::whereIn('id', $missingUserIds)->select('id', 'name')->get();

        $dummyEntries = $missingUsers->map(function ($user) {
            return (object)[
                'id' => null,
                'user_id' => $user->id,
                'atty_initials' => null,
                'stage_of_process' => null,
                'client_name' => null,
                'case' => null,
                'categorie' => null,
                'date_from' => null,
                'date_to' => null,
                'all_day' => null,
                'editable' => false,
                'user' => (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ];
        });

        // Merge all data into one response
        $allData = collect($events)->concat($userCases)->concat($dummyEntries)->values();

        return response()->json($allData);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(Request $request) {
        $eventId = $request->input('event_case_id');

        $currentUser = auth()->user();

        // Enforce user permissions for viewing other users' events
        if ($request->has('user_id')) {
            $requestedUserIds = $request->input('user_id');

            // Normalize to array for easier checking
            if (!is_array($requestedUserIds)) {
                $requestedUserIds = [(int) $requestedUserIds];
            } else {
                $requestedUserIds = array_map('intval', $requestedUserIds);
            }

            // If user is regular and tries to access other user's events
            if ($currentUser->isRegularUser() && !in_array($currentUser->id, $requestedUserIds)) {
                return response()->json([]);
            }

            $userIds = $requestedUserIds;
        } else {
            $userIds = [$currentUser->id];
        }

        if ($eventId) {
            $event = Event::with([
                'categorie:id,categoryName,color',
                'user:id,name'
            ])
                ->select(['id', 'atty_initials', 'stage_of_process', 'client_name', 'user_id', 'categoryId', 'date_from', 'date_to'])
                ->where('id', $eventId)
                ->where('isDeleted', 0)
                ->first();

            if (!$event) {
                return response()->json(['error' => 'Event not found'], 404);
            }

            // if ($event->user_id !== $currentUser->id && $currentUser->userPermission !== 'admin') {
            //     return response()->json(['error' => 'Unauthorized access'], 403);
            // }

            if (!$currentUser->canEditEvent($event)) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            // Get all users and categories
            $users = User::getActiveUsers(); // You already have this
            $categories = Categorie::getActiveCategories(); // You already have this

            return response()->json([
                'eventCase' => $event,
                'users' => $users,
                'categories' => $categories,
                'auth_user_id' => $currentUser->id,
            ]);
        }

        // $userIds = $request->input('user_id');
        // $startDateInput = $request->input('start_date');
        // $endDateInput = $request->input('end_date');

        // if(!$userIds) {
        //     $userIds = [auth()->id()];
        // } elseif (!is_array($userIds)) {
        //     $userIds = [(int) $userIds];
        // }

        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        try {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $events = Event::with(['categorie:id,categoryName,color', 'user:id,name'])
            ->select(['id', 'atty_initials', 'stage_of_process', 'client_name', 'user_id', 'categoryId', 'date_from', 'date_to'])
            ->whereIn('user_id', $userIds)
            ->where('isDeleted', 0)
            ->whereBetween('date_from', [$startDate, $endDate])
            ->get();

        // $events->transform(function ($event) use ($currentUser) {
        //     $event->editable = ($currentUser->userPermission === 'admin' || $event->user_id === $currentUser->id);
        //     return $event;
        // });

        $events->transform(function ($event) use ($currentUser) {
            $event->editable = $currentUser->canEditEvent($event);
            return $event;
        });

        // Find user_ids present in events
        $userIdsWithEvents = $events->pluck('user_id')->unique()->toArray();

        // Find missing user_ids (users with no events)
        $missingUserIds = array_diff($userIds, $userIdsWithEvents);

        // Get missing users info
        $missingUsers = User::whereIn('id', $missingUserIds)->select('id', 'name')->get();

        // Create dummy event objects with only user info for missing users
        $missingUserEvents = $missingUsers->map(function ($user) {
            return (object)[
                'id' => null,
                'atty_initials' => null,
                'stage_of_process' => null,
                'client_name' => null,
                'user_id' => $user->id,
                'categoryId' => null,
                'date_from' => null,
                'date_to' => null,
                'categorie' => null,
                'user' => (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ];
        });

        // Merge actual events with dummy events
        $allEvents = $events->concat($missingUserEvents);

        return response()->json($allEvents);
    }

    public function store(Request $request) {
        $fromDateRaw = str_replace('+', ' ', $request->input('fromDate'));
        $toDateRaw = str_replace('+', ' ', $request->input('toDate'));

        // Merge corrected input back into request
        $request->merge([
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
        ]);

        // Validate input dates in correct format
        $request->validate([
            'atty_initials' => 'required|string|max:255',
            'type' => 'required|in:event',
            'fromDate' => ['required', 'date_format:m-d-Y H:i'],
            'toDate' => ['required', 'date_format:m-d-Y H:i', 'after:fromDate'],
            'category' => ['required','not_in:-1' , 'exists:categories,id'],
            // 'user' => 'required|exists:users,id',
        ], [
            'category.not_in' => 'The category field is required.',
        ]);

        // Convert to Carbon date for saving
        $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y H:i', $request->input('fromDate'));
        $toDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y H:i', $request->input('toDate'));

        // Conflict check
        if ($this->hasTimeConflict(auth()->id(), $fromDateCarbon, $toDateCarbon)) {
            throw ValidationException::withMessages([
                'fromDate' => ['You already have an event during this time.'],
                'toDate' => ['You already have an event during this time.'],
            ]);
        }

        $event = Event::create([
            'atty_initials' => $request['atty_initials'],
            'stage_of_process' => $request['stage_of_process'],
            'client_name' => $request['client_name'],
            'user_id' => auth()->id(),
            'categoryId' => $request['category'],
            'date_from' => $fromDateCarbon->format('Y-m-d H:i:s'),
            'date_to' => $toDateCarbon->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'message' => 'Event created successfully!',
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event) {
        $currentUser = auth()->user();

        if (!$currentUser->canEditEvent($event)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // if ($event->user_id !== auth()->id() && auth()->user()->userPermission !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        $fromDateRaw = str_replace('+', '', $request->input('fromDate'));
        $toDateRaw = str_replace('+', '', $request->input('toDate'));

        $request->merge([
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
        ]);

        $request->validate([
            'atty_initials' => 'required|string|max:255',
            'type' => 'required|in:event',
            'fromDate' => ['required', 'date_format:m-d-Y H:i'],
            'toDate' => ['required', 'date_format:m-d-Y H:i', 'after:fromDate'],
            'category' => ['required','not_in:-1' , 'exists:categories,id'],
        ], [
            'category.not_in' => 'The category field is required.',
        ]);

        $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y H:i', $request->input('fromDate'));
        $toDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y H:i', $request->input('toDate'));

        // Only check for conflict if dates were changed
        $existingFrom = \Carbon\Carbon::parse($event->date_from)->format('Y-m-d H:i');
        $existingTo = \Carbon\Carbon::parse($event->date_to)->format('Y-m-d H:i');
        $newFrom = $fromDateCarbon->format('Y-m-d H:i');
        $newTo = $toDateCarbon->format('Y-m-d H:i');

        if ($existingFrom !== $newFrom || $existingTo !== $newTo) {
            if ($this->hasTimeConflict(auth()->id(), $fromDateCarbon, $toDateCarbon, $event->id)) {
                throw ValidationException::withMessages([
                    'fromDate' => ['You already have an event during this time.'],
                    'toDate' => ['You already have an event during this time.'],
                ]);
            }
        }
        // // Conflict check
        // if ($this->hasTimeConflict(auth()->id(), $fromDateCarbon, $toDateCarbon, $event->id)) {
        //     throw ValidationException::withMessages([
        //         'fromDate' => ['You already have an event during this time.'],
        //         'toDate' => ['You already have an event during this time.'],
        //     ]);
        // }

        $event->update([
            'atty_initials' => $request->input('atty_initials'),
            'stage_of_process' => $request->input('stage_of_process'),
            'client_name' => $request->input('client_name'),
            'categoryId' => $request->input('category'),
            'date_from' => $fromDateCarbon->format('Y-m-d H:i:s'),
            'date_to' => $toDateCarbon->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'message' => 'Event updated successfully!',
            'event' => $event,
        ]);
    }

    public function delete(Request $request, Event $event) {
        // if ($event->user_id !== auth()->id() && auth()->user()->userPermission !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        $currentUser = auth()->user();

        if (!$currentUser->canDeleteEvent($event)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $updated = $event->update([
            'isDeleted' => 1,
        ]);

        if (!$updated) {
            return response()->json(['error' => 'Failed to delete the event'], 500);
        }

        return response()->json([
            'message' => 'Event has been deleted!',
        ]);
    }

    // private function hasTimeConflict($userId, $from, $to): bool
    // {
    //     return Event::where('user_id', $userId)
    //         ->where(function ($query) use ($from, $to) {
    //             $query->where('date_from', '<', $to)
    //                 ->where('date_to', '>', $from);
    //         })
    //         ->exists();
    // }

    public function updateEventUser(Request $request)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $eventId = $request->input('event_id');
        $newUserId = $request->input('new_user_id') ?? null;
        $newDate = $request->input('new_date');

        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            // 'new_user_id' => 'required|integer|exists:users,id',
        ]);

        $event = Event::find($eventId);

        if ($newDate) {
            $newDate = \Carbon\Carbon::parse($newDate)->startOfDay();

            // Extract original time components
            $originalFrom = \Carbon\Carbon::parse($event->date_from);
            $originalTo = \Carbon\Carbon::parse($event->date_to);

            // Combine new date with original time
            $fromDate = $newDate->copy()->setTimeFrom($originalFrom);
            $toDate = $newDate->copy()->setTimeFrom($originalTo);

            // Check for conflicts
            if ($this->hasTimeConflict($newUserId, $fromDate, $toDate, $event->id)) {
                return response()->json([
                    'error' => 'The target user already has an event during this time.'
                ], 422);
            }

            $event->date_from = $fromDate;
            $event->date_to = $toDate;
        }

        if($newUserId) {
            $event->user_id = $newUserId;
        }

        $event->save();

        return response()->json([
            'message' => 'Event reassigned successfully.',
            'event' => $event,
        ]);
    }

    protected function hasTimeConflict($userId, $fromDate, $toDate, $excludeEventId = null) {
        $query = Event::where('user_id', $userId)
            ->where('isDeleted', 0)
            ->where(function($q) use ($fromDate, $toDate) {
                // $q->whereBetween('date_from', [$fromDate, $toDate])
                // ->orWhereBetween('date_to', [$fromDate, $toDate])
                // ->orWhere(function($q2) use ($fromDate, $toDate) {
                //     $q2->where('date_from', '<', $fromDate)
                //         ->where('date_to', '>', $toDate);
                // });
                $q->where(function($q2) use ($fromDate, $toDate) {
                    $q2->where('date_from', '>=', $fromDate)
                    ->where('date_from', '<', $toDate);
                })
                ->orWhere(function($q3) use ($fromDate, $toDate) {
                    $q3->where('date_to', '>', $fromDate)
                    ->where('date_to', '<=', $toDate);
                })
                ->orWhere(function($q4) use ($fromDate, $toDate) {
                    $q4->where('date_from', '<', $fromDate)
                    ->where('date_to', '>', $toDate);
                });
            });

        if ($excludeEventId) {
            $query->where('id', '!=', $excludeEventId);
        }

        return $query->exists();
    }
    // protected function hasTimeConflict($userId, $fromDate, $toDate, $excludeEventId = null)
    // {
    //     $query = Event::where('user_id', $userId)
    //         ->where(function ($q) use ($fromDate, $toDate) {
    //             $q->where('date_from', '<', $toDate)
    //             ->where('date_to', '>', $fromDate);
    //         });

    //     if ($excludeEventId) {
    //         $query->where('id', '!=', $excludeEventId);
    //     }

    //     return $query->exists();
    // }
}

?>

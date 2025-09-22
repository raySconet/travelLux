<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(Request $request) {
        $userIds = $request->input('user_id');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if(!$userIds) {
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

        $events = Event::with(['categorie:id,categoryName,color', 'user:id,name'])
            ->select(['id', 'title', 'user_id', 'categoryId', 'date_from', 'date_to'])
            ->whereIn('user_id', $userIds)
            ->where('isDeleted', 0)
            ->whereBetween('date_from', [$startDate, $endDate])
            ->get();

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
                'title' => null,
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
            'title' => 'required|string|max:255',
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
            'title' => $request['title'],
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

    private function hasTimeConflict($userId, $from, $to): bool
    {
        return Event::where('user_id', $userId)
            ->where(function ($query) use ($from, $to) {
                $query->where('date_from', '<', $to)
                    ->where('date_to', '>', $from);
            })
            ->exists();
    }
}

?>

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
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

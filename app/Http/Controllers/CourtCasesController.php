<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourtCasesController extends Controller
{
    public function store(Request $request) {

        $fromDateRaw = $request->input('fromDate');
        $toDateRaw = $request->input('toDate');

        $fromDate = str_replace('+', ' ', $fromDateRaw);
        $toDate = str_replace('+', ' ', $toDateRaw);

        try {
            $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y H:i', $fromDate);
            $toDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y H:i', $toDate);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['fromDate' => ['Invalid fromDate format'], 'toDate' => ['Invalid toDate format']]], 422);
        }

        $request->merge([
            'fromDate' => $fromDateCarbon->format('Y-m-d H:i:s'),
            'toDate' => $toDateCarbon->format('Y-m-d H:i:s'),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
            'type' => 'required|in:case',
            'category' => 'required|exists:categories,id',
        ]);

        $event = Event::create([
            'case' => $request['title'],
            'user_id' => auth()->id(),
            'categoryId' => $request['category'],
            'date_from' => $request['fromDate'],
            'date_to' => $request['toDate'],
        ]);

        return response()->json([
            'message' => 'Event created successfully!',
            'event' => $event,
        ]);
    }
}

?>

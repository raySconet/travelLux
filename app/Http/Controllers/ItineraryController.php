<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItineraryTrip;
use App\Models\ItineraryDay;
use App\Models\ItineraryEvent;
use Barryvdh\DomPDF\Facade\Pdf;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'fname', 'lname', 'email')->where('isDeleted', 0)->get();

        $agentId = $request->input('agents', auth()->id());

        $itineraries = ItineraryTrip::with('creator')
            ->where('is_deleted', 0)
            ->when($agentId != -1, function ($query) use ($agentId) {
                $query->where('created_by', $agentId);
            })
            ->orderBy('date', 'desc')
            ->get();

        return view('itinerary', compact('users', 'agentId', 'itineraries'));
    }

    public function create()
    {
        return view('itinerary.createTrip');
    }

    public function store(Request $request)
    {
        $messages = [
            'name' => 'The Trip name is required.',
            'date' => 'The Trip date is required.'
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ], $messages);

        $itinerary = ItineraryTrip::create([
            'name' => $request->name,
            'date' => $request->date,
            'created_by' => auth()->id(),
            'is_deleted' => 0,
        ]);

        return redirect()->route('itinerary.index');
    }

    public function update(Request $request, ItineraryTrip $itinerary)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $itinerary->update([
            'name' => $request->name,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function edit(ItineraryTrip $itinerary)
    {
        $itinerary->load([
            'itineraryDays' => function ($query) {
                $query->where('isDeleted', 0)
                    ->with([
                        'events' => function ($eventQuery) {
                            $eventQuery->where('isDeleted', 0)
                                ->orderBy('eventTime', 'ASC');
                        }
                    ])
                    ->orderBy('dayNumber', 'ASC');
            }
        ]);

        return view('itinerary.edit', compact('itinerary'));
    }

    public function destroy(ItineraryTrip $itinerary)
    {
        $itinerary->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('itinerary.index')
                ->with('success', 'Itinerary deleted successfully');
    }

    public function addDay(ItineraryTrip $itinerary)
    {
        $lastDay = ItineraryDay::where('itinerary_trip_id', $itinerary->id)
            ->where('isDeleted', 0)
            ->max('dayNumber');

        $nextDayNumber = $lastDay ? $lastDay + 1 : 1;

        ItineraryDay::create([
            'dayNumber' => $nextDayNumber,
            'itinerary_trip_id' => $itinerary->id,
            'dayTitle' => null,
            'isDeleted' => 0,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function updateDay(Request $request, ItineraryDay $day)
    {
        $request->validate([
            'date' => 'nullable|date',
            'title' => 'nullable|string|max:255',
        ]);

        $day->update([
            'dayTitle' => $request->title,
            'dayDate' => $request->date,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroyDay(ItineraryDay $day)
    {
        $day->update([
            'isDeleted' => 1,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroyEvent(ItineraryEvent $event)
    {
        $event->update([
            'isDeleted' => 1,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function downloadPdf(ItineraryTrip $itinerary)
    {
        $itinerary->load([
            'creator',
            'itineraryDays' => function ($query) {
                $query->where('isDeleted', 0)
                    ->with([
                        'events' => function ($eventQuery) {
                            $eventQuery->where('isDeleted', 0)
                                ->orderBy('eventTime', 'ASC');
                        }
                    ])
                    ->orderBy('dayNumber', 'ASC');
            }
        ]);

        $pdf = Pdf::loadView('itinerary.itineraryPdf', compact('itinerary'))->setPaper('a4', 'portrait');

        return $pdf->download(
            $itinerary->name . '.pdf'
        );
    }

}
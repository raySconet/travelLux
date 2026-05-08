<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItineraryTrip;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'fname', 'lname', 'email')
            ->where('isDeleted', 0)
            ->get();

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
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
        ]);

        $itinerary = ItineraryTrip::create([
            'name' => $request->name,
            'date' => $request->date,
            'created_by' => auth()->id(),
            'is_deleted' => 0,
        ]);

        return redirect()->route('itinerary.index');
    }

    public function edit(ItineraryTrip $itinerary)
    {
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
}
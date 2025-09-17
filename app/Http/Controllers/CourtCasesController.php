<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\UserCase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourtCasesController extends Controller
{
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

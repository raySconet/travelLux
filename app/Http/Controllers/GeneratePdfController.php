<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use Illuminate\Http\Request;

class GeneratePdfController extends Controller
{
    public function generatePdfOne($id)
    {
        $gender = request('gender'); // "male" or "female"

        $case = CourtCase::with([
            'clients' => function ($q) {
                $q->orderBy('id', 'asc')
                    ->with([
                        'affidavits' => fn($q2) => $q2->orderBy('id', 'asc'),
                        'treatingChart' => fn($q3) => $q3->orderBy('id', 'asc'),
                        'negotiatingCharts' => fn($q4) => $q4->orderBy('id', 'asc')
                    ]);
            },
            'thirdParties' => fn($q) => $q->orderBy('id', 'asc'),
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'gender'  => $gender,
            'case'    => $case,
        ]);
    }
}

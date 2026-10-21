<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentsReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);
        $filters = $request->validate(['export' => ['nullable', Rule::in(['excel'])]]);

        // Preserve Home_Model::loadAgentsReportData's eligibility rule. This report
        // specifically uses -1, even though other screens treat 0 as enabled.
        $users = User::select('fname', 'lname', 'email', 'first_address_line', 'state', 'city', 'cell_phone_number')
            ->where('isDeleted', 0)->where('is_disabled', -1)->where('id', '!=', 6680)->get();

        if (($filters['export'] ?? null) === 'excel') {
            return response()->streamDownload(function () use ($users) {
                echo '<!DOCTYPE html><html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta charset="UTF-8"></head><body>';
                echo '<table><thead><tr>';
                foreach (['First Name', 'Last Name', 'Home Address', 'Email', 'Cellphone Number'] as $heading) {
                    echo '<th>'.e($heading).'</th>';
                }
                echo '</tr></thead><tbody>';
                foreach ($users as $user) {
                    echo '<tr>';
                    foreach ([$user->fname, $user->lname, $user->city.' - '.$user->state.' - '.$user->first_address_line, $user->email, $user->cell_phone_number] as $value) {
                        // Text cells preserve phone numbers and prevent spreadsheet formula execution.
                        echo '<td x:str style="mso-number-format:\'\\@\';">'.e($value ?? '').'</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table></body></html>';
            }, 'Agents.xls', ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8']);
        }

        return view('reports.agentsReport', compact('users'));
    }
}

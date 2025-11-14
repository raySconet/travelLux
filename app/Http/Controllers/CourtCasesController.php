<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\CourtCase;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserCase;
use App\Models\CaseClient;
use App\Models\CaseThirdP;
use App\Models\CaseFirstP;
use App\Models\CaseDefenseCounsel;
use App\Models\CaseAffidavit;
use App\Models\CaseTreatingChart;
use App\Models\CaseDepositExpensesAdv;
use App\Models\CaseNegotiatingChart;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CourtCasesController extends Controller
{
    public function index(Request $request)
    {
        $caseId = $request->input('event_case_id');
        $currentUser = auth()->user();

        $assignedUserIds = UserAssignment::where('user_id', $currentUser->id)
            ->where('isDeleted', false)
            ->pluck('assigned_id')
            ->toArray();

        if ($request->has('user_id')) {
            $requestedUserIds = $request->input('user_id');

            // Normalize to array
            if (!is_array($requestedUserIds)) {
                $requestedUserIds = [(int) $requestedUserIds];
            } else {
                $requestedUserIds = array_map('intval', $requestedUserIds);
            }

            // if ($currentUser->isRegularUser()) {
            //     if (!in_array($currentUser->id, $requestedUserIds)) {
            //         return response()->json([]);
            //     }
            //     $userIds = [$currentUser->id];
            // } else {
            //     $userIds = $requestedUserIds;
            // }
            if ($currentUser->isRegularUser() || $currentUser->isAdmin()) { // added new || $currentUser->isAdmin()
                $allowedUserIds = array_merge([$currentUser->id], $assignedUserIds);

                foreach ($requestedUserIds as $reqId) {
                    if (!in_array($reqId, $allowedUserIds)) {
                        return response()->json([], 403);
                    }
                }

                $userIds = $requestedUserIds;
            } else {
                $userIds = $requestedUserIds;
            }
        } else {
            // $userIds = [$currentUser->id];
            if ($currentUser->isRegularUser() || $currentUser->isAdmin()) { // added new || $currentUser->isAdmin()
                $userIds = array_merge([$currentUser->id], $assignedUserIds);
            } else {
                $userIds = User::pluck('id')->toArray(); // or however you want to handle admin/all users
            }
        }

        if ($caseId) {
            $userCases = UserCase::with([
                'user:id,name',
                'case.categorie:id,categoryName,color'
            ])
            ->where('case_id', $caseId)
            ->where('isdeleted', 0)
            ->get();

            if ($userCases->isEmpty()) {
                return response()->json(['error' => 'Case not found or has no users assigned'], 404);
            }

            $case = $userCases->first()->case;

            // $assignedUserIds = $userCases->pluck('user_id')->toArray();

            // if (!in_array($currentUser->id, $assignedUserIds) && $currentUser->userPermission !== 'admin') {
            //     return response()->json(['error' => 'Unauthorized access'], 403);
            // }

            if (!$currentUser->canEditCase($case)) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $transformed = [
                'id' => $case->id,
                'atty_initials' => $case->atty_initials ?? '',
                'stage_of_process' => $case->stage_of_process ?? '',
                'client_name' => $case->client_name ?? '',
                'categoryId' => $case->categoryId,
                'date_from' => $case->dateFrom,
                'date_to' => $case->dateTo,
                'categorie' => $case->categorie ? [
                    'id' => $case->categorie->id,
                    'categoryName' => $case->categorie->categoryName,
                    'color' => $case->categorie->color,
                ] : null,
                'users' => $userCases->map(function ($userCase) {
                    return [
                        'id' => $userCase->user->id,
                        'name' => $userCase->user->name,
                    ];
                })->values(),
            ];

            $users = User::getActiveUsers(); // You already have this
            $categories = Categorie::getActiveCategories(); // You already have this

            return response()->json([
                'eventCase' => $transformed,
                'users' => $users,
                'categories' => $categories,
                'auth_user_id' => $currentUser->id,
                'permissions' => [
                    'can_edit' => $currentUser->canEditCase($case),
                    'can_delete' => $currentUser->canDeleteCase($case),
                ],
            ]);

            // return response()->json($transformed);
        }

        // $userIds = $request->input('user_id');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $viewMode = $request->input('view_mode');

        // if (!$userIds) {
        //     $userIds = [auth()->id()];
        // } elseif (!is_array($userIds)) {
        //     $userIds = [(int) $userIds];
        // }

        try {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Fetch from user_cases table with related case and user
        $userCount = count($userIds);
        if ($viewMode === 'Month View' || ($viewMode === 'Day View' && $userCount !== 2) || ($viewMode === 'Week View' && $userCount !== 2)) {
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
                ->distinct()
                ->get();
        } else {
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
                // ->where('isdeleted', 0)
                ->get();
        }

        $userCases->transform(function ($event) use ($currentUser) {
            if (!$event->case) {
                $event->editable = false;
                return $event;
            }

            // $assignedUserIds = $event->case->users->pluck('id')->toArray();
            // $isAdmin = $currentUser->userPermission === 'admin';
            // $isAssigned = in_array($currentUser->id, $assignedUserIds);
            // $isCreator = $event->case->created_by === $currentUser->id;
            // $event->editable = $isAdmin || $isAssigned || $isCreator;
            $event->editable = $currentUser->canEditCase($event->case);

            return $event;
        });

        // Users who had at least one case
        $userIdsWithCases = $userCases->pluck('user_id')->unique()->toArray();

        // Users with no cases
        $missingUserIds = array_diff($userIds, $userIdsWithCases);

        $missingUsers = User::whereIn('id', $missingUserIds)->select('id', 'name')->get();

        // Build dummy events for users with no cases
        $dummyEvents = $missingUsers->map(function ($user) {
            return (object)[
                'id' => null,
                'user_id' => $user->id,
                'case_id' => null,
                'case' => null,
                'user' => (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ];
        });

        // Merge both
        $all = $userCases->concat($dummyEvents);

        return response()->json($all);
    }

    public function store(Request $request) {
        $currentUser = auth()->user();

        if (!$currentUser->canCreateCase()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $fromDateRaw = $request->input('fromDate');
        $toDateRaw = $request->input('toDate');

        // Merge corrected input back into request
        $request->merge([
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
        ]);

        // Validate input dates in correct format
        $request->validate([
            'atty_initials' => 'required|string|max:255',
            'type' => 'required|in:case',
            'fromDate' => ['required', 'date_format:m-d-Y'],
            'toDate' => ['required', 'date_format:m-d-Y', 'after_or_equal:fromDate'],
            'category' => ['required', 'not_in:-1', 'exists:categories,id'],
            // 'user' => ['required', 'array', 'min:1'],
            'user.*' => ['required', 'exists:users,id'],
        ], [
            'category.not_in' => 'The Category field is required.',
            'toDate.after' => 'The "To Date" must be later than the "From Date".',
        ]);

        // Convert to Carbon date for saving
        $fromDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('fromDate'));
        $toDateCarbon = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('toDate'));

        // create case
        $case = CourtCase::create([
            'atty_initials' => $request['atty_initials'],
            'stage_of_process' => $request['stage_of_process'],
            'client_name' => $request['client_name'],
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
            'case' => $case,
        ]);
    }

    public function update(Request $request, CourtCase $case)
    {
        $user = auth()->user();

        if (!$user->canEditCase($case)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // $isAssigned = UserCase::where('case_id', $case->id)
        //     ->where('user_id', $user->id)
        //     ->where('isDeleted', 0)
        //     ->exists();

        // if (!$isAssigned && $user->userPermission !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        // Step 1: Prepare and validate
        $request->merge([
            'fromDate' => $request->input('fromDate'),
            'toDate' => $request->input('toDate'),
        ]);

        $request->validate([
            'atty_initials' => 'required|string|max:255',
            'type' => 'required|in:case',
            'fromDate' => ['required', 'date_format:m-d-Y'],
            'toDate' => ['required', 'date_format:m-d-Y', 'after_or_equal:fromDate'],
            'category' => ['required', 'not_in:-1', 'exists:categories,id'],
            'user.*' => ['required', 'exists:users,id'],
        ], [
            'category.not_in' => 'The Category field is required.',
            'toDate.after' => 'The "To Date" must be later than the "From Date".',
        ]);

        // Step 2: Parse and update case
        $fromDate = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('fromDate'))->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('m-d-Y', $request->input('toDate'))->format('Y-m-d');

        $case->update([
            'atty_initials' => $request['atty_initials'],
            'stage_of_process' => $request['stage_of_process'],
            'client_name' => $request['client_name'],
            'categoryId' => $request['category'],
            'dateFrom' => $fromDate,
            'dateTo' => $toDate,
        ]);

        // Step 3: Prepare users
        $newUserIds = $request->input('user', []);
        $newUserIds[] = auth()->id(); // always include auth user
        $newUserIds = array_unique($newUserIds);

        // Step 4: Get current user-case assignments
        $existingUserCases = UserCase::where('case_id', $case->id)->get()->keyBy('user_id');

        $existingUserIds = $existingUserCases->keys()->toArray();

        // Step 5: Determine changes
        $toSoftDelete = array_diff($existingUserIds, $newUserIds);
        $toReEnable   = array_intersect($existingUserIds, $newUserIds);
        $toInsert     = array_diff($newUserIds, $existingUserIds);

        // Step 6: Perform updates (batch)
        if (!empty($toSoftDelete)) {
            UserCase::where('case_id', $case->id)
                ->whereIn('user_id', $toSoftDelete)
                ->update(['isDeleted' => 1]);
        }

        if (!empty($toReEnable)) {
            UserCase::where('case_id', $case->id)
                ->whereIn('user_id', $toReEnable)
                ->update(['isDeleted' => 0]);
        }

        // Step 7: Bulk insert new rows
        if (!empty($toInsert)) {
            $insertData = [];
            $timestamp = now();

            foreach ($toInsert as $userId) {
                $insertData[] = [
                    'case_id' => $case->id,
                    'user_id' => $userId,
                    'isDeleted' => 0,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            UserCase::insert($insertData);
        }

        return response()->json([
            'message' => 'Case updated successfully!'
        ]);
    }

    public function delete(Request $request, CourtCase $case)
    {
        $user = auth()->user();

        if (!$user->canDeleteCase($case)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // $isAssigned = UserCase::where('case_id', $case->id)
        //     ->where('user_id', $user->id)
        //     ->where('isDeleted', 0)
        //     ->exists();

        // if (!$isAssigned && $user->userPermission !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        UserCase::where('case_id', $case->id)->update(['isDeleted' => 1]);

        $updated = $case->update([
            'isDeleted' => 1,
        ]);

        if (!$updated) {
            return response()->json(['error' => 'Failed to delete the case'], 500);
        }

        return response()->json([
            'message' => 'Case has been deleted!',
        ]);
    }

    public function updateCaseUser(Request $request)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $caseId = $request->input('case_id');
        $currentUserId = $request->input('current_user_id') ?? null;
        $newUserId = $request->input('new_user_id') ?? null;
        $newDate = $request->input('new_date');

        $request->validate([
            'case_id' => 'required|integer|exists:court_cases,id',
            // 'new_user_id' => 'required|integer|exists:users,id',
        ]);

        if($currentUserId && $newUserId) {
            $userCase = UserCase::where('user_id', $currentUserId)
                    ->where('case_id', $caseId)
                    ->first();

            if (!$userCase) {
                return response()->json(['error' => 'Case not found or not assigned to current user'], 404);
            }

            if($currentUserId != $newUserId) {
                $alreadyAssigned = UserCase::where('user_id', $newUserId)
                    ->where('case_id', $caseId)
                    ->exists();

                if ($alreadyAssigned) {
                    return response()->json(['error' => 'Case is already assigned to the target user'], 422);
                }
            }

            $userCase->user_id = $newUserId;
            $userCase->save();
        }

        if ($newDate) {
            $case = CourtCase::where('id', $caseId)->first();

            if (!$case) {
                return response()->json(['error' => 'Case not found'], 404);
            }

            $oldFrom = Carbon::parse($case->dateFrom);
            $oldTo = Carbon::parse($case->dateTo);

            // ✅ Calculate difference between old start and new drop date
            $newStart = Carbon::parse($newDate);
            $daysToShift = $oldFrom->diffInDays($newStart, false); // second param = signed diff

            // ✅ Shift both dates by the same amount
            $newFrom = $oldFrom->copy()->addDays($daysToShift);
            $newTo = $oldTo->copy()->addDays($daysToShift);

            $case->dateFrom = $newFrom;
            $case->dateTo = $newTo;
            $case->save();
        }

        return response()->json([
            'message' => 'Case reassigned successfully.',
            // 'case' => $userCase,
            'newfrom' => $newFrom ?? null,
            'newto' => $newTo ?? null,
        ]);
    }

    public function canCreateCase()
    {
        $user = auth()->user();

        return response()->json([
            'can_create' => $user->canCreateCase(),
        ]);
    }




    public function updateCaseInfo(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Find the existing case
            $case = CourtCase::findOrFail($id);
            // 2️⃣ Update case main data
            $case->update([
                'referralChiro' => $request->referralChiro,
                'chiro' => $request->chiro,
                'doi' => $request->doi,
                'preExistingInjuries' => $request->preExistingInjuries ,
                'facts' => $request->facts,
                'injuries' => $request->injuries,
                'policeReport' => $request->policeReport,
                'photos' => $request->photos,
                'propertyDesctiption' => $request->propertyDesctiption,
                '3pLiability' => $request['3pLiability'],
                '3pCoverage' => $request['3pCoverage'],
                '3pLiabilityLimit' => $request['3pLiabilityLimit'],
                '1pCoverageLimits' => $request['1pCoverageLimits'],

                'caseInfoStyle' => $request->caseInfoStyle,
                'causeNumber' => $request->causeNumber,
                'courtCounty' => $request->courtCounty,
                'recordsServed' => $request->recordsServed,
                'filed' => $request->filed,
                'served' => $request->served,
                'answer' => $request->answer,
                'expertDesignationDeadlinesP' => $request->expertDesignationDeadlinesP,
                'discoveryResponsesP' => $request->discoveryResponsesP,
                'discoveryResponsesD' => $request->discoveryResponsesD,
                'discoveryPeriodEnds' => $request->discoveryPeriodEnds,
                'expertDesignationDeadlinesD' => $request->expertDesignationDeadlinesD,
                'deposP' => $request->deposP,
                'deposD' => $request->deposD,
                'docketCall' => $request->docketCall,
                'billingServedDate' => $request->billingServedDate,
                'noticeFiledDate' => $request->noticeFiledDate,
                'trial' => $request->trial,

                'firstpPermissionToRelease' => $request->firstpPermissionToRelease,
                'thirdpRelease' => $request->thirdpRelease,
                'firstpRelease' => $request->firstpRelease,
                'pip' => $request->pip,
                'statutoryLiens' => $request->statutoryLiens,
                'otherLiensSubrogationInterests' => $request->otherLiensSubrogationInterests,
                'disbursal' => $request->disbursal,
                'checks' => $request->checks
                // add any other fields from your form
            ]);

            // Decode JSON arrays sent from JS
            $clients = json_decode($request->clients, true);
            $firstParties = json_decode($request->first_parties, true);
            $thirdParties = json_decode($request->third_parties, true);
            $defenseCounsels = json_decode($request->defense_counsels, true);
            $expenses = json_decode($request->expenses, true);
            $advances = json_decode($request->advances, true);
            $deposits = json_decode($request->deposits, true);

            // Remove old related records if updating
            $oldClientIds = CaseClient::where('caseId', $id)->pluck('id');
            CaseAffidavit::whereIn('case_client_id', $oldClientIds)->delete();
            CaseNegotiatingChart::whereIn('case_client_id', $oldClientIds)->delete();
            CaseTreatingChart::whereIn('case_client_id', $oldClientIds)->delete();
            CaseClient::where('caseId', $id)->delete();
            CaseFirstP::where('caseId', $id)->delete();
            CaseThirdP::where('caseId', $id)->delete();
            CaseDefenseCounsel::where('caseId', $id)->delete();
            CaseDepositExpensesAdv::where('caseId', $id)->delete();

            // Insert Clients + their Affidavits
            if (!empty($clients)) {
                foreach ($clients as $client) {
                    $newClient = CaseClient::create([
                        'caseId' => $id,
                        'client_name' => $client['name'] ?? null,
                        'client_email' => $client['email'] ?? null,
                        'client_address' => $client['address'] ?? null,
                        'client_dob' => $client['dob'] ?? null,
                        'client_tel' => $client['tel'] ?? null,
                        'client_ssn' => $client['ssn'] ?? null,
                    ]);

                    if (!empty($client['affidavit_charts'])) {
                        foreach ($client['affidavit_charts'] as $affidavit) {
                            CaseAffidavit::create([
                                'case_client_id' => $newClient->id,
                                'providerName' => $affidavit['providerName'] ?? null,
                                'dateOrdered' => $affidavit['dateOrdered'] ?? null,
                                'dateReceivedMr' => $affidavit['dateReceivedMr'] ?? null,
                                'dateReceivedBr' => $affidavit['dateReceivedBr'] ?? null,
                                'dateServed' => $affidavit['affidavitDateServed'] ?? null,
                                'noticeFilled' => $affidavit['affidavitNoticeFiled'] ?? null,
                                'mri_and_results' => $affidavit['mriAndResults'] ?? null,
                                'controverted' => $affidavit['controverted'] ?? null,
                            ]);
                        }
                    }

                    if (!empty($client['treating_charts'])) {
                        foreach ($client['treating_charts'] as $treating) {
                            CaseTreatingChart::create([
                                'case_client_id' => $newClient->id,
                                'ems' => $treating['ems'] ?? null,
                                'hospital' => $treating['hospital'] ?? null,
                                'chiropractor' => $treating['chiropractor'] ?? null,
                                'pcpOrMd' => $treating['pcpmd'] ?? null,
                                'mriAndResults' => $treating['mriAndResults'] ?? null,
                                'painManagement' => $treating['painManagement'] ?? null,
                                'orthoOrSurgery' => $treating['orthoOrSurgery'] ?? null
                            ]);
                        }
                    }

                    if (!empty($client['negotiation_charts'])) {
                        foreach ($client['negotiation_charts'] as $negotiation) {
                            $newNegotiation = CaseNegotiatingChart::create([
                                'case_client_id' => $newClient->id,
                                'medsTotal' => $negotiation['medsTotal'] ?? null,
                                'medsPviTotal' => $negotiation['medsPviTotal'] ?? null,
                                'negotiationLastOffer' => $negotiation['negotiationLastOffer'] ?? null,
                                'negotiationLastOfferDate' => $negotiation['negotiationLastOfferDate'] ?? null,
                                'negotiationLastDemand' => $negotiation['negotiationLastDemand'] ?? null,
                                'negotiationLastDemandDate' => $negotiation['negotiationLastDemandDate'] ?? null,
                                'physicalPainMentalAnguishText' => $negotiation['physicalPainMentalAnguishText'] ?? null,
                            ]);

                            if (!empty($negotiation['negotiation_subs'])) {
                                foreach ($negotiation['negotiation_subs'] as $sub) {
                                    CaseNegotiatingChart::create([
                                        'case_client_id' => $newClient->id,
                                        'negotiationNameBottom' => $sub['negotiationNameBottom'] ?? null,
                                        'negotiationDateBottom' => $sub['negotiationDateBottom'] ?? null,
                                        'negotiationAmountBottom' => $sub['negotiationAmountBottom'] ?? null,
                                    ]);
                                }
                            }
                        }
                    }

                }
            }

            // Insert 1P (First Party)
            if (!empty($firstParties)) {
                foreach ($firstParties as $fp) {
                    CaseFirstP::create([
                        'caseId' => $id,
                        'name' => $fp['name'] ?? null,
                        'claim' => $fp['claim'] ?? null,
                        'adjuster' => $fp['adjuster'] ?? null,
                        'tel' => $fp['tel'] ?? null,
                        'fax' => $fp['fax'] ?? null,
                        'email' => $fp['email'] ?? null,
                    ]);
                }
            }

            // Insert 3P (Third Party)
            if (!empty($thirdParties)) {
                foreach ($thirdParties as $tp) {
                    CaseThirdP::create([
                        'caseId' => $id,
                        'third_party_name' => $tp['name'] ?? null,
                        'third_party_claim' => $tp['claim'] ?? null,
                        'third_party_adjuster' => $tp['adjuster'] ?? null,
                        'third_party_tel' => $tp['tel'] ?? null,
                        'third_party_fax' => $tp['fax'] ?? null,
                        'third_party_email' => $tp['email'] ?? null,
                    ]);
                }
            }

            // Insert Defense Counsels
            if (!empty($defenseCounsels)) {
                foreach ($defenseCounsels as $dc) {
                    CaseDefenseCounsel::create([
                        'caseId' => $id,
                        'defense_name' => $dc['name'] ?? null,
                        'defense_attorney' => $dc['attorney'] ?? null,
                        'defense_address' => $dc['address'] ?? null,
                        'defense_tel' => $dc['tel'] ?? null,
                        'defense_fax' => $dc['fax'] ?? null,
                        'defense_email' => $dc['email'] ?? null,
                    ]);
                }
            }


            if (!empty($deposits)) {
                foreach ($deposits as $dep) {
                    CaseDepositExpensesAdv::create([
                        'caseId' => $id,
                        'depositName' => $dep['name'] ?? null,
                        'depositPrice' => $dep['amount'] ?? null,
                        'depositDate' => $dep['date'] ?? null,
                        'depositCheckNumber' => $dep['checkNumber'] ?? null,
                    ]);
                }
            }
            if (!empty($advances)) {
                foreach ($advances as $adv) {
                    CaseDepositExpensesAdv::create([
                        'caseId' => $id,
                        'advancesName' => $adv['name'] ?? null,
                        'advancesAmount' => $adv['amount'] ?? null,
                        'advancesDate' => $adv['date'] ?? null,
                        'advancesCheckNumber' => $adv['checkNumber'] ?? null,
                    ]);
                }
            }

            if (!empty($expenses)) {
                foreach ($expenses as $exp) {
                    CaseDepositExpensesAdv::create([
                        'caseId' => $id,
                        'expensesName' => $exp['name'] ?? null,
                        'expensesPrice' => $exp['amount'] ?? null,
                        'expensesDate' => $exp['date'] ?? null,
                        'expensesCheck' => $exp['checkNumber'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Case updated with related data successfully!',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }



    public function mainInfo($id)
    {
        $case = CourtCase::with([
                'clients' => function ($q) {
                    $q->orderBy('id', 'asc')
                    ->with([
                        'affidavits' => function ($q2) {
                            $q2->orderBy('id', 'asc');
                        },
                        'treatingChart' => function ($q3) {
                            $q3->orderBy('id', 'asc');
                        },
                        'negotiatingCharts' => function ($q4) {
                            $q4->orderBy('id', 'asc');
                        }
                    ]);
                },
                'firstParties' => function ($q) {
                    $q->orderBy('id', 'asc');
                },
                'thirdParties' => function ($q) {
                    $q->orderBy('id', 'asc');
                },
                'defenseCounsels' => function ($q) {
                    $q->orderBy('id', 'asc');
                },
                'depositExpensesAdvs' => function ($q) {
                    $q->orderBy('id', 'asc');
                },
            ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'case' => $case
        ]);
    }

    public function saveTextarea(Request $request)
    {
        $request->validate([
            'text' => 'nullable|string',
            'case_id' => 'required|integer|exists:court_cases,id'
        ]);

        $case = CourtCase::find($request->case_id);
        $case->todoNote = $request->text; // example column
        $case->save();

        return response()->json(['success' => true]);
    }


    public function updateCategory(Request $request)
    {
        $request->validate([
            'case_id' => 'required|integer|exists:court_cases,id',
            'category_id' => 'required|integer',
        ]);

        $courtCase = CourtCase::findOrFail($request->case_id);
        $courtCase->categoryId = $request->category_id;
        $courtCase->save();

        return response()->json(['success' => true, 'message' => 'Category updated.']);
    }


}
?>

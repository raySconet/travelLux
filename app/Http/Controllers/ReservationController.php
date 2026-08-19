<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Destination;
use App\Models\ResortShip;
use App\Models\CruiseItinerary;
use App\Models\CustomersForm;
use App\Models\ReservationTask;
use App\Models\ReservationPayment;
use App\Models\ReservationDiningNote;
use App\Models\ReservationGift;
use App\Models\ReservationPhoneNote;
use App\Models\ReservationCommissionFee;
use App\Models\ReservationTraveler;
use App\Models\ReservationLink;
use App\Models\TimelineTask;
use App\Models\AutomatedEmail;
use App\Models\CustomerAutomatedEmail;
use App\Models\ItineraryTrip;
use App\Models\ReservationAttachment;
use Illuminate\Support\Facades\Storage;
use App\Models\ReservationPaidInFullAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\FormSent;
use App\Mail\CustomerFormMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationDetailsMail;
use App\Mail\CreditCardAuthorizationMail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
       $statuses = $request->input('status', ['Active']);
       $search = $request->input('search');


       $agentId = $request->input('users', auth()->id());

       $users = User::select('id','fname', 'lname','email')->where('isDeleted',0)->get();

        $reservationsQuery = Reservation::with('agent', 'customer', 'product', 'destination')
                                         ->select('id', 'status', 'created_on', 'reservation_number', 'reservation_name', 'customer_id', 'agent_id', 'product_id', 'destination_id', 'checkin_date', 'final_payment_due_date')
                                         ->where('is_deleted', 0);

        $reservationsQuery->where(function ($query) use ($statuses) {

            $regularStatuses = array_diff($statuses,['Paid in Full Paid by Travelux','Paid in Full Not Paid by Travelux']);

            if (!empty($regularStatuses)) {
                $query->whereIn('status', $regularStatuses);
            }

            if (in_array('Paid in Full Paid by Travelux', $statuses)) {
                $query->orWhere(function ($q) {
                    $q->where('status', 'Paid in Full')->where('agent_commission_received', 1);
                });
            }

            if (in_array('Paid in Full Not Paid by Travelux', $statuses)) {
                $query->orWhere(function ($q) {
                    $q->where('status', 'Paid in Full')->where('agent_commission_received', 0);
                });
            }
        });

        if($agentId !=-1){
            $reservationsQuery->where('agent_id', $agentId);
        }

        if ($search) {
            $reservationsQuery->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function ($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%");
                })
                ->orWhereHas('destination', function ($q) use ($search) {
                    $q->where('destination_name', 'like', "%{$search}%");
                })
                ->orWhere('reservation_number', 'like', "%{$search}%")
                ->orWhere('reservation_name', 'like', "%{$search}%");
            });
        }

        $reservations = $reservationsQuery->orderBy('created_on', 'asc')->get();
        return view('reservations.reservationList', compact('users', 'reservations', 'statuses', 'agentId'));
    }

    public function getDestinationsByProduct(Request $request)
    {
        return Destination::select('id','destination_name','product_id')->where('is_deleted', 0)->where('product_id', $request->product_id)->orderBy('destination_name')->get();
    }

    public function getResortsByDestination(Request $request)
    {
        return ResortShip::select('id','resort_ship_name','destination_id')->where('is_deleted', 0)->where('destination_id', $request->destination_id)->orderBy('resort_ship_name')->get();
    }

    public function getCruisesByResort(Request $request)
    {
        return CruiseItinerary::select('id','cruise_name','resort_ship_id')->where('is_deleted', 0)->where('resort_ship_id', $request->resort_id)->orderBy('cruise_name')->get();
    }

    private function getCustomersForAgent(int $agentId)
    {
        return Cache::remember("customers_by_agent_{$agentId}", 300, function () use ($agentId) {
            return Customer::query()
                ->select('id', 'fname', 'lname', 'agent_id', 'email', 'cellphone')
                ->where('agent_id', $agentId)
                ->where('is_deleted', 0)
                ->with(['familyMembers' => function ($q) {
                    $q->select('id', 'customer_id', 'fname', 'lname', 'email')
                        ->where('is_deleted', 0)
                        ->whereNotNull('email')
                        ->where('email', '!=', '');
                }])
                ->orderBy('lname')->orderBy('fname')
                ->get();
        });
    }

    public function getCustomersByAgent(Request $request, $agentId)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && (int) $agentId !== (int) $user->id) {
            abort(403);
        }
        return response()->json($this->getCustomersForAgent((int) $agentId));
    }

    public function create()
    {
        $reservation = new Reservation();
        $isNewReservation = true;
<<<<<<< HEAD

=======
>>>>>>> e53932fb7025ef35767d0e21072f5bee7639f001
        $user = auth()->user();
        $selectedCustomer = null;

<<<<<<< HEAD
        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted',0)->orderBy('lname')->get();
        $itineraryTrips = ItineraryTrip::where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get();
        $overdueTasksCount = ReservationTask::where('reservation_id', $reservation->id)->where('is_deleted', 0)->where('is_completed', 0)->whereDate('due_date', '<=', now())->count();

        $customersPayload = Cache::remember('customers_payload_'.$user->id, 600, function () use ($user) {
=======
        $users = Cache::remember('reservation_users', 600, fn () =>
            User::select('id','fname','lname','email','commission')
                ->where('isDeleted', 0)->orderBy('lname')->orderBy('fname')->get()
        );
>>>>>>> e53932fb7025ef35767d0e21072f5bee7639f001

       $products = Product::select('id', 'product_name')
            ->where('is_deleted', 0)
            ->orderBy('product_name')
            ->get();

        $destinations = Destination::select('id', 'destination_name')
            ->where('is_deleted', 0)
            ->where('product_id', $reservation->product_id)
            ->orderBy('destination_name')
            ->get();

        $resorts = ResortShip::select('id', 'resort_ship_name')
            ->where('is_deleted', 0)
            ->where('destination_id', $reservation->destination_id)
            ->orderBy('resort_ship_name')
            ->get();

        $cruises = CruiseItinerary::select('id', 'cruise_name')
            ->where('is_deleted', 0)
            ->where('resort_ship_id', $reservation->resort_id)
            ->orderBy('cruise_name')
            ->get();

        $itineraryTrips = Cache::remember('reservation_itinerary_trips_'.$user->id, 600, fn () =>
            ItineraryTrip::select('id','name','date')
                ->where('is_deleted', 0)->where('created_by', $user->id)
                ->orderBy('date', 'desc')->get()
        );

        $initialCustomers = $user->isAdmin() ? [] : $this->getCustomersForAgent($user->id);
        $initialAgentId = $user->isAdmin() ? null : $user->id;

        return view('reservations.reservationDetails', compact('users', 'reservation', 'isNewReservation', 'products', 'destinations', 'resorts', 'cruises','itineraryTrips', 'selectedCustomer', 'initialCustomers', 'initialAgentId'));
    }

    public function edit(Reservation $reservation)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $reservation->agent_id != $user->id) {
            abort(403);
        }

        $isNewReservation = false;
        $reservation->load([
            'paidInFullAudits.modifiedByUser',
        ]);

<<<<<<< HEAD
                                });
                                $q->where('is_deleted', 0);
                            })
                            ->get();

        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted',0)->orderBy('lname')->get();
        $itineraryTrips = ItineraryTrip::where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get();
        $linkedReservations = ReservationLink::where('reservation_id', $reservation->id)->where('is_linked', 1) ->with('linkedReservation')->get();
        $overdueTasksCount = ReservationTask::where('reservation_id', $reservation->id)->where('is_deleted', 0)->where('is_completed', 0)->whereDate('due_date', '<=', now())->count();
        $timelineTasks = $reservation->tasks()->with('agent')->where('is_deleted',0)->where('is_timeline_task',1)->get();
        $generalTasks = $reservation->tasks()->with('agent')->where('is_deleted',0)->where('is_timeline_task',0)->get();
=======
        $users = Cache::remember('reservation_users', 600, fn () =>
            User::select('id','fname','lname','email','commission')
                ->where('isDeleted', 0)->orderBy('lname')->orderBy('fname')->get()
        );
  
        $products = Product::select('id', 'product_name')
            ->where('is_deleted', 0)
            ->orderBy('product_name')
            ->get();
>>>>>>> e53932fb7025ef35767d0e21072f5bee7639f001

        $destinations = Destination::select('id', 'destination_name')
            ->where('is_deleted', 0)
            ->where('product_id', $reservation->product_id)
            ->orderBy('destination_name')
            ->get();

        $resorts = ResortShip::select('id', 'resort_ship_name')
            ->where('is_deleted', 0)
            ->where('destination_id', $reservation->destination_id)
            ->orderBy('resort_ship_name')
            ->get();

        $cruises = CruiseItinerary::select('id', 'cruise_name')
            ->where('is_deleted', 0)
            ->where('resort_ship_id', $reservation->resort_id)
            ->orderBy('cruise_name')
            ->get();

        $overdueTasksCount = ReservationTask::where('reservation_id', $reservation->id)
            ->where('is_deleted', 0)->where('is_completed', 0)
            ->whereDate('due_date', '<=', today())->count();

        $selectedCustomer = $reservation->customer_id
            ? Customer::select('id','fname','lname','agent_id','email','cellphone')
                ->with(['familyMembers' => fn($q) => $q->select('id','customer_id','fname','lname','email')
                    ->where('is_deleted', 0)->whereNotNull('email')->where('email','!=','')])
                ->find($reservation->customer_id)
            : null;

        $relevantAgentId = $reservation->agent_id ?? $user->id;
        $initialCustomers = $this->getCustomersForAgent($relevantAgentId);
        $initialAgentId = $reservation->agent_id ?? ($user->isAdmin() ? null : $user->id);
        
        return view('reservations.reservationDetails', compact('users', 'reservation', 'isNewReservation', 'products', 'destinations', 'resorts', 'cruises','overdueTasksCount', 'selectedCustomer', 'initialCustomers', 'initialAgentId'));
    }




    public function searchForCompletedReservations(Request $request)
    {
        $reservations = Reservation::query()
            ->with([
                'customer:id,fname,lname',
                'agent:id,fname,lname,email',
                'product:id,product_name',
            ])
            ->where('commission_received', 0)
            ->whereIn('status', [
                'Paid in Full',
                'Canceled w/ Insurance Payout',
                'Canceled - Commission Protected',
            ])
            ->where('non_commissionable', 0)
            ->where('is_deleted', 0)

            ->when($request->filled('reservation_number'), function ($query) use ($request) {
                $query->where(
                    'reservation_number',
                    'LIKE',
                    '%' . $request->reservation_number . '%'
                );
            })

            ->when($request->filled('customer_last_name'), function ($query) use ($request) {
                $query->whereHas('customer', function ($query) use ($request) {
                    $query->where(
                        'lname',
                        'LIKE',
                        '%' . $request->customer_last_name . '%'
                    );
                });
            })

            ->when($request->filled('group_number'), function ($query) use ($request) {
                $query->where(
                    'group_number',
                    'LIKE',
                    '%' . $request->group_number . '%'
                );
            })

            ->when($request->filled('reservation_cost'), function ($query) use ($request) {
                $query->where(
                    'reservation_cost',
                    $request->reservation_cost
                );
            })

            ->when($request->filled('agency_commission'), function ($query) use ($request) {
                $query->where(
                    'agency_commission',
                    $request->agency_commission
                );
            })

            ->get();

        return response()->json([
            'success' => true,
            'count' => $reservations->count(),
            'data' => $reservations,
        ]);
    }





    private function generateTimelineTasks($reservation)
    {
        ReservationTask::where('reservation_id', $reservation->id)->where('is_timeline_task', 1)->delete();

        if (!$reservation->product_id || !$reservation->destination_id) {
            return;
        }

        $timelineTasks = TimelineTask::where('product_id', $reservation->product_id)->where('destination_id', $reservation->destination_id)->get();

        foreach ($timelineTasks as $task) {

            $baseDate = match ($task->date_type) {
                'Check In Date' => $reservation->checkin_date,
                'Check Out Date' => $reservation->checkout_date,
                'Deposit Due Date' => $reservation->deposit_due_date,
                'Final Payment Due Date' => $reservation->final_payment_due_date,
                default => $reservation->created_on,
            };

            $dueDate = $baseDate ? \Carbon\Carbon::parse($baseDate) ->{strtolower($task->before_after) === 'before' ? 'subDays' : 'addDays'}($task->due_days) : null;

            ReservationTask::create([
                'reservation_id' => $reservation->id,
                'timeline_task_id' => $task->id,
                'task_name' => $task->task_name,
                'priority' => $task->priority,
                'due_date' => $dueDate,
                'date_type' => $task->date_type,
                'is_timeline_task' => 1,
                'created_by' => auth()->id(),
                'created_on' => now(),
            ]);
        }
    }

    private function sendAutomatedEmailsIfNeeded(Reservation $reservation): void
    {
        if ($reservation->stop_auto_emails) {
            return;
        }

        if (!in_array($reservation->status, ['Active', 'Paid in Full'])) {
            return;
        }

        $reservation->load(['customer','agent',]);

        $customer = $reservation->customer;
        $agent = $reservation->agent;

        if (!$customer || !$agent) {
            return;
        }

        $clientEmail = $reservation->email_to_send ?: $customer->email;

        if (!$clientEmail) {
            return;
        }

        $emails = AutomatedEmail::with('attachments')
            ->where('is_deleted', 0)
            ->where('is_disabled', 0)
            ->where('email_type', 'Reservation Reminder')
            ->where('agent_id', $agent->id)
            ->get()
        ;

        foreach ($emails as $email) {

            $productIds = array_filter(explode(',', $email->product_list ?? ''));

            $destinationIds = array_values(array_diff(array_filter(explode(',', $email->destination_list ?? '')),['-1']));

            $resortIds = array_values(array_diff(array_filter(explode(',', $email->resort_list ?? '')),['-1']));

            $matches = false;

            if ($reservation->resort_id && in_array($reservation->resort_id, $resortIds)) {

                $matches = true;

            } elseif ($reservation->destination_id && in_array($reservation->destination_id, $destinationIds) && empty($resortIds)) {

                $matches = true;

            } elseif ($reservation->product_id && in_array($reservation->product_id, $productIds) && empty($destinationIds) && empty($resortIds)) {

                $matches = true;
            }

            if (!$matches) {
                continue;
            }

            $checkin = Carbon::parse($reservation->checkin_date);

            $sendDate = strtolower($email->before_after) === 'after' ? $checkin->copy()->addDays($email->days) : $checkin->copy()->subDays($email->days);

            if (now()->lt($sendDate)) {
                continue;
            }

            $alreadySent = CustomerAutomatedEmail::where([
                'reservation_id' => $reservation->id,
                'customer_id' => $reservation->customer_id,
                'automated_email_id' => $email->id,
            ])->exists();

            if ($alreadySent) {
                continue;
            }

            if ($this->sendAutomatedEmail($reservation, $email)) {

                CustomerAutomatedEmail::create([
                    'customer_id' => $reservation->customer_id,
                    'reservation_id' => $reservation->id,
                    'automated_email_id' => $email->id,
                    'date' => now(),
                ]);

            }
        }
    }

    private function sendAutomatedEmail(Reservation $reservation,AutomatedEmail $automatedEmail): bool {

        $customer = $reservation->customer;
        $agent = $reservation->agent;

        $clientEmail = $reservation->email_to_send ?: $customer->email;

        $signature = "
            <br><br>
            <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                Thank you, please let me know if I can further assist you!
            </span>

            <br><br>

            <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                {$agent->fname} {$agent->lname}
            </span>
            <br>
        ";

        if (!empty($agent->phone_number)) {

            $signature .= "
                <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                    {$agent->phone_number}
                </span>
                <br>
            ";

        }

        $signature .= "
            <span>
                <a style='color:#3B3BFF;font-weight:bold;font-size:15px;' href='mailto:{$agent->email}'>
                    {$agent->email}
                </a>
            </span>

            <br><br>

            <span>
                <a style='color:#3B3BFF;font-weight:bold;font-size:15px;' href='https://gotravelux.com/'>
                    www.gotravelux.com
                </a>
            </span>

            <br><br>

            <span style='color:#006FC9;font-weight:bold;font-size:13px;'>
                I book everything from hotels, Disney, Universal Studios,
                All Inclusive resorts, all cruise lines and more!
            </span>
        ";

        $body = $automatedEmail->message . $signature;

        try {

            Mail::send([], [], function ($message) use (
                $clientEmail,
                $reservation,
                $agent,
                $automatedEmail,
                $body
            ) {

                $message->to($clientEmail);

                if (!empty($reservation->spouse_email) && filter_var($reservation->spouse_email, FILTER_VALIDATE_EMAIL)) {

                    $message->cc($reservation->spouse_email);
                }

                if ($automatedEmail->bcc_agent) {
                    $message->bcc($agent->email);
                }

                $message->subject($automatedEmail->subject);

                $message->html($body);

                foreach ($automatedEmail->attachments as $attachment) {

                    $path = public_path('attachments/automatedEmails/' . $attachment->id . '.' . $attachment->file_extension);

                    if (File::exists($path)) {

                        $message->attach(
                            $path,
                            [
                                'as' => $attachment->file_name . '.' . $attachment->file_extension
                            ]
                        );

                    }

                }

            });

            return true;

        } catch (\Throwable $e) {

            Log::error($e);

            return false;

        }
    }

    private const RESORT_FACT_SHEETS = [
        // Zoetry Agua Punta Cana
        166 => 'http://www.amresorts.com/mediasite/documents/2013/12/zoapc-fs.pdf',
        263 => 'http://www.amresorts.com/mediasite/documents/2013/12/zoapc-fs.pdf',
        916 => 'http://www.amresorts.com/mediasite/documents/2013/12/zoapc-fs.pdf',
        967 => 'http://www.amresorts.com/mediasite/documents/2013/12/zoapc-fs.pdf',
        1062 => 'http://www.amresorts.com/mediasite/documents/2013/12/zoapc-fs.pdf',

        // Zoetry Montego Bay Jamaica
        180 => 'http://www.amresorts.com/mediasite/documents/2016/03/zombj-fs.pdf',
        286 => 'http://www.amresorts.com/mediasite/documents/2016/03/zombj-fs.pdf',
        917 => 'http://www.amresorts.com/mediasite/documents/2016/03/zombj-fs.pdf',
        968 => 'http://www.amresorts.com/mediasite/documents/2016/03/zombj-fs.pdf',
        1063 => 'http://www.amresorts.com/mediasite/documents/2016/03/zombj-fs.pdf',

        // Zoetry Paraiso de la Bonita Riviera Maya
        38 => 'http://www.amresorts.com/mediasite/documents/2013/12/zopdb-fs.pdf',
        196 => 'http://www.amresorts.com/mediasite/documents/2013/12/zopdb-fs.pdf',
        302 => 'http://www.amresorts.com/mediasite/documents/2013/12/zopdb-fs.pdf',
        965 => 'http://www.amresorts.com/mediasite/documents/2013/12/zopdb-fs.pdf',
        1060 => 'http://www.amresorts.com/mediasite/documents/2013/12/zopdb-fs.pdf',

        // Zoetry Villa Rolandi Isla Mujeres Cancun
        878 => 'http://www.amresorts.com/mediasite/documents/2014/08/zvrim-fs.pdf',
        915 => 'http://www.amresorts.com/mediasite/documents/2014/08/zvrim-fs.pdf',
        966 => 'http://www.amresorts.com/mediasite/documents/2014/08/zvrim-fs.pdf',
        1019 => 'http://www.amresorts.com/mediasite/documents/2014/08/zvrim-fs.pdf',
        1061 => 'http://www.amresorts.com/mediasite/documents/2014/08/zvrim-fs.pdf',

        // Secrets Akumal Riviera Maya
        34 => 'http://www.amresorts.com/mediasite/documents/2015/01/searm-fs.pdf',
        192 => 'http://www.amresorts.com/mediasite/documents/2015/01/searm-fs.pdf',
        298 => 'http://www.amresorts.com/mediasite/documents/2015/01/searm-fs.pdf',
        969 => 'http://www.amresorts.com/mediasite/documents/2015/01/searm-fs.pdf',
        1064 => 'http://www.amresorts.com/mediasite/documents/2015/01/searm-fs.pdf',

        // Secrets Aura Cozumel
        879 => 'http://www.amresorts.com/mediasite/documents/2013/12/seacz-fs.pdf',
        918 => 'http://www.amresorts.com/mediasite/documents/2013/12/seacz-fs.pdf',
        970 => 'http://www.amresorts.com/mediasite/documents/2013/12/seacz-fs.pdf',
        1020 => 'http://www.amresorts.com/mediasite/documents/2013/12/seacz-fs.pdf',
        1065 => 'http://www.amresorts.com/mediasite/documents/2013/12/seacz-fs.pdf',

        // Secrets Huatulco Resort & Spa
        880 => 'http://www.amresorts.com/mediasite/documents/2013/12/sechu-fs.pdf',
        919 => 'http://www.amresorts.com/mediasite/documents/2013/12/sechu-fs.pdf',
        971 => 'http://www.amresorts.com/mediasite/documents/2013/12/sechu-fs.pdf',
        1021 => 'http://www.amresorts.com/mediasite/documents/2013/12/sechu-fs.pdf',
        1066 => 'http://www.amresorts.com/mediasite/documents/2013/12/sechu-fs.pdf',

        // Secrets Maroma Beach Riviera Cancun
        881 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        920 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        972 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        1022 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        1067 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',

        // Secrets Playa Mujeres Golf & Spa Resort
        882 => 'http://www.amresorts.com/mediasite/documents/2014/02/secpm-fs.pdf',
        921 => 'http://www.amresorts.com/mediasite/documents/2014/02/secpm-fs.pdf',
        973 => 'http://www.amresorts.com/mediasite/documents/2014/02/secpm-fs.pdf',
        1023 => 'http://www.amresorts.com/mediasite/documents/2014/02/secpm-fs.pdf',
        1068 => 'http://www.amresorts.com/mediasite/documents/2014/02/secpm-fs.pdf',

        // Secrets Puerto Los Cabos Golf & Spa Resort
        883 => 'http://www.amresorts.com/mediasite/documents/2013/12/seplc-fs.pdf',
        922 => 'http://www.amresorts.com/mediasite/documents/2013/12/seplc-fs.pdf',
        974 => 'http://www.amresorts.com/mediasite/documents/2013/12/seplc-fs.pdf',
        1024 => 'http://www.amresorts.com/mediasite/documents/2013/12/seplc-fs.pdf',
        1069 => 'http://www.amresorts.com/mediasite/documents/2013/12/seplc-fs.pdf',

        // Secrets Riviera Cancun Resort & Spa
        884 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        923 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        975 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        1025 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',
        1070 => 'http://www.amresorts.com/mediasite/documents/2013/12/semrc-fs.pdf',

        // Secrets Silversands Riviera Cancun
        885 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesrc-fs.pdf',
        924 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesrc-fs.pdf',
        976 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesrc-fs.pdf',
        1026 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesrc-fs.pdf',
        1071 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesrc-fs.pdf',

        // Secrets The Vine Cancun
        886 => 'http://www.amresorts.com/mediasite/documents/2013/12/sevcu-fs.pdf',
        925 => 'http://www.amresorts.com/mediasite/documents/2013/12/sevcu-fs.pdf',
        977 => 'http://www.amresorts.com/mediasite/documents/2013/12/sevcu-fs.pdf',
        1027 => 'http://www.amresorts.com/mediasite/documents/2013/12/sevcu-fs.pdf',
        1072 => 'http://www.amresorts.com/mediasite/documents/2013/12/sevcu-fs.pdf',

        // Secrets Vallarta Bay Puerto Vallarta
        887 => 'http://www.amresorts.com/mediasite/documents/2013/12/secvb-fs.pdf',
        926 => 'http://www.amresorts.com/mediasite/documents/2013/12/secvb-fs.pdf',
        978 => 'http://www.amresorts.com/mediasite/documents/2013/12/secvb-fs.pdf',
        1028 => 'http://www.amresorts.com/mediasite/documents/2013/12/secvb-fs.pdf',
        1073 => 'http://www.amresorts.com/mediasite/documents/2013/12/secvb-fs.pdf',

        // Secrets St. James Montego Bay
        177 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesmb-fs.pdf',
        283 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesmb-fs.pdf',
        927 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesmb-fs.pdf',
        979 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesmb-fs.pdf',
        1074 => 'http://www.amresorts.com/mediasite/documents/2013/12/sesmb-fs.pdf',

        // Secrets Wild Orchid Montego Bay
        888 => 'http://www.amresorts.com/mediasite/documents/2013/12/sewmb-fs.pdf',
        928 => 'http://www.amresorts.com/mediasite/documents/2013/12/sewmb-fs.pdf',
        980 => 'http://www.amresorts.com/mediasite/documents/2013/12/sewmb-fs.pdf',
        1029 => 'http://www.amresorts.com/mediasite/documents/2013/12/sewmb-fs.pdf',
        1075 => 'http://www.amresorts.com/mediasite/documents/2013/12/sewmb-fs.pdf',

        // Secrets Cap Cana Resort & Spa
        164 => 'http://www.amresorts.com/mediasite/documents/2016/02/seccc-fs.pdf',
        261 => 'http://www.amresorts.com/mediasite/documents/2016/02/seccc-fs.pdf',
        929 => 'http://www.amresorts.com/mediasite/documents/2016/02/seccc-fs.pdf',
        981 => 'http://www.amresorts.com/mediasite/documents/2016/02/seccc-fs.pdf',
        1076 => 'http://www.amresorts.com/mediasite/documents/2016/02/seccc-fs.pdf',

        // Secrets Royal Beach Punta Cana
        889 => 'http://www.amresorts.com/mediasite/documents/2013/12/secrb-fs.pdf',
        930 => 'http://www.amresorts.com/mediasite/documents/2013/12/secrb-fs.pdf',
        982 => 'http://www.amresorts.com/mediasite/documents/2013/12/secrb-fs.pdf',
        1030 => 'http://www.amresorts.com/mediasite/documents/2013/12/secrb-fs.pdf',
        1077 => 'http://www.amresorts.com/mediasite/documents/2013/12/secrb-fs.pdf',

        // Secrets Papagayo Costa Rica
        49 => 'http://www.amresorts.com/mediasite/documents/2015/07/sepcr-fs.pdf',
        54 => 'http://www.amresorts.com/mediasite/documents/2015/07/sepcr-fs.pdf',
        931 => 'http://www.amresorts.com/mediasite/documents/2015/07/sepcr-fs.pdf',
        1031 => 'http://www.amresorts.com/mediasite/documents/2015/07/sepcr-fs.pdf',
        107 => 'http://www.amresorts.com/mediasite/documents/2015/07/sepcr-fs.pdf',

        // Breathless Punta Cana Resort & Spa
        160 => 'http://www.amresorts.com/mediasite/documents/2013/12/brepc-fs.pdf',
        257 => 'http://www.amresorts.com/mediasite/documents/2013/12/brepc-fs.pdf',
        935 => 'http://www.amresorts.com/mediasite/documents/2013/12/brepc-fs.pdf',
        986 => 'http://www.amresorts.com/mediasite/documents/2013/12/brepc-fs.pdf',
        1082 => 'http://www.amresorts.com/mediasite/documents/2013/12/brepc-fs.pdf',

        // Breathless Cabo San Lucas Resort & Spa
        24 => 'http://www.amresorts.com/mediasite/documents/2015/07/brecl-fs.pdf',
        182 => 'http://www.amresorts.com/mediasite/documents/2015/07/brecl-fs.pdf',
        288 => 'http://www.amresorts.com/mediasite/documents/2015/07/brecl-fs.pdf',
        987 => 'http://www.amresorts.com/mediasite/documents/2015/07/brecl-fs.pdf',
        1083 => 'http://www.amresorts.com/mediasite/documents/2015/07/brecl-fs.pdf',

        // Breathless Riviera Cancun Resort & Spa
        893 => 'http://23.21.66.147/mediasite/documents/2015/07/brerc-fs.pdf',
        936 => 'http://23.21.66.147/mediasite/documents/2015/07/brerc-fs.pdf',
        988 => 'http://23.21.66.147/mediasite/documents/2015/07/brerc-fs.pdf',
        1035 => 'http://23.21.66.147/mediasite/documents/2015/07/brerc-fs.pdf',
        1084 => 'http://23.21.66.147/mediasite/documents/2015/07/brerc-fs.pdf',

        // Breathless Montego Bay Resort & Spa
        167 => 'http://www.amresorts.com/mediasite/documents/2016/03/bremb-fs.pdf',
        273 => 'http://www.amresorts.com/mediasite/documents/2016/03/bremb-fs.pdf',
        937 => 'http://www.amresorts.com/mediasite/documents/2016/03/bremb-fs.pdf',
        989 => 'http://www.amresorts.com/mediasite/documents/2016/03/bremb-fs.pdf',
        1085 => 'http://www.amresorts.com/mediasite/documents/2016/03/bremb-fs.pdf',

        // Dreams Huatulco Resort & Spa
        894 => 'http://www.amresorts.com/mediasite/documents/2013/12/drehu-fs.pdf',
        938 => 'http://www.amresorts.com/mediasite/documents/2013/12/drehu-fs.pdf',
        991 => 'http://www.amresorts.com/mediasite/documents/2013/12/drehu-fs.pdf',
        1036 => 'http://www.amresorts.com/mediasite/documents/2013/12/drehu-fs.pdf',
        1087 => 'http://www.amresorts.com/mediasite/documents/2013/12/drehu-fs.pdf',

        // Dreams Los Cabos Suites Golf Resort & Spa
        895 => 'http://www.amresorts.com/mediasite/documents/2013/12/drelc-fs.pdf',
        939 => 'http://www.amresorts.com/mediasite/documents/2013/12/drelc-fs.pdf',
        992 => 'http://www.amresorts.com/mediasite/documents/2013/12/drelc-fs.pdf',
        1037 => 'http://www.amresorts.com/mediasite/documents/2013/12/drelc-fs.pdf',
        1088 => 'http://www.amresorts.com/mediasite/documents/2013/12/drelc-fs.pdf',

        // Dreams Playa Mujeres Golf & Spa Resort
        897 => 'http://www.amresorts.com/mediasite/documents/2016/02/drepm-fs.pdf',
        941 => 'http://www.amresorts.com/mediasite/documents/2016/02/drepm-fs.pdf',
        994 => 'http://www.amresorts.com/mediasite/documents/2016/02/drepm-fs.pdf',
        1039 => 'http://www.amresorts.com/mediasite/documents/2016/02/drepm-fs.pdf',
        1090 => 'http://www.amresorts.com/mediasite/documents/2016/02/drepm-fs.pdf',

        // Dreams Puerto Aventuras Resort & Spa
        898 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepa-fs.pdf',
        942 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepa-fs.pdf',
        995 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepa-fs.pdf',
        1040 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepa-fs.pdf',
        1091 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepa-fs.pdf',

        // Dreams Riviera Cancun Resort & Spa
        899 => 'http://www.amresorts.com/mediasite/documents/2013/12/drerc-fs.pdf',
        943 => 'http://www.amresorts.com/mediasite/documents/2013/12/drerc-fs.pdf',
        996 => 'http://www.amresorts.com/mediasite/documents/2013/12/drerc-fs.pdf',
        1041 => 'http://www.amresorts.com/mediasite/documents/2013/12/drerc-fs.pdf',
        1092 => 'http://www.amresorts.com/mediasite/documents/2013/12/drerc-fs.pdf',

        // Dreams Sands Cancun Resort & Spa
        900 => 'http://www.amresorts.com/mediasite/documents/2014/06/dresc-fs.pdf',
        944 => 'http://www.amresorts.com/mediasite/documents/2014/06/dresc-fs.pdf',
        997 => 'http://www.amresorts.com/mediasite/documents/2014/06/dresc-fs.pdf',
        1042 => 'http://www.amresorts.com/mediasite/documents/2014/06/dresc-fs.pdf',
        1093 => 'http://www.amresorts.com/mediasite/documents/2014/06/dresc-fs.pdf',

        // Dreams Tulum Resort & Spa
        901 => 'http://www.amresorts.com/mediasite/documents/2013/12/dretu-fs.pdf',
        945 => 'http://www.amresorts.com/mediasite/documents/2013/12/dretu-fs.pdf',
        998 => 'http://www.amresorts.com/mediasite/documents/2013/12/dretu-fs.pdf',
        1043 => 'http://www.amresorts.com/mediasite/documents/2013/12/dretu-fs.pdf',
        1094 => 'http://www.amresorts.com/mediasite/documents/2013/12/dretu-fs.pdf',

        // Dreams Villamagna Nuevo Vallarta
        902 => 'http://www.amresorts.com/mediasite/documents/2013/12/drevm-fs.pdf',
        946 => 'http://www.amresorts.com/mediasite/documents/2013/12/drevm-fs.pdf',
        999 => 'http://www.amresorts.com/mediasite/documents/2013/12/drevm-fs.pdf',
        1044 => 'http://www.amresorts.com/mediasite/documents/2013/12/drevm-fs.pdf',
        1095 => 'http://www.amresorts.com/mediasite/documents/2013/12/drevm-fs.pdf',

        // Dreams Dominicus La Romana
        161 => 'http://www.amresorts.com/mediasite/documents/2015/05/dredl-fs.pdf',
        258 => 'http://www.amresorts.com/mediasite/documents/2015/05/dredl-fs.pdf',
        948 => 'http://www.amresorts.com/mediasite/documents/2015/05/dredl-fs.pdf',
        1001 => 'http://www.amresorts.com/mediasite/documents/2015/05/dredl-fs.pdf',
        1097 => 'http://www.amresorts.com/mediasite/documents/2015/05/dredl-fs.pdf',

        // Dreams Palm Beach Punta Cana
        905 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepb-fs.pdf',
        950 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepb-fs.pdf',
        1003 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepb-fs.pdf',
        1047 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepb-fs.pdf',
        1099 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepb-fs.pdf',

        // Dreams Punta Cana Resort & Spa
        906 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        951 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        1004 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        1048 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        1100 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',

        // Dreams Las Mareas Costa Rica
        48 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        53 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        952 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        1049 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',
        1101 => 'http://www.amresorts.com/mediasite/documents/2013/12/drepc-fs.pdf',

        // Dreams Playa Bonita Panama
        47 => 'http://23.21.66.147/mediasite/documents/2016/09/fs_drepbp.pdf',
        953 => 'http://23.21.66.147/mediasite/documents/2016/09/fs_drepbp.pdf',
        1005 => 'http://23.21.66.147/mediasite/documents/2016/09/fs_drepbp.pdf',
        1050 => 'http://23.21.66.147/mediasite/documents/2016/09/fs_drepbp.pdf',
        1102 => 'http://23.21.66.147/mediasite/documents/2016/09/fs_drepbp.pdf',

        // Now Amber Puerto Vallarta
        32 => 'http://www.amresorts.com/mediasite/documents/2013/12/noapv-fs.pdf',
        190 => 'http://www.amresorts.com/mediasite/documents/2013/12/noapv-fs.pdf',
        296 => 'http://www.amresorts.com/mediasite/documents/2013/12/noapv-fs.pdf',
        1007 => 'http://www.amresorts.com/mediasite/documents/2013/12/noapv-fs.pdf',
        1104 => 'http://www.amresorts.com/mediasite/documents/2013/12/noapv-fs.pdf',

        // Now Jade Riviera Cancun
        909 => 'http://www.amresorts.com/mediasite/documents/2013/12/nojrc-fs.pdf',
        956 => 'http://www.amresorts.com/mediasite/documents/2013/12/nojrc-fs.pdf',
        1009 => 'http://www.amresorts.com/mediasite/documents/2013/12/nojrc-fs.pdf',
        1053 => 'http://www.amresorts.com/mediasite/documents/2013/12/nojrc-fs.pdf',
        1106 => 'http://www.amresorts.com/mediasite/documents/2013/12/nojrc-fs.pdf',

        // Now Sapphire Riviera Cancun
        910 => 'http://www.amresorts.com/mediasite/documents/2013/12/nosrc-fs.pdf',
        957 => 'http://www.amresorts.com/mediasite/documents/2013/12/nosrc-fs.pdf',
        1010 => 'http://www.amresorts.com/mediasite/documents/2013/12/nosrc-fs.pdf',
        1054 => 'http://www.amresorts.com/mediasite/documents/2013/12/nosrc-fs.pdf',
        1107 => 'http://www.amresorts.com/mediasite/documents/2013/12/nosrc-fs.pdf',

        // Now Larimar Punta Cana
        163 => 'http://www.amresorts.com/mediasite/documents/2013/12/nolpc-fs.pdf',
        260 => 'http://www.amresorts.com/mediasite/documents/2013/12/nolpc-fs.pdf',
        958 => 'http://www.amresorts.com/mediasite/documents/2013/12/nolpc-fs.pdf',
        1011 => 'http://www.amresorts.com/mediasite/documents/2013/12/nolpc-fs.pdf',
        1108 => 'http://www.amresorts.com/mediasite/documents/2013/12/nolpc-fs.pdf',

        // Now Onyx Punta Cana
        911 => 'http://www.amresorts.com/mediasite/documents/2016/03/noopc-fs.pdf',
        959 => 'http://www.amresorts.com/mediasite/documents/2016/03/noopc-fs.pdf',
        1012 => 'http://www.amresorts.com/mediasite/documents/2016/03/noopc-fs.pdf',
        1055 => 'http://www.amresorts.com/mediasite/documents/2016/03/noopc-fs.pdf',
        1109 => 'http://www.amresorts.com/mediasite/documents/2016/03/noopc-fs.pdf',

        // Sunscape Dorado Pacifico Ixtapa
        912 => 'http://www.amresorts.com/mediasite/documents/2013/12/sudix-fs.pdf',
        960 => 'http://www.amresorts.com/mediasite/documents/2013/12/sudix-fs.pdf',
        1014 => 'http://www.amresorts.com/mediasite/documents/2013/12/sudix-fs.pdf',
        1056 => 'http://www.amresorts.com/mediasite/documents/2013/12/sudix-fs.pdf',
        1111 => 'http://www.amresorts.com/mediasite/documents/2013/12/sudix-fs.pdf',

        // Sunscape Puerto Vallarta Resort & Spa
        913 => 'http://www.amresorts.com/mediasite/documents/2015/11/sunpv-fs.pdf',
        961 => 'http://www.amresorts.com/mediasite/documents/2015/11/sunpv-fs.pdf',
        1015 => 'http://www.amresorts.com/mediasite/documents/2015/11/sunpv-fs.pdf',
        1057 => 'http://www.amresorts.com/mediasite/documents/2015/11/sunpv-fs.pdf',
        1112 => 'http://www.amresorts.com/mediasite/documents/2015/11/sunpv-fs.pdf',

        // Sunscape Sabor Cozumel
        914 => 'http://www.amresorts.com/mediasite/documents/2013/12/suscz-fs.pdf',
        962 => 'http://www.amresorts.com/mediasite/documents/2013/12/suscz-fs.pdf',
        1016 => 'http://www.amresorts.com/mediasite/documents/2013/12/suscz-fs.pdf',
        1058 => 'http://www.amresorts.com/mediasite/documents/2013/12/suscz-fs.pdf',
        1113 => 'http://www.amresorts.com/mediasite/documents/2013/12/suscz-fs.pdf',

        // Sunscape Curacao Resort, Spa & Casino
        50 => 'http://www.amresorts.com/mediasite/documents/2013/12/sucur-fs.pdf',
        963 => 'http://www.amresorts.com/mediasite/documents/2013/12/sucur-fs.pdf',
        1017 => 'http://www.amresorts.com/mediasite/documents/2013/12/sucur-fs.pdf',
        1059 => 'http://www.amresorts.com/mediasite/documents/2013/12/sucur-fs.pdf',
        1114 => 'http://www.amresorts.com/mediasite/documents/2013/12/sucur-fs.pdf',
    ];

    private function sendBrochureToCustomer(Reservation $reservation): bool
    {
        if ($reservation->stop_auto_emails) {
            return false;
        }

        $reservation->load(['customer','agent',]);

        $customer = $reservation->customer;
        $agent = $reservation->agent;

        if (!$customer || !$agent) {
            return false;
        }

        $clientEmail = $reservation->email_to_send ?: $customer->email;

        if (!$clientEmail) {
            return false;
        }

        $factSheetLink = self::RESORT_FACT_SHEETS[$reservation->resort_id] ?? null;

        if (!$factSheetLink) {
            return false;
        }

        $signature = "
            <br><br>
            <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                Thank you, please let me know if I can further assist you!
            </span>

            <br><br>

            <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                {$agent->fname} {$agent->lname}
            </span>
            <br>
        ";

        if (!empty($agent->phone_number)) {

            $signature .= "
                <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                    {$agent->phone_number}
                </span>
                <br>
            ";

        }

        $signature .= "
            <span>
                <a style='color:#3B3BFF;font-weight:bold;font-size:15px;' href='mailto:{$agent->email}'>
                    {$agent->email}
                </a>
            </span>

            <br><br>

            <span>
                <a style='color:#3B3BFF;font-weight:bold;font-size:15px;' href='https://gotravelux.com/'>
                    www.gotravelux.com
                </a>
            </span>

            <br><br>

            <span style='color:#006FC9;font-weight:bold;font-size:13px;'>
                I book everything from hotels, Disney, Universal Studios,
                All Inclusive resorts, all cruise lines and more!
            </span>
        ";

        $body = "
            You have picked a great resort for your trip, here is a fact sheet with
            additional information. Please feel free to contact me if you see
            anything you have questions on or want to know more about.

            <br><br>

            <a href='{$factSheetLink}'>{$factSheetLink}</a>

            {$signature}
        ";

        try {

            Mail::send([], [], function ($message) use ($clientEmail,$reservation,$agent,$body) {

                $message->to($clientEmail);

                if (!empty($reservation->spouse_email) && filter_var($reservation->spouse_email, FILTER_VALIDATE_EMAIL)) {

                    $message->cc($reservation->spouse_email);
                }

                $message->subject('Fact Sheet');

                $message->html($body);

            });

            return true;

        } catch (\Throwable $e) {

            Log::error($e);

            return false;

        }
    }

    public function store(Request $request)
    {
        $messages = [
            'agent_id.required' => 'The Agent field is required.',
            'customer_id.required' => 'The Customer field is required.',
            'reservation_number.required' => 'The Reservation Number field is required.',
            'reservation_name.required' => 'The Reservation Name field is required.',
            'reservation_cost.required' => 'The Total Cost field is required.',
            'agency_commission.required' => 'The Total Agency Commission field is required.',
            'agent_commission.required' => 'The Agent Commission field is required.',
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required',
            'resort_id.required' => 'The Resort/Ship field is required.',
            'onboard_credit_from_cruise_line.numeric' => 'The OBC From Cruise Line field must be numeric and may contain 2 decimal points.',
            'onboard_credit_from_agent' => 'The OBC From Agent field must be numeric and may contain 2 decimal points.'
        ];

        $data = $request->validate([
            'agent_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'reservation_name' => 'required|string|max:255',
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'agent_commission' => 'required|numeric',
            'status' => 'required|string|max:255',
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',

            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'spouse_email' => 'nullable|string|max:255',
            'email_to_send' => 'nullable|string|max:255',
            'celebrations' => 'nullable|array',
            'celebrations.*' => 'string|max:255',
            'celebration_notes' => 'nullable|string|max:255',
            'room_category' => 'nullable|string|max:255',
            'stateroom_number' => 'nullable|string|max:255',
            'embarkation_port' => 'nullable|string|max:255',
            'ticket_types' => 'nullable|array',
            'ticket_types.*' => 'integer',
            'dining_option' => 'nullable|string|max:255',
            'add_on_options' => 'nullable|array',
            'add_on_options.*' => 'integer',
            'transportation_options' => 'nullable|array',
            'transportation_options.*' => 'integer',
            'cruise_level' => 'nullable|string|max:255',

            'onboard_credit_from_cruise_line' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],
            'onboard_credit_from_agent' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],

            'commission_notes' => 'nullable|string',
            'reservation_notes' => 'nullable|string',
            'flight_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'deposit_due_date' => 'nullable|date',
            'final_payment_due_date' => 'nullable|date',
            'unknown_reservation_date' => 'nullable|date',

            'commission_received' => 'nullable|integer',
            'commission_claimed' => 'nullable|integer',
            'look_up' => 'nullable|integer',
            'document_fee' => 'nullable|integer',
            'agent_commission_percentage' => 'nullable|integer',
            'commission_to_agent' => 'nullable|integer',
            'non_commissionable' => 'nullable|integer',
            'is_surprise' => 'nullable|integer',
            'agent_personal_travel' => 'nullable|integer',
            'secondary_agent' => 'nullable|integer',
            'cruise_itinerary_id' => 'nullable|integer',
            'days_of_tickets' => 'nullable|integer',
            'concierge_ship' => 'nullable|integer',
            'club_level_resort' => 'nullable|integer',
            'submitted_to_rewards' => 'nullable|integer',
            'agent_commission_received' => 'nullable|integer',
            'mentor_commission_received' => 'nullable|integer',
            'remove_mentor_commission' => 'nullable|integer',
            'unknown_reservation_checked_flag' => 'nullable|integer',
            'stop_auto_emails' => 'nullable|integer',
            'radio_station_ads' => 'nullable|integer',
            'itinerary_trip_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'last_modified_by' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',

            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',

        ], $messages);

        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        $data['ticket_types'] = !empty($data['ticket_types']) ? implode(',', $data['ticket_types']) : null;
        $data['add_on_options'] = !empty($data['add_on_options']) ? implode(',', $data['add_on_options']) : null;
        $data['transportation_options'] = !empty($data['transportation_options']) ? implode(',', $data['transportation_options']) : null;
        $data['celebrations'] = !empty($data['celebrations']) ? implode(',', $data['celebrations']) : null;

        $user = auth()->user();

        $data['agent_id'] = $user->isAdmin() ? $data['agent_id'] : $user->id;

        $reservation = Reservation::create($data);
        $this->generateTimelineTasks($reservation);
        if (!$reservation->stop_auto_emails) {

            if (array_key_exists($reservation->resort_id, self::RESORT_FACT_SHEETS)) {
                $this->sendBrochureToCustomer($reservation);
            }

            $this->sendAutomatedEmailsIfNeeded($reservation);

        }

        $customer = Customer::with(['familyMembers' => function ($q) { $q->where('is_deleted', 0); }])->find($data['customer_id']);

        if ($customer && $customer->familyMembers->count() > 0) {

            $travelersData = $customer->familyMembers->map(function ($familyMember) use ($reservation) {

                return [
                    'reservation_id' => $reservation->id,
                    'customer_family_member_id' => $familyMember->id,
                    'is_included' => 0,
                    'is_deleted' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

            })->toArray();

            ReservationTraveler::insert($travelersData);
        }

        return redirect()
            ->route('reservations.reservationList')
            ->with('success', 'Reservation created successfully');
    }


    public function update(Request $request, Reservation $reservation)
    {
        $messages = [
            'agent_id.required' => 'The Agent field is required.',
            'customer_id.required' => 'The Customer field is required.',
            'reservation_number.required' => 'The Reservation Number field is required.',
            'reservation_name.required' => 'The Reservation Name field is required.',
            'reservation_cost.required' => 'The Total Cost field is required.',
            'agency_commission.required' => 'The Total Agency Commission field is required.',
            'agent_commission.required' => 'The Agent Commission field is required.',
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required',
            'resort_id.required' => 'The Resort/Ship field is required.',
            'onboard_credit_from_cruise_line' => 'The OBC From Cruise Line field must be numeric and may contain 2 decimal points.',
            'onboard_credit_from_agent' => 'The OBC From Agent field must be numeric and may contain 2 decimal points.',
            'days_of_tickets.numeric' => 'Invalid',
        ];

        $data = $request->validate([
            'agent_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reservation_number' => 'required|string|max:255',
            'reservation_name' => 'required|string|max:255',
            'reservation_cost' => 'required|numeric',
            'agency_commission' => 'required|numeric',
            'agent_commission' => 'required|numeric',
            'status' => 'required|string|max:255',
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'resort_id' => 'required|integer',

            'group_number' => 'nullable|string|max:50',
            'special_offer' => 'nullable|string|max:255',
            'spouse_email' => 'nullable|string|max:255',
            'email_to_send' => 'nullable|string|max:255',
            'celebrations' => 'nullable|array',
            'celebrations.*' => 'string|max:255',
            'celebration_notes' => 'nullable|string|max:255',
            'room_category' => 'nullable|string|max:255',
            'stateroom_number' => 'nullable|string|max:255',
            'embarkation_port' => 'nullable|string|max:255',
            'ticket_types' => 'nullable|array',
            'ticket_types.*' => 'integer',
            'dining_option' => 'nullable|string|max:255',
            'add_on_options' => 'nullable|array',
            'add_on_options.*' => 'integer',
            'transportation_options' => 'nullable|array',
            'transportation_options.*' => 'integer',
            'cruise_level' => 'nullable|string|max:255',

            'onboard_credit_from_cruise_line' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],
            'onboard_credit_from_agent' => ['nullable','regex:/^\d+(\.\d{1,2})?$/'],

            'commission_notes' => 'nullable|string',
            'reservation_notes' => 'nullable|string',
            'flight_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            'checkin_date' => 'nullable|date',
            'checkout_date' => 'nullable|date',
            'deposit_due_date' => 'nullable|date',
            'final_payment_due_date' => 'nullable|date',
            'unknown_reservation_date' => 'nullable|date',

            'commission_received' => 'nullable|integer',
            'commission_claimed' => 'nullable|integer',
            'look_up' => 'nullable|integer',
            'document_fee' => 'nullable|integer',
            'agent_commission_percentage' => 'nullable|integer',
            'commission_to_agent' => 'nullable|integer',
            'non_commissionable' => 'nullable|integer',
            'is_surprise' => 'nullable|integer',
            'agent_personal_travel' => 'nullable|integer',
            'secondary_agent' => 'nullable|integer',
            'cruise_itinerary_id' => 'nullable|integer',
            'days_of_tickets' => 'nullable|integer',
            'concierge_ship' => 'nullable|integer',
            'club_level_resort' => 'nullable|integer',
            'submitted_to_rewards' => 'nullable|integer',
            'agent_commission_received' => 'nullable|integer',
            'mentor_commission_received' => 'nullable|integer',
            'remove_mentor_commission' => 'nullable|integer',
            'unknown_reservation_checked_flag' => 'nullable|integer',
            'stop_auto_emails' => 'nullable|integer',
            'radio_station_ads' => 'nullable|integer',
            'itinerary_trip_id' => 'nullable|integer',
            'is_deleted' => 'nullable|integer',

            'is_website_lead_knot' => 'nullable|integer',
            'is_website_lead' => 'nullable|integer',
            'is_virtuoso_lead' => 'nullable|integer',
            'is_luxury_magazine_lead' => 'nullable|integer',
            'is_facebook_lead' => 'nullable|integer',
            'is_instagram_lead' => 'nullable|integer',
            'is_radio_lead' => 'nullable|integer',
        ], $messages);

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $data['ticket_types'] = !empty($data['ticket_types']) ? implode(',', $data['ticket_types']) : null;
        $data['add_on_options'] = !empty($data['add_on_options']) ? implode(',', $data['add_on_options']) : null;
        $data['transportation_options'] = !empty($data['transportation_options']) ? implode(',', $data['transportation_options']) : null;
        $data['celebrations'] = !empty($data['celebrations']) ? implode(',', $data['celebrations']) : null;

        $user = auth()->user();

        if (!$user->isAdmin()) {
            $data['agent_id'] = $user->id;
        }

        $oldStatus = $reservation->status;

        if ( $data['status'] === 'Paid in Full' && $oldStatus !== 'Paid in Full' && empty($reservation->paid_in_full_date) ) {
            $data['paid_in_full_date'] = now();
        }

        $wasPaidInFullBefore = !empty($reservation->paid_in_full_date);

        if ($wasPaidInFullBefore) {

            $changes = [];

            foreach ($data as $field => $newValue) {

                if ($field === 'last_modified_on' || $field === 'last_modified_by') {
                    continue;
                }

                $oldValue = $reservation->$field;

                if ( str_starts_with($field, 'is_') || in_array($field, [
                        'non_commissionable',
                        'agent_personal_travel',
                        'stop_auto_emails',
                        'radio_station_ads',
                        'remove_mentor_commission',
                        'commission_received',
                        'commission_claimed',
                        'look_up',
                        'document_fee',
                        'commission_to_agent',
                        'submitted_to_rewards',
                        'agent_commission_received',
                        'mentor_commission_received',
                    ])
                ) {
                    $oldValue = $oldValue ?? 0;
                    $newValue = $newValue ?? 0;
                }

                if (is_array($oldValue)) $oldValue = implode(',', $oldValue);
                if (is_array($newValue)) $newValue = implode(',', $newValue);

                if ($oldValue != $newValue) {
                    $changes[] = [
                        'reservation_id' => $reservation->id,
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'modified_by' => auth()->id(),
                        'modified_on' => now(),
                    ];
                }
            }

            if (!empty($changes)) {
                ReservationPaidInFullAudit::insert($changes);
            }
        }

        $oldCheckin = $reservation->checkin_date;
        $oldCheckout = $reservation->checkout_date;
        $oldDeposit = $reservation->deposit_due_date;
        $oldFinalPayment = $reservation->final_payment_due_date;

        $oldProduct = $reservation->product_id;
        $oldDestination = $reservation->destination_id;
        $oldResort = $reservation->resort_id;

        $reservation->update($data);

        if ($oldCheckin != $reservation->checkin_date || $oldCheckout != $reservation->checkout_date || $oldDeposit != $reservation->deposit_due_date || $oldFinalPayment != $reservation->final_payment_due_date)
        {
            $this->generateTimelineTasks($reservation);
        }

        if (!$reservation->stop_auto_emails) {

            if ($oldResort != $reservation->resort_id && array_key_exists($reservation->resort_id, self::RESORT_FACT_SHEETS)) {
                $this->sendBrochureToCustomer($reservation);
            }

            if ($oldCheckin != $reservation->checkin_date || $oldProduct != $reservation->product_id || $oldDestination != $reservation->destination_id) {
                $this->sendAutomatedEmailsIfNeeded($reservation);
            }

        }

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                $attachment = ReservationAttachment::create([
                    'reservation_id' => $reservation->id,
                    'file_name' => $originalName,
                    'file_extension' => $extension,
                    'file_size' => $file->getSize(),
                    'created_by' => auth()->id(),
                    'created_on' => now(),
                ]);

                $fileName = $attachment->id . '.' . $extension;

                $file->storeAs('attachments/reservations', $fileName, 'public');
            }
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Reservation updated successfully')
                ->with('activeTab', $request->input('activeTab', 'reservation-details'));
    }

    public function destroyAttachment(ReservationAttachment $attachment)
    {
        Storage::disk('public')->delete('attachments/reservations/' . $attachment->id . '.' . $attachment->file_extension);

        $attachment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $attachment->reservation_id)
                ->with('success', 'Reservation attachments deleted successfully')
                ->with('activeTab', 'attachments');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('reservations.reservationList')
            ->with('success', 'Reservation deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->selected_reservations;

        if (!$ids || count($ids) == 0) {
            return redirect()->back();
        }

        Reservation::whereIn('id', $ids)->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
            ->route('reservations.reservationList')
            ->with('success', 'Reservations deleted successfully.');
    }

    public function duplicate(Reservation $reservation)
    {
        $copy = $reservation->replicate();

        $copy->id = null;
        $copy->created_at = null;
        $copy->updated_at = null;

        $copy->reservation_name = $reservation->reservation_name . ' (Copy)';

        $users = User::select('id','fname','lname','email','commission')->where('isDeleted', 0)->get();

        $customers = Customer::select('id','fname','lname','agent_id','email','cellphone')->where('is_deleted', 0)->get();

        $products = Product::orderBy('product_name')->where('is_deleted', 0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted', 0)->get();
        $resortShips = ResortShip::orderBy('resort_ship_name')->where('is_deleted', 0)->get();
        $cruiseItineraries = CruiseItinerary::orderBy('cruise_name')->where('is_deleted', 0)->get();

        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted', 0)->orderBy('lname')->get();

        $itineraryTrips = ItineraryTrip::where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get();

        $isNewReservation = true;

        return view('reservations.reservationDetails', compact('copy','users','customers','products','destinations','resortShips','cruiseItineraries','referralCustomers','itineraryTrips','isNewReservation'))->with('reservation', $copy);
    }

    public function storeTask(Request $request, Reservation $reservation)
    {
        $messages = [
            'task_name.required' => 'The Task Name field is required.',
            'due_date.required' => 'The Due Date field is required.',
            'priority.required' => 'The Priority field is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'priority' => 'required|string',
            'due_date' => 'required|date',
            'task_notes' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->withErrors($validator, 'taskStore')
                ->withInput()
                ->with('activeTab', 'tasks')
                ->with('openTaskModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationTask::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Task added successfully')
                ->with('activeTab', 'tasks');
    }

    public function updateTask(Request $request, ReservationTask $task)
    {
        $validator = \Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'priority' => 'required|string',
            'due_date' => 'required|date',
            'task_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $task->update($data);

        return redirect()
                ->back()
                ->with('success', 'Task updated successfully')
                ->with('activeTab', 'tasks');
    }

    public function toggleCompleteTask(ReservationTask $task)
    {
        $completed = !$task->is_completed;

        $task->update([
            'is_completed' => $completed,
            'is_completed_by' => auth()->id(),
            'is_completed_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_completed' => $completed,
            ]);
        }

        return redirect()
            ->route('reservations.reservationDetails', $task->reservation_id)
            ->with('success', $completed ? 'Task completed' : 'Task marked incomplete')
            ->with('activeTab', 'tasks');
    }

    public function deleteTask(ReservationTask $task)
    {
        $task->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
            ->route('reservations.reservationDetails', $task->reservation_id)
            ->with('success', 'Task deleted successfully')
            ->with('activeTab', 'tasks');
    }

    public function storePayment(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Payment Amount field is required',
            'payment_type.required' => 'The Payment Type is required.',
            'payment_method.required' => 'The Payment Method is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'amount' => 'required|integer',
            'payment_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'check_number' => 'nullable|integer',
            'credit_card_number' => 'nullable|integer',
            'notes' => 'required|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                    ->route('reservations.reservationDetails', $reservation->id)
                    ->withErrors($validator, 'paymentStore')
                    ->withInput()
                    ->with('activeTab', 'payments')
                    ->with('openPaymentModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationPayment::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Customer Payments added successfully')
                ->with('activeTab', 'payments');
    }

    public function updatePayment(Request $request, ReservationPayment $payment)
    {
        $validator = \Validator::make($request->all(), [
            'amount' => 'required|integer',
            'payment_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'check_number' => 'nullable|integer',
            'credit_card_number' => 'nullable|integer',
            'notes' => 'required|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $payment->update($data);

        return redirect()
                ->back()
                ->with('success', 'Payment updated successfully')
                ->with('activeTab', 'payments');
    }

    public function deletePayment(ReservationPayment $payment)
    {
        $reservationId = $payment->reservation_id;

        $payment->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Payment deleted successfully')
                ->with('activeTab', 'payments');
    }

    public function storeDiningNote(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Note field is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'dining_date' => 'nullable|date',
            'dining_time' => 'nullable|date_format:H:i',
            'meal' => 'nullable|string',
            'notes' => 'required|string',
        ],$messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                    ->route('reservations.reservationDetails', $reservation->id)
                    ->withErrors($validator, 'diningNoteStore')
                    ->withInput()
                    ->with('activeTab', 'diningInformation')
                    ->with('openDiningInfoModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationDiningNote::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Dining Note added successfully')
                ->with('activeTab', 'diningInformation');
    }

    public function updateDiningNote(Request $request, ReservationDiningNote $diningNote)
    {
        $validator = \Validator::make($request->all(), [
            'dining_date' => 'nullable|date',
            'dining_time' => 'nullable|date_format:H:i:s',
            'meal' => 'nullable|string',
            'notes' => 'required|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();


        $diningNote->update($data);

        return redirect()
                ->back()
                ->with('success', 'Dining note updated successfully')
                ->with('activeTab', 'diningInformation');
    }

    public function toggleCancelDiningNote(ReservationDiningNote $diningNote)
    {
        $diningNote->update([
            'is_canceled' => $diningNote->is_canceled ? 0 : 1,
            'canceled_by' => auth()->id(),
            'canceled_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('reservations.reservationDetails', $diningNote->reservation_id)
                ->with('success', $diningNote->is_canceled ? 'Dining Note marked as uncanceled' : 'Dining Note marked as canceled')
                ->with('activeTab', 'diningInformation');
    }

    public function deleteDiningNote(ReservationDiningNote $diningNote)
    {
        $reservationId = $diningNote->reservation_id;

        $diningNote->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Dining Note deleted successfully')
                ->with('activeTab', 'diningInformation');
    }

    public function storeGift(Request $request, Reservation $reservation)
    {
        $messages = [
            'gift_type.required' => 'Gift Type is required.',
            'gift_date.required' => 'Gift Date is required.',
            'amount.required' => 'Amount is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'gift_date' => 'required|date',
            'gift_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string'
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                    ->route('reservations.reservationDetails', $reservation->id)
                    ->withErrors($validator, 'giftStore')
                    ->withInput()
                    ->with('activeTab', 'giftsInfo')
                    ->with('openGiftsModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationGift::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Gift added successfully')
                ->with('activeTab', 'giftsInfo');
    }

    public function updateGift(Request $request, ReservationGift $gift)
    {
        $validator = \Validator::make($request->all(), [
            'gift_date' => 'required|date',
            'gift_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $gift->update($data);

        return redirect()
                ->back()
                ->with('success', 'Gift updated successfully')
                ->with('activeTab', 'giftsInfo');
    }

    public function deleteGift(ReservationGift $gift)
    {
        $reservationId = $gift->reservation_id;

        $gift->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now()
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Gift deleted successfully')
                ->with('activeTab', 'giftsInfo');
    }

    public function storePhoneNote(Request $request, Reservation $reservation)
    {
        $messages = [
            'notes.required' => 'The Notes field is required.',
        ];

        $validator = \Validator::make($request->all(), [
            'category' => 'nullable|string',
            'caller_name' => 'nullable|string',
            'caller_phone_number' => 'nullable|string',
            'notes' => 'required|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->withErrors($validator, 'phoneNoteStore')
                ->withInput()
                ->with('activeTab', 'phoneNotes')
                ->with('openPhoneNotesModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationPhoneNote::create($data);

        return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->with('success', 'Phone Note added successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function updatePhoneNote(Request $request, ReservationPhoneNote $phoneNote)
    {

        $validator = \Validator::make($request->all(), [
            'category' => 'nullable|string',
            'caller_name' => 'nullable|string',
            'caller_phone_number' => 'nullable|string',
            'notes' => 'required|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $phoneNote->update($data);

        return redirect()
                ->back()
                ->with('success', 'Phone Note updated successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function toggleCancelPhoneNote(ReservationPhoneNote $phoneNote)
    {
        $canceled = !$phoneNote->is_canceled;

        $phoneNote->update([
            'is_canceled' => $canceled,
            'canceled_by' => auth()->id(),
            'canceled_on' => now(),
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_canceled' => $canceled,
            ]);
        }

        return redirect()
            ->route('reservations.reservationDetails', $phoneNote->reservation_id)
            ->with('success',$canceled ? 'Phone Note marked as canceled' : 'Phone Note marked as uncanceled')
            ->with('activeTab', 'phoneNotes');
    }

    public function deletePhoneNote(ReservationPhoneNote $phoneNote)
    {
        $reservationId = $phoneNote->reservation_id;

        $phoneNote->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Phone Note deleted successfully')
                ->with('activeTab', 'phoneNotes');
    }

    public function storeCommissionFee(Request $request, Reservation $reservation)
    {
        $messages = [
            'fee_type.required' => 'The Fee Type field is required.',
            'amount.required' => 'The Fee Amount field is required.'
        ];

        $validator = \Validator::make($request->all(), [
            'fee_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->route('reservations.reservationDetails', $reservation->id)
                ->withErrors($validator, 'commissionFeeStore')
                ->withInput()
                ->with('activeTab', 'agentPayments')
                ->with('openCommissionFeesModal', true);
        }

        $data = $validator->validated();

        $data['reservation_id'] = $reservation->id;
        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        ReservationCommissionFee::create($data);

        return redirect()
            ->route('reservations.reservationDetails', $reservation->id)
            ->with('success', 'Commission Fee added successfully')
            ->with('activeTab', 'agentPayments');
    }

    public function updateCommissionFee(Request $request, ReservationCommissionFee $commissionFee)
    {
        $validator = \Validator::make($request->all(), [
            'fee_type' => 'required|string',
            'amount' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $data = $validator->validated();

        $data['last_modified_by'] = auth()->id();
        $data['last_modified_on'] = now();

        $commissionFee->update($data);

        return redirect()
            ->back()
            ->with('success', 'Commission Fee updated successfully')
            ->with('activeTab', 'agentPayments');
    }

    public function deleteCommissionFee(ReservationCommissionFee $commissionFee)
    {
        $reservationId = $commissionFee->reservation_id;

        $commissionFee->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
                ->route('reservations.reservationDetails', $reservationId)
                ->with('success', 'Commission Fee deleted successfully')
                ->with('activeTab', 'agentPayments');
    }

    public function toggleIncludeTraveler(ReservationTraveler $traveler)
    {
        $traveler->update([
            'is_included' => $traveler->is_included ? 0 : 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('reservations.reservationDetails', $traveler->reservation_id)
                ->with('success', $traveler->is_included ? 'Traveler marked excluded' : 'Traveler marked included')
                ->with('activeTab', 'travelers');
    }

    public function getActiveReservations(Request $request, $customerId)
    {
        $currentId = $request->current_reservation_id;

        $linkedIds = ReservationLink::where('reservation_id', $currentId)->pluck('linked_reservation_id')->toArray();

        $reverseLinkedIds = ReservationLink::where('linked_reservation_id', $currentId)->pluck('reservation_id')->toArray();

        $allLinkedIds = array_merge($linkedIds, $reverseLinkedIds);

        $allLinkedIds[] = $currentId;

        $reservations = Reservation::where('customer_id', $customerId)
            ->where('status', 'Active')
            ->where('is_deleted', 0)
            ->whereNotIn('id', $allLinkedIds)
            ->select('id', 'reservation_number', 'reservation_name', 'checkin_date', 'checkout_date')
            ->get()
        ;

        return response()->json($reservations);
    }


    public function linkReservation(Request $request, Reservation $reservation)
    {
        $linkedId = $request->linked_reservation_id;

        if (!$linkedId || $linkedId == $reservation->id) {
            return response()->json(['message' => 'Invalid reservation'], 422);
        }

        $exists = ReservationLink::where(function ($q) use ($reservation, $linkedId) {
            $q->where('reservation_id', $reservation->id)
            ->where('linked_reservation_id', $linkedId);
        })->orWhere(function ($q) use ($reservation, $linkedId) {
            $q->where('reservation_id', $linkedId)
            ->where('linked_reservation_id', $reservation->id);
        })->exists();

        if ($exists) {
            return response()->json(['message' => 'Already linked'], 409);
        }

        ReservationLink::create([
            'reservation_id' => $reservation->id,
            'linked_reservation_id' => $linkedId,
            'is_linked' => 1,
            'created_by' => auth()->id(),
            'created_on' => now(),
        ]);

        ReservationLink::create([
            'reservation_id' => $linkedId,
            'linked_reservation_id' => $reservation->id,
            'is_linked' => 1,
            'created_by' => auth()->id(),
            'created_on' => now(),
        ]);

        return response()->json(['message' => 'Linked successfully']);
    }

    public function unlinkReservation(Request $request, Reservation $reservation)
    {
        $linkedId = $request->linked_reservation_id;

        if (!$linkedId) {
            return response()->json(['message' => 'Invalid reservation'], 422);
        }

        ReservationLink::where('reservation_id', $reservation->id)
            ->where('linked_reservation_id', $linkedId)
            ->update([
                'is_linked' => 0,
                'last_modified_by' => auth()->id(),
                'last_modified_on' => now()
            ]);

        ReservationLink::where('reservation_id', $linkedId)
            ->where('linked_reservation_id', $reservation->id)
            ->update([
                'is_linked' => 0,
                'last_modified_by' => auth()->id(),
                'last_modified_on' => now()
            ]);

        return response()->json(['message' => 'Unlinked successfully']);
    }

    public function sendForm(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer',
            'form_id' => 'required|integer',
        ]);

        $reservation = Reservation::with('customer')->findOrFail($request->reservation_id);

        $form = CustomersForm::findOrFail($request->form_id);

        $customer = $reservation->customer;

        if (!$customer) {
            return response()->json([
                'flag' => -1,
                'message' => 'Customer not found.'
            ]);
        }

        $email = $reservation->email_to_send ?: $customer->email;

        if (empty($email)) {
            return response()->json([
                'flag' => -1,
                'message' => 'Customer email is missing.'
            ]);
        }

        $sentForm = FormSent::create([
            'customer_id'    => null,
            'reservation_id' => $reservation->id,
            'form_id'        => $form->id,
            'sent_by'        => Auth::id(),
            'sent_on'        => now(),
        ]);

        $token = encrypt($sentForm->id);

        Mail::to($email)->send(
            new CustomerFormMail(
                $customer->fname,
                Auth::user()->fname . ' ' . Auth::user()->lname,
                $token
            )
        );

        return response()->json([
            'flag' => 1,
            'message' => 'Form sent successfully.'
        ]);
    }

    public function resendForm(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer',
            'sent_form_id'   => 'required|integer',
        ]);

        $sentForm = FormSent::with([
            'reservation.customer',
            'form'
        ])->find($request->sent_form_id);

        if (!$sentForm) {
            return response()->json([
                'flag' => -1,
                'message' => 'Form not found.'
            ]);
        }

        $reservation = $sentForm->reservation;

        if (!$reservation || !$reservation->customer) {
            return response()->json([
                'flag' => -1,
                'message' => 'Reservation or customer not found.'
            ]);
        }

        $customer = $reservation->customer;

        $email = $reservation->email_to_send ?: $customer->email;

        if (empty($email)) {
            return response()->json([
                'flag' => -1,
                'message' => 'Customer email is missing.'
            ]);
        }

        $token = encrypt($sentForm->id);

        Mail::to($email)->send(
            new CustomerFormMail(
                $customer->fname,
                Auth::user()->fname . ' ' . Auth::user()->lname,
                $token
            )
        );

        return response()->json([
            'flag' => 1,
            'message' => 'Successfully re-sent form to customer.'
        ]);
    }

    public function sendDetailsToCustomer(Request $request)
    {
        $request->validate([
            'reservations' => ['required','array']
        ]);

        $reservations = Reservation::with([
            'customer',
            'agent',
            'product',
            'destination',
            'resort',
            'travelers.familyMember',
            'diningNotes'
        ])
        ->whereIn('id',$request->reservations)
        ->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->customer->email)->send(new ReservationDetailsMail($reservation));
        }

        return response()->json([
            'success'=>true
        ]);
    }


    public function sendCreditCardForm(Reservation $reservation)
    {
        $reservation->load(['customer','agent']);

        $email = $reservation->email_to_send ?: $reservation->customer->email;

        if (!$email) {

            return response()->json([
                'success'=>false,
                'message'=>'Customer email not found.'
            ],422);

        }

        Mail::to($email)->send(new CreditCardAuthorizationMail($reservation));

        return response()->json([
            'success'=>true
        ]);
    }

    public function creditCardForm($token)
    {
        try {
            $reservationId = decrypt($token);
        } catch (DecryptException $e) {
            abort(404);
        }

        $reservation = Reservation::with([
            'customer',
            'agent',
            'product',
            'destination',
            'resort',
            'travelers.familyMember'
        ])
        ->where('is_deleted', 0)
        ->findOrFail($reservationId);

        return view('credit-card.credit-card-form', compact('reservation'));
    }

    public function resendAutomatedEmail(Reservation $reservation,CustomerAutomatedEmail $customerAutomatedEmail)
    {
        $customerAutomatedEmail = CustomerAutomatedEmail::with('automatedEmail')
            ->where('id', $customerAutomatedEmail->id)
            ->where('reservation_id', $reservation->id)
            ->where('customer_id', $reservation->customer_id)
            ->first();

        $automatedEmail = $customerAutomatedEmail->automatedEmail;

        if (!$customerAutomatedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Automated email record not found.'
            ], 404);
        }

        $automatedEmail = AutomatedEmail::with('attachments')->find($customerAutomatedEmail->automated_email_id);

        if (!$automatedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Automated email not found.'
            ], 404);
        }

        $customer = $reservation->customer;
        $agent = $reservation->agent;

        if (!$customer || !$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation information is incomplete.'
            ], 422);
        }

        $clientEmail = $reservation->email_to_send ?: $customer->email;

        if (!$clientEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Customer email not found.'
            ], 422);
        }

        $signature = "
            <br><br>
            <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                Thank you, please let me know if I can further assist you!
            </span>
            <br><br>

            <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                {$agent->fname} {$agent->lname}
            </span>
            <br>
        ";

        if (!empty($agent->phone_number)) {
            $signature .= "
                <span style='color:#FF6600;font-weight:bold;font-size:15px;'>
                    {$agent->phone_number}
                </span>
                <br>
            ";
        }

        $signature .= "
            <span>
                <a style='color:#3B3BFF;font-weight:bold;font-size:15px;' href='mailto:{$agent->email}'>
                    {$agent->email}
                </a>
            </span>

            <br><br>

            <span>
                <a style='color:#3B3BFF;font-weight:bold;font-size:15px;' href='https://gotravelux.com/'>
                    www.gotravelux.com
                </a>
            </span>

            <br><br>

            <span style='color:#006FC9;font-weight:bold;font-size:13px;'>
                I book everything from hotels, Disney, Universal Studios,
                All Inclusive resorts, all cruise lines and more!
            </span>
        ";

        $body = $automatedEmail->message . $signature;

        try {

            Mail::send([], [], function ($message) use (
                $clientEmail,
                $reservation,
                $agent,
                $automatedEmail,
                $body
            ) {

                $message->to($clientEmail);

                if (!empty($reservation->spouse_email) &&
                    filter_var($reservation->spouse_email, FILTER_VALIDATE_EMAIL)) {

                    $message->cc($reservation->spouse_email);
                }

                if ($automatedEmail->bcc_agent) {
                    $message->bcc($agent->email);
                }

                $message->subject($automatedEmail->subject);

                $message->html($body);

                foreach ($automatedEmail->attachments as $attachment) {

                    $path = public_path('attachments/automatedEmails/' . $attachment->id . '.' . $attachment->file_extension);

                    if (File::exists($path)) {

                        $message->attach(
                            $path,
                            [
                                'as' => $attachment->file_name . '.' . $attachment->file_extension
                            ]
                        );
                    }
                }
            });

            $customerAutomatedEmail->update([
                'last_resent_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email resent successfully.',
                'last_resent_date' => $customerAutomatedEmail->fresh()->last_resent_date ? Carbon::parse($customerAutomatedEmail->fresh()->last_resent_date)->format('m/d/Y') : null,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend email.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

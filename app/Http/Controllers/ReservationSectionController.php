<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationTask;
use App\Models\ReservationPayment;
use App\Models\ReservationDiningNote;
use App\Models\ReservationGift;
use App\Models\ReservationPhoneNote;
use App\Models\ReservationCommissionFee;
use App\Models\ReservationTraveler;
use App\Models\ReservationLink;
use App\Models\CustomerAutomatedEmail;
use App\Models\Customer;
use App\Models\CustomersForm;
use App\Models\FormSent;
use App\Models\ReservationAttachment;
use App\Models\ItineraryTrip;
use Illuminate\Support\Facades\Cache;

class ReservationSectionController extends Controller
{
    private function authorizeReservation(Reservation $reservation)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $reservation->agent_id != $user->id) {
            abort(403);
        }
    }

    public function tasks(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $timelineTasks = $reservation->tasks()
            ->with('agent')
            ->where('is_deleted', 0)
            ->where('is_timeline_task', 1)
        ->get();

        $generalTasks = $reservation->tasks()
            ->with('agent')
            ->where('is_deleted', 0)
            ->where('is_timeline_task', 0)
        ->get();

        $overdueTasksCount = ReservationTask::where('reservation_id', $reservation->id)->where('is_deleted', 0)->where('is_completed', 0)->whereDate('due_date', '<=', now())->count();

        return view('reservations.partials.tasks', [
            'isNewReservation' => false,
            'reservation' => $reservation,
            'timelineTasks' => $timelineTasks,
            'generalTasks' => $generalTasks,
            'overdueTasksCount' => $overdueTasksCount,
        ]);
    }

    public function payments(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.payments', [
            'isNewReservation' => false,
            'reservation' => $reservation,
        ]);
    }

    public function onBoardCredit(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.onBoardCredit', [
            'reservation' => $reservation,
        ]);
    }

    public function travelers(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.travelers', [
            'reservation' => $reservation,
        ]);
    }

    public function linkedReservations(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $linkedReservations = ReservationLink::where('reservation_id', $reservation->id)
            ->where('is_linked', 1)
            ->with('linkedReservation')
        ->get();

        $referralCustomers = Customer::where('agent_id', auth()->id())->where('is_deleted',0)->orderBy('lname')->get();    

        return view('reservations.partials.linkedReservations', [
            'reservation' => $reservation,
            'linkedReservations' => $linkedReservations,
            'referralCustomers' => $referralCustomers,
        ]);
    }

    public function forms(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $availableForms = CustomersForm::where('is_deleted', 0)
            ->where('is_active', 1)
            ->whereHas('customersFormRequired', function ($q) use ($reservation) {
                $q->where(function ($subQ) use ($reservation) {

                    $subQ->where('all_reservations_required', 1)

                        ->orWhere(function ($matchQ) use ($reservation) {
                            $matchQ->where('product_id', $reservation->product_id)
                                ->where('destination_id', $reservation->destination_id);
                        });

                });

                $q->where('is_deleted', 0);
            })
        ->get();

        $sentForms = FormSent::with('form')
            ->where('reservation_id', $reservation->id)
            ->orderByDesc('sent_on')
        ->get();

        return view('reservations.partials.forms', [
            'reservation' => $reservation,
            'availableForms' => $availableForms,
            'sentForms' => $sentForms,
        ]);
    }

    public function flightInfo(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.flightInfo', [
            'reservation' => $reservation,
        ]);
    }

    public function diningInformation(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.diningInformation', [
            'isNewReservation' => false,
            'reservation' => $reservation,
        ]);
    }

    public function giftsInfo(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.giftsInfo', [
            'isNewReservation' => false,
            'reservation' => $reservation,
        ]);
    }

    public function autoEmails(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $automatedEmails = $reservation->automatedEmails()
            ->orderByDesc('date')
            ->get();

        return view('reservations.partials.autoEmails', [
            'reservation' => $reservation,
            'automatedEmails' => $automatedEmails,
        ]);
    }

    public function notes(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.notes', [
            'reservation' => $reservation,
        ]);
    }

    public function phoneNotes(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        return view('reservations.partials.phoneNotes', [
            'isNewReservation' => false,
            'reservation' => $reservation,
        ]);
    }

    public function agentPayments(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $commissionFees = $reservation->commissionFees()->get();

        return view('reservations.partials.agentPayments', [
            'isNewReservation' => false,
            'reservation' => $reservation,
            'commissionFees' => $commissionFees,
        ]);
    }

    public function attachments(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $attachments = $reservation->attachments()->get();

        return view('reservations.partials.attachments', [
            'reservation' => $reservation,
            'attachments' => $attachments,
        ]);
    }

    public function itinerary(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $itineraryTrips = ItineraryTrip::select('id','name',)->where('is_deleted', 0)->where('created_by', auth()->id())->orderBy('date', 'desc')->get(); 

        return view('reservations.partials.selectItineraryTrip', [
            'reservation' => $reservation,
            'itineraryTrips' => $itineraryTrips,
        ]);
    }
}
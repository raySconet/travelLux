<?php

namespace App\Services;

use App\Models\AutomatedEmail;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use App\Models\CustomerAutomatedEmail;
use App\Models\User;
use App\Models\Reservation;

class AutomatedEmailService
{
    public function process()
    {
        $emails = AutomatedEmail::where('is_deleted',0)->where('is_disabled',0)->get();

        foreach ($emails as $email) {

            match ($email->email_type) {

                'Anniversary Email' => $this->sendAnniversaryEmails($email),
                'Upcoming Anniversary Email' => $this->sendUpcomingAnniversaryEmails($email),
                'Birthday Email' => $this->sendBirthdayEmails($email),
                'Upcoming Birthday Email' => $this->sendUpcomingBirthdayEmails($email),
                'New Year Email' => $this->sendNewYearEmails($email),
                'Reservation Reminder' => $this->sendReservationReminders($email),
                'Agents Birthday Email' => $this->sendAgentBirthdayEmails($email),

                default => null,
            };

        }
    }

    private function getDateToCheck(AutomatedEmail $email): \Carbon\Carbon
    {
        return $email->before_after === 'After' ? now()->subDays($email->days) : now()->addDays($email->days);
    }

    private function getAgentDisplayName($agent): string
    {
        return !empty($agent->nickname) ? $agent->nickname : "{$agent->fname} {$agent->lname}";
    }

    private function sendCustomerEmail(AutomatedEmail $automatedEmail, Customer $customer, $reservation = null)
    {
        $agent = $customer->agent;

        Mail::to($customer->email)
            ->when($automatedEmail->bcc_agent, function ($mail) use ($agent) {
                $mail->bcc($agent->email);
            })
            ->send(
                new \App\Mail\AutomatedCustomerMail(
                    subjectLine: $automatedEmail->subject,
                    messageBody: nl2br($automatedEmail->message),
                    agentName: $this->getAgentDisplayName($agent),
                    agentPhone: $agent->phone_number,
                    agentEmail: $agent->email,
                )
        );

        CustomerAutomatedEmail::create([
            'customer_id' => $customer->id,
            'reservation_id' => $reservation?->id,
            'automated_email_id' => $automatedEmail->id,
            'date' => now(),
        ]);
    }

    private function sendAgentEmail(AutomatedEmail $automatedEmail,User $agent)
    {
        Mail::to($agent->email)
            ->send(
                new \App\Mail\AutomatedAgentMail(
                    subjectLine: $automatedEmail->subject,
                    messageBody: nl2br($automatedEmail->message)
                )
            );
    }

    private function sendReservationEmail(AutomatedEmail $automatedEmail,Reservation $reservation,string $subject)
    {
        $agent = $reservation->agent;
        $customer = $reservation->customer;

        Mail::to($customer->email)
            ->when($automatedEmail->bcc_agent, function ($mail) use ($agent) {
                $mail->bcc($agent->email);
            })
            ->send(
                new \App\Mail\AutomatedCustomerMail(
                    subjectLine: $subject,
                    messageBody: nl2br($automatedEmail->message),
                    agentName: $this->getAgentDisplayName($agent),
                    agentPhone: $agent->phone_number,
                    agentEmail: $agent->email,
                )
            );

        CustomerAutomatedEmail::create([
            'customer_id' => $customer->id,
            'reservation_id' => $reservation->id,
            'automated_email_id' => $automatedEmail->id,
            'date' => now(),
        ]);
    }

    private function sendAnniversaryEmails(AutomatedEmail $email)
    {
        $date = $this->getDateToCheck($email)->format('m-d');

        $customers = Customer::with('agent')
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereRaw("DATE_FORMAT(anniversary_date,'%m-%d') = ?", [$date]);

        if ($email->agent_id != -1) {
            $customers->where('agent_id', $email->agent_id);
        }

        foreach ($customers->get() as $customer) {

            $agent = $customer->agent;

            if (!$agent) {
                continue;
            }

            $this->sendCustomerEmail(
                automatedEmail: $email,
                customer: $customer,
                reservation: null
            );
        }
    }

    private function sendUpcomingAnniversaryEmails(AutomatedEmail $email)
    {
        $date = $this->getDateToCheck($email)->format('m-d');

        $customers = Customer::with('agent')
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereRaw("DATE_FORMAT(anniversary_date,'%m-%d') = ?", [$date]);

        if ($email->agent_id != -1) {
            $customers->where('agent_id', $email->agent_id);
        }

        foreach ($customers->get() as $customer) {

            if (!$customer->agent) {
                continue;
            }

            $this->sendCustomerEmail($email, $customer);
        }
    }

    private function sendBirthdayEmails(AutomatedEmail $email)
    {
        $date = $this->getDateToCheck($email)->format('m-d');

        $customers = Customer::with('agent')
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereRaw("DATE_FORMAT(birth_date,'%m-%d') = ?", [$date]);

        if ($email->agent_id != -1) {
            $customers->where('agent_id', $email->agent_id);
        }

        foreach ($customers->get() as $customer) {

            if (!$customer->agent) {
                continue;
            }

            $this->sendCustomerEmail($email, $customer);
        }
    }

    private function sendUpcomingBirthdayEmails(AutomatedEmail $email)
    {
        $date = $this->getDateToCheck($email)->format('m-d');

        $customers = Customer::with('agent')
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereRaw("DATE_FORMAT(birth_date,'%m-%d') = ?", [$date]);

        if ($email->agent_id != -1) {
            $customers->where('agent_id', $email->agent_id);
        }

        foreach ($customers->get() as $customer) {

            if (!$customer->agent) {
                continue;
            }

            $this->sendCustomerEmail($email, $customer);
        }
    }

    private function sendNewYearEmails(AutomatedEmail $email)
    {
        if (!now()->isNewYear()) {
            return;
        }

        $customers = Customer::with('agent')
            ->where('is_deleted', 0)
            ->where('status', 'Active')
            ->whereNotNull('email')
            ->where('email', '<>', '');

        if ($email->agent_id != -1) {
            $customers->where('agent_id', $email->agent_id);
        }

        foreach ($customers->get() as $customer) {

            if (!$customer->agent) {
                continue;
            }

            $this->sendCustomerEmail($email, $customer);
        }
    }

    private function sendAgentBirthdayEmails(AutomatedEmail $email)
    {
        $date = $this->getDateToCheck($email)->format('m-d');

        $agents = User::where('isDeleted', 0)
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereRaw("DATE_FORMAT(birth_date,'%m-%d') = ?", [$date])
            ->get();

        foreach ($agents as $agent) {
            $this->sendAgentEmail($email, $agent);
        }
    }

    private function sendReservationReminders(AutomatedEmail $email)
    {
        $date = $this->getDateToCheck($email)->toDateString();

        if ($email->product_list == -1 && $email->destination_list == -1 && $email->resort_list == -1 && $email->cruise_itinerary_list == -1) {
            $this->sendReservationReminderWithoutFilters($email, $date);
        } else {
            $this->sendReservationReminderWithFilters($email, $date);
        }
    }

    private function sendReservationReminderWithoutFilters(AutomatedEmail $email,string $date)
    {
        $reservations = Reservation::with(['customer','agent'])
            ->where('is_deleted',0)
            ->whereIn('status',['Active','Paid in Full'])
            ->whereDate('checkin_date',$date);

        if ($email->agent_id != -1) {
            $reservations->where('agent_id',$email->agent_id);
        }

        foreach ($reservations->get() as $reservation) {

            if (!$reservation->customer) {
                continue;
            }

            if (!$reservation->agent) {
                continue;
            }

            if ($reservation->stop_auto_emails) {
                continue;
            }

            $subject = $email->subject;

            if (!empty($reservation->reservation_number)) {
                $subject .= " - Reservation #: {$reservation->reservation_number}";
            }

            $this->sendReservationEmail(
                automatedEmail: $email,
                reservation: $reservation,
                subject: $subject
            );
        }
    }

    private function sendReservationReminderWithFilters(AutomatedEmail $email,string $date)
    {
        $reservations = Reservation::with(['customer.agent'])
            ->where('is_deleted', 0)
            ->whereIn('status', ['Active', 'Paid in Full'])
            ->whereDate('checkin_date', $date);

        if ($email->agent_id != -1) {
            $reservations->where('agent_id', $email->agent_id);
        }

        if (!empty($email->cruise_itinerary_list) && $email->cruise_itinerary_list != '-1') {

            $reservations->whereIn('cruise_itinerary_id',explode(',', $email->cruise_itinerary_list));

        } elseif (!empty($email->resort_list) && $email->resort_list != '-1') {

            $reservations->whereIn('resort_id',explode(',', $email->resort_list));

        }elseif (!empty($email->destination_list) && $email->destination_list != '-1') {

            $reservations->whereIn('destination_id',explode(',', $email->destination_list));

        }elseif (!empty($email->product_list) && $email->product_list != '-1') {

            $reservations->whereIn('product_id',explode(',', $email->product_list));

        }

        foreach ($reservations->get() as $reservation) {

            if (!$reservation->customer || !$reservation->customer->agent || empty($reservation->customer->email) || $reservation->stop_auto_emails) {
                continue;
            }

            $subject = $email->subject;

            if (!empty($reservation->reservation_number)) {
                $subject .= " - Reservation #: {$reservation->reservation_number}";
            }

            $this->sendReservationEmail(
                automatedEmail: $email,
                reservation: $reservation,
                subject: $subject
            );
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\FormSent;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function show($token)
    {
        try {

            $sentFormId = decrypt($token);

        } catch (DecryptException $e) {

            abort(404);

        }

        $sentForm = FormSent::with('form')->find($sentFormId);

        if (!$sentForm) {
            abort(404);
        }

        if ($sentForm->submit_flag) {

            abort(
                403,
                'This link has expired, please contact your travel agent for a new link.'
            );

        }

        return view('forms.customerForm', [
            'sentForm' => $sentForm,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'previewFormHtml' => 'required',
        ]);

        try {

            $sentFormId = decrypt($request->token);

        } catch (\Exception $e) {

            return response()->json([
                'flag' => -1,
                'message' => 'Failed To Submit Form. (Error Code: 308)'
            ]);

        }

        try {

            DB::transaction(function () use ($request, $sentFormId) {

                $sentForm = FormSent::with([
                    'customer.agent',
                    'reservation.customer.agent',
                    'form'
                ])->findOrFail($sentFormId);

                if ($sentForm->submit_flag) {
                    throw new \Exception('This form has already been submitted.');
                }

                $sentForm->update([
                    'submitted_form_content' => $request->previewFormHtml,
                    'submit_flag' => 1,
                    'submitted_on' => now(),
                ]);


                $customer = $sentForm->customer;

                if (!$customer && $sentForm->reservation) {
                    $customer = $sentForm->reservation->customer;
                }

                Notification::create([
                    'user_id' => $sentForm->sent_by,
                    'module_name' => $sentForm->reservation_id ? 'reservations' : 'customers',
                    'record_id' => $sentForm->reservation_id ?: $customer->id,
                    'message' => "{$customer->fname} {$customer->lname} submitted {$sentForm->form->form_name}.",
                    'date' => now(),
                    'created_by' => -1,
                    'is_read' => 0,
                ]);

                if ($customer->agent?->email) {

                    Mail::html(
                        "
                        <div style='font-family: Arial, sans-serif;'>

                            Hello {$customer->agent->fname},

                            <br><br>

                            Your client {$customer->fname} {$customer->lname} has submitted the {$sentForm->form->form_name}.

                            <br><br>

                            Thank you,

                            <br>

                            Travelux

                        </div>
                        ",
                        function ($message) use ($customer, $sentForm) {

                            $message
                                ->to($customer->agent->email)
                                ->subject("Client Submitted {$sentForm->form->form_name}");

                        }
                    );

                }

            });

        } catch (\Exception $e) {

            return response()->json([
                'flag' => -1,
                'message' => $e->getMessage()
            ]);

        }

        return response()->json([
            'flag' => 1,
            'html' => '
                <div class="flex flex-col items-center justify-center mt-10">

                    <img src="' . asset('images/archer-logo.png') . '" class="w-[250px]" alt="Logo">

                    <div class="flex items-center justify-center gap-3 mt-8">
                        <i class="fa fa-check-circle text-green-500 text-5xl"></i>
                        <h3 class="text-xl">Thanks for submitting this form!</h3>
                    </div>

                </div>
            '
        ]);
    }
}
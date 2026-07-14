<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerIntakeForm;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use App\Mail\IntakeFormMail;

class IntakeFormController extends Controller
{
    public function show($token)
    {
        $decoded = base64_decode($token);

        $intakeFormId = str_replace(config('app.invitation_salt'),'',$decoded);

        if (!is_numeric($intakeFormId)) {
            abort(404);
        }

        $intakeForm = CustomerIntakeForm::find($intakeFormId);

        if (!$intakeForm) {
            abort(404);
        }

        if ($intakeForm->status === 'S' || $intakeForm->submit_flag) {
            abort(
                403,
                'This link has expired, please contact your travel agent for a new link.'
            );
        }

        $customer = Customer::find($intakeForm->customer_id);

        if (!$customer) {
            abort(404);
        }

        return view(
            'invitations.customerIntakeForm',
            [
                'customer' => $customer,
                'token' => $token,
                'intakeForm' => $intakeForm,
            ]
        );
    }

    public function submit(Request $request)
    {
        $request->validate([
            'encryption' => 'required',
            'intakeFormContentHTML' => 'required',

            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'passportsRadio' => 'required',
        ]);


        $decoded = base64_decode($request->encryption);

        $intakeFormId = str_replace(config('app.invitation_salt'),'',$decoded);

        if(!is_numeric($intakeFormId))
        {
            return response()->json([
                'flag'=>-1,
                'message'=>'Failed To Submit Trip Inquiry Form. (Error Code:404)'
            ]);
        }

        $intakeForm = CustomerIntakeForm::find($intakeFormId);

        if(!$intakeForm)
        {
            return response()->json([
                'flag'=>-1,
                'message'=>'Invalid Intake Form'
            ]);
        }


        $intakeForm->update([
            'submitted_form_content'=>$request->intakeFormContentHTML,
            'status'=>'S',
            'submit_flag'=>1,
            'submitted_on'=>now()
        ]);

        $customer = $intakeForm->customer;

        if ($customer) {

            $customer->load('agent');


            if ($customer->agent?->email) {

                Mail::html(
                    "
                    <div style='font-family: Arial, sans-serif;'>


                        Hello {$customer->agent->fname},

                        <br><br>

                        Your client {$customer->lname}, {$customer->fname} has filled out the trip inquiry form for Trip {$intakeForm->counter}.

                        <br><br>

                        Thank you,

                        <br>

                        Travelux

                    </div>
                    ",
                    function ($message) use ($customer) {

                        $message
                            ->to($customer->agent->email)
                            ->subject("Your client {$customer->lname}, {$customer->fname} has filled out the trip inquiry form!");

                    }
                );

            }

        }


        return response()->json([
            'flag'=>1,
            'html'=>'
                <div class="flex flex-col items-center justify-center mt-10">
                    <img  src="'.asset('images/archer-logo.png').'" class="w-[250px]" alt="Logo">
                    <div class="flex items-center justify-center gap-3 mt-8">
                        <i class="fa fa-check-circle text-green-500 text-5xl"></i>
                        <h3 class="text-xl">
                            Thanks for submitting this trip inquiry form!
                        </h3>
                    </div>

                </div>
            '

        ]);

    }
}

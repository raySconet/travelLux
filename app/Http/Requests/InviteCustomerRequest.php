<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'email' => 'required|email|max:255',

            'is_website_lead_knot' => 'boolean',
            'is_website_lead' => 'boolean',
            'is_virtuoso_lead' => 'boolean',
            'is_luxury_magazine_lead' => 'boolean',
            'is_facebook_lead' => 'boolean',
            'is_instagram_lead' => 'boolean',
            'is_radio_lead' => 'boolean',
        ];
    }
}
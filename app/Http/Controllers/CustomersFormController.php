<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomersForm;
use App\Models\CustomersFormRequired;

class CustomersFormController extends Controller
{
    public function index(Request $request)
    {
       $search = $request->input('search'); 
       $customerFormsQuery = CustomersForm::select('id','form_name', 'is_active')
                                    ->where('is_deleted',0);
        
        if($search){
            $customerFormsQuery->where('form_name', 'like', "%{$search}%");
        }
        
       $customerForms = $customerFormsQuery->get();

       return view('customersForm', compact('customerForms'));
    }

    public function create(CustomersForm $customerForm)
    {
        $customerForm = new CustomersForm();
        $isNewCustomersForm = true;

        return view('customers-form.edit', compact('customerForm','isNewCustomersForm'));
    }

    public function edit(CustomersForm $customerForm)
    {
        $isNewCustomersForm = false;
        $customerFormRequired = $customerForm->customersFormRequired;

        return view('customers-form.edit' , compact('customerForm','customerFormRequired','isNewCustomersForm'));
    }

    public function store(Request $request)
    {
        $messages = [
            'form_name.required' => 'Form Name Is Required.',
            'form_subject.required' => 'Form Subject Is Required.',
        ];

        $data = $request->validate([
            'form_name' => 'required|string|max:255',
            'form_subject' => 'required|string|max:255',
            'form_items_html_content' => 'nullable|string',
            'preview_form_html_content' => 'nullable|string',
            'is_active' => 'nullable|integer',
            'is_deleted' => 'nullable|integer'
        ], $messages);

        $data['created_by'] = auth()->id();
        $data['created_at'] = now();

        $form = CustomersForm::create($data);

        CustomersFormRequired::create([
            'form_id' => $form->id,
            'all_customers_required' => $request->all_customers_required ?? 0,
            'all_reservations_required' => $request->all_reservations_required ?? 0,
            'is_deleted' => 0
        ]);

        return redirect()
                ->route('customersForms')
                ->with('success','Customers Form created successfully');
    }

    public function update(Request $request, CustomersForm $customerForm)
    {
        $messages = [
            'form_name.required' => 'Form Name Is Required.',
            'form_subject.required' => 'Form Subject Is Required.'
        ];

        $data = $request->validate([
            'form_name' => 'required|string|max:255',
            'form_subject' => 'required|string|max:255',
            'form_items_html_content' => 'nullable|string',
            'preview_form_html_content' => 'nullable|string',
            'is_active' => 'nullable|integer',
            'is_deleted' => 'nullable|integer'
        ], $messages);

        $data['last_modified_by'] = auth()->id();
        $data['updated_at'] = now();

        $customerForm->update($data);

        CustomersFormRequired::updateOrCreate(
            ['form_id' => $customerForm->id],
            [
                'all_customers_required' => $request->all_customers_required ?? 0,
                'all_reservations_required' => $request->all_reservations_required ?? 0,
                'is_deleted' => 0
            ]
        );

        return redirect()
                ->route('customersForms')
                ->with('success', 'Customers Form updated successfully');
    }

    public function destroy(CustomersForm $customerForm)
    {
        $customerForm->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return redirect()
                ->route('customersForms')
                ->with('success', 'Customers Form deleted successfully');
    }

}

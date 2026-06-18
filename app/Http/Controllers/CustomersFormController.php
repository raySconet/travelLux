<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomersForm;
use App\Models\CustomersFormRequired;
use App\Models\Product;
use App\Models\Destination;

class CustomersFormController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }
    
    public function index(Request $request)
    {
       $this->checkAdmin();
       $search = $request->input('search'); 
       $customerFormsQuery = CustomersForm::select('id','form_name', 'is_active')
                                    ->where('is_deleted',0);
        
        if($search){
            $customerFormsQuery->where('form_name', 'like', "%{$search}%");
        }
        
       $customerForms = $customerFormsQuery->get();

       return view('customersForm', compact('customerForms'));
    }

    public function create()
    {
        $this->checkAdmin();
        $customerForm = new CustomersForm();
        $isNewCustomersForm = true;

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted',0)->get();

        return view('customers-form.edit', compact('customerForm','isNewCustomersForm','products','destinations'));
    }

    public function edit(CustomersForm $customerForm)
    {
        $this->checkAdmin();
        $isNewCustomersForm = false;

        $customerFormRequiredRows = $customerForm->customersFormRequired()->get();

        $customerFormRequired = $customerFormRequiredRows->first(); 

        $products = Product::orderBy('product_name')->where('is_deleted',0)->get();
        $destinations = Destination::orderBy('destination_name')->where('is_deleted',0)->get();

        return view('customers-form.edit', compact('customerForm','customerFormRequiredRows','customerFormRequired','isNewCustomersForm','products','destinations'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
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

        $form = CustomersForm::create($data);

        $productIds = $request->product_id ?? [];
        $destinationIds = $request->destination_id ?? [];

        $rows = min(count($productIds), count($destinationIds));

        for ($i = 0; $i < $rows; $i++) {

            if (!$productIds[$i] && !$destinationIds[$i]) {
                continue;
            }

            CustomersFormRequired::create([
                'form_id' => $form->id,
                'product_id' => $productIds[$i] ?? null,
                'destination_id' => $destinationIds[$i] ?? null,
                'all_customers_required' => $request->all_customers_required ?? 0,
                'all_reservations_required' => $request->all_reservations_required ?? 0,
                'is_deleted' => 0
            ]);
        }

        return redirect()
                ->route('customersForms')
                ->with('success','Customers Form created successfully');
    }

    public function update(Request $request, CustomersForm $customerForm)
    {
        $this->checkAdmin();
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

        $customerForm->update($data);

        $productIds = $request->product_id ?? [];
        $destinationIds = $request->destination_id ?? [];

        $existingRows = CustomersFormRequired::where('form_id', $customerForm->id)->where('is_deleted', 0)->get();

        $usedIds = [];

        $rows = min(count($productIds), count($destinationIds));

        for ($i = 0; $i < $rows; $i++) {

            if (!$productIds[$i] && !$destinationIds[$i]) {
                continue;
            }

            $existing = $existingRows[$i] ?? null;

            if ($existing) {

                $existing->update([
                    'product_id' => $productIds[$i] ?? null,
                    'destination_id' => $destinationIds[$i] ?? null,
                    'all_customers_required' => $request->all_customers_required ?? 0,
                    'all_reservations_required' => $request->all_reservations_required ?? 0,
                    'is_deleted' => 0
                ]);

                $usedIds[] = $existing->id;

            } else {

                $newRow = CustomersFormRequired::create([
                    'form_id' => $customerForm->id,
                    'product_id' => $productIds[$i] ?? null,
                    'destination_id' => $destinationIds[$i] ?? null,
                    'all_customers_required' => $request->all_customers_required ?? 0,
                    'all_reservations_required' => $request->all_reservations_required ?? 0,
                    'is_deleted' => 0
                ]);

                $usedIds[] = $newRow->id;
            }
        }
      
        CustomersFormRequired::where('form_id', $customerForm->id)->whereNotIn('id', $usedIds)->update(['is_deleted' => 1]);

        return redirect()
                ->route('customersForm.edit', $customerForm->id)
                ->with('success', 'Customers Form updated successfully')
                ->with('activeTab', $request->input('activeTab', 'general'));
    }

    public function destroy(CustomersForm $customerForm)
    {
        $this->checkAdmin();
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

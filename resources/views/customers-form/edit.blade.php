<form method="POST"
      action="{{ $isNewCustomersForm 
            ? route('customersForm.store')
            : route('customersForm.update', $customerForm->id) }}">  
    @csrf
    @if(!$isNewCustomersForm)
        @method('PUT')
    @endif            
    <x-app-layout>
        <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-server mr-2 text-[#f18325]"></i>{{ __('Customers Forms') }}
                </h2>

                @if($isNewCustomersForm)
                    <div class="space-x-2">
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('customersForms') }}'"><i class="far fa-minus-square"></i><span>Close</span></x-primary-btn>
                    </div>
                @else
                    <div class="space-x-2">
                        <form method="POST" action="{{ route('customersForm.destroy', $customerForm->id)}}" class="inline delete-form">
                            @csrf
                            @method('DELETE')

                            <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(this)"><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                        </form>
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('customersForms') }}'"><i class="far fa-minus-square"></i><span>Close</span></x-primary-btn>
                    </div>
                @endif    
            </div>
        </x-slot>

        <div>
            <div class="bg-white shadow rounded-none p-2 ml-4 mr-3" x-data="{ section: 'general' }">
                <div class="topButtonsGroup">
                    <div class="btn-group systemUsersNav" role="group">
                        <button type="button"  class="systemUsersSectionBtn" :class="{ 'active': section === 'general' }" @click="section = 'general'">
                            <span style="font-size:15px;">General Info</span>
                        </button>
                        <button type="button"  class="systemUsersSectionBtn" :class="{ ' active': section === 'formContent' }" @click="section = 'formContent'">
                            <span style="font-size:15px;">Form Content</span> 
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <div x-show="section === 'general'" x-cloak>
                        @include('customers-form.partials.general-info')
                    </div>

                    <div x-show="section === 'formContent'" x-cloak>
                        @include('customers-form.partials.form-content')
                    </div>

                </div>
            </div>
        </div>
    </x-app-layout>
</form>
<x-delete-modal />
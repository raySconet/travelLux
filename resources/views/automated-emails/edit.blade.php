<form method="POST"
      action="{{ $isNewAutomatedEmail
             ? route('automatedEmails.store')
             : route('automatedEmails.update', $automatedEmail->id ) }}">
    @csrf
    @if(!$isNewAutomatedEmail)
        @method('PUT')
    @endif               
    <x-app-layout>
        <x-slot name="header">
            <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-envelope mr-2 text-[#f18325]"></i>{{ __('Automated Emails') }}
                </h2>

                @if($isNewAutomatedEmail)
                    <div class="space-x-2">
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('automatedEmails') }}'"><i class="far fa-minus-square"></i><span>Close Email</span></x-primary-btn>
                    </div>
                @else    
                    <div class="space-x-2">
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('automatedEmails.destroy', $automatedEmail->id)}}" class="inline delete-form">
                                @csrf
                                @method('DELETE')

                                <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(this)"><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                            </form> 
                        @endif    
                        <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save</span></x-secondary-btn>
                        <x-primary-btn type="button" onclick="window.location='{{ route('automatedEmails') }}'"><i class="far fa-minus-square"></i><span>Close Email</span></x-primary-btn>
                    </div>
                @endif    
            </div>
        </x-slot>

        <div class="p-2 grid grid-cols-1 gap-6 ml-2 mr-1">
            <div class="bg-white shadow rounded-lg p-6">
                @include('automated-emails.partials.emails')
            </div>
        </div>
    </x-app-layout>
</form>
<x-delete-modal />
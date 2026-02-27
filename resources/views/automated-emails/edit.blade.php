<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-envelope mr-2 text-[#f18325]"></i>{{ __('Automated Emails') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete</span></x-secondary-buttonToDelete>
                    <x-secondary-btn><i class="fas fa-save"></i><span>Save Email</span></x-secondary-btn>
                    <x-primary-btn onclick="window.location='{{ route('automatedEmails') }}'"><i class="far fa-minus-square"></i><span>Close Email</span></x-primary-btn>
                </div>
        </div>
    </x-slot>

    <div class="p-2 grid grid-cols-1 gap-6 ml-2 mr-1">
        <div class="bg-white shadow rounded-none p-6">
            @include('automated-emails.partials.emails')
        </div>
    </div>
</x-app-layout>
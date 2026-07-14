<x-invitation-layout>
    <div class="max-w-5xl mx-auto mt-10 bg-white shadow rounded p-10">
        <form id="customerForm">

            @csrf

            <input type="hidden" id="token" value="{{ request()->route('token') }}">

            <div id="formItemsParentContainer">
                {!! $sentForm->form->preview_form_html_content !!}
            </div>

            <div class="flex justify-end mt-8">
                <x-primary-btn type="submit">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit
                </x-primary-btn>
            </div>

        </form>
    </div>
</x-invitation-layout>
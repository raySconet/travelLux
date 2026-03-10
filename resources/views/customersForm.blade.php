<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-server mr-2 text-[#f18325]"></i>{{ __('Customers Forms') }}
            </h2>

            <x-primary-btn class="flex items-center gap-2" onclick="window.location='{{ route('customersForm.create') }}'"><i class="far fa-plus-square"></i>Add</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2 mr-2 px-2">
           
            <form method="GET" action="{{ route('customersForms')}}" class="flex items-end justify-end px-6 py-4">

                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Quick Search"
                        class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                        oninput="clearTimeout(this.delay); this.delay=setTimeout(()=>this.form.submit(),500)"
                    >
                </div>
            </form>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6] font-extrabold">
                                Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6] font-extrabold">
                                Active
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @forelse ($customerForms as $customerForm)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('customersForm.edit', $customerForm->id) }}'">
                                <td class="w-3/4 px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}"">
                                    {{ $customerForm->form_name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}"">
                                    @if($customerForm->is_active == 1)
                                        <i class="fas fa-circle text-green-500 ml-3"></i>
                                    @else
                                        <i class="fas fa-circle text-red-500 ml-3"></i>
                                    @endif        
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-2 text-center">
                                    No data available in table
                                </td>
                            </tr>
                        @endforelse    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

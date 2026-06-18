<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-tag mr-2 text-[#B6844A]"></i>{{ __('Reservations') }}
            </h2>

            <div class="space-x-2">
                @if(auth()->user()->isAdmin())
                    <x-secondary-buttonToDelete type="button" onclick="handleBulkDelete()">
                        <i class="fas fa-trash"></i>
                        <span>Delete Reservations</span>
                    </x-secondary-buttonToDelete>
                @endif
                <x-primary-btn type="button" onclick="showLoaderOnSubmit(); window.location='{{ route('reservations.create') }}'"><i class="far fa-plus-square"></i><span>Add Reservations</span></x-primary-btn>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
           
            <form method="GET" action="{{ route('reservations.reservationList')}}" class="flex items-end justify-end px-6 py-4 gap-4"  onchange="this.form.submit()">
                @php
                    $selectedStatuses = request()->input('status', ['Active']);
                @endphp

                <div class="w-90 relative" x-data="{
                    open: false,
                    selected: {{ json_encode($selectedStatuses) }},
                    options: ['Active','Canceled - Commission Protected','Canceled w/ Insurance Payout','Canceled','Duplicate','Paid in Full Paid by Archer','Paid in Full Not Paid by Archer','Claimed','On Hold','Prospect'],
                    toggle(val) {
                        if (this.selected.includes(val)) {
                            this.selected = this.selected.filter(v => v !== val);
                        } else {
                            this.selected.push(val);
                        }
                    },
                    label() {
                        return this.selected.length ? this.selected.join(', ') : '-- Filter by Status --';
                    }
                    }" x-cloak>
                    <template x-for="s in selected" :key="s">
                        <input type="hidden" name="status[]" :value="s">
                    </template>
                    <button type="button" @click="open = !open" @click.outside="open = false" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 bg-white text-left flex justify-between items-center">
                        <span x-text="label()" class="text-gray-600 truncate"></span>
                        <svg class="w-4 h-4 text-gray-400 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg">
                        <template x-for="opt in options" :key="opt">
                            <div @click="toggle(opt); showLoaderOnSubmit(); $nextTick(() => $el.closest('form').submit())" class="flex items-center justify-between px-3 py-2 text-sm cursor-pointer hover:bg-gray-100">
                                <span x-text="opt"></span>
                                <svg x-show="selected.includes(opt)" class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                </div>

                @if(auth()->user()->isAdmin())
                    <select name="users" id="users" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"  onchange="showLoaderOnSubmit();this.form.submit()">
                        <option value="-1" {{ $agentId == -1 ? 'selected' : ''}}>All Agents</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $agentId == $user->id ? 'selected' : ''}}>
                                {{ $user->fname . ' ' . $user->lname }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <input
                    type="text"
                    name="search"
                    value="{{ request('search')}}"
                    placeholder="Quick Search"
                    class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                    oninput="clearTimeout(this.delay); this.delay=setTimeout(() => this.form.submit(), 500)"
                >
            </form>

           <div class="flex justify-end px-6">
                <button
                    type="button"
                    class="exportExcelBtn bg-[#e0e0e0] text-black px-2 py-1 rounded-none border-1 border-[#666]" style="background-image: linear-gradient(to bottom, #f9f9f9 0%, #e0e0e0 100%);">
                    Excel
                </button>
            </div>

            <div class="overflow-x-auto mt-3 px-2">
                <form id="bulkDeleteForm" method="POST" action="{{ route('reservations.bulkDelete') }}">
                    @csrf
                    @method('DELETE')
                    <table class="min-w-full text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    <i class="fas fa-paper-plane text-[#B6844A] text-base cursor-pointer" title="Send Details to Customer"></i>
                                </th>    
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Date Created
                                </th> 
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Reservation Number
                                </th> 
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Reservation Name
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Customer
                                </th>    
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Agent
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Product
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Destination
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Checkin Date
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                    Final Payment
                                </th>                
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse ($reservations as $reservation)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="showLoaderOnSubmit();window.location='{{ route('reservations.reservationDetails', $reservation->id) }}'">
                                    <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        <input type="checkbox" name="selected_reservations[]" value="{{ $reservation->id }}" class="reservation-checkbox" onclick="event.stopPropagation();">
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->status}}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->created_on ? \Carbon\Carbon::parse($reservation->created_on)->format('m/d/Y') : ' ' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->reservation_number}}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->reservation_name}}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->customer ? $reservation->customer->fname . ',' . $reservation->customer->lname : ' ' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->agent ? $reservation->agent->fname . ' ' . $reservation->agent->lname : ' ' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->product ? $reservation->product->product_name : ' ' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->destination ? $reservation->destination->destination_name : ' ' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') : ' ' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $reservation->final_payment_due_date ? \Carbon\Carbon::parse($reservation->final_payment_due_date)->format('m/d/y') : ' ' }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-2 text-center">
                                        No data available in table
                                    </td>
                                </tr>
                            @endforelse    
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-reservation-attentionModal />
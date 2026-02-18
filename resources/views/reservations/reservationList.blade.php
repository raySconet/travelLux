<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-tag mr-2 text-[#f18325]"></i>{{ __('Reservations') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete Reservations</span></x-secondary-buttonToDelete>
                    <x-primary-btn onclick="window.location='{{ route('reservations.create') }}'"><i class="far fa-plus-square"></i><span>Add Reservations</span></x-primary-btn>
                </div>
        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-1">
           
            <form method="GET" action="{{ route('reservations.reservationList')}}" class="flex items-end justify-end px-6 py-4 gap-4"  onchange="this.form.submit()">
                <select name="status" id="status" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1" onchange="this.form.submit()">
                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Canceled - Commission Protected" {{ $status == 'Canceled - Commission Protected' ? 'selected' : '' }}>Canceled - Commission Protected</option>
                    <option value="Canceled w/ Insurance Payout" {{ $status == 'Canceled w/ Insurance Payout' ? 'selected' : '' }}>Canceled w/ Insurance Payout</option>
                    <option value="Canceled" {{ $status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="Duplicate" {{ $status == 'Duplicate' ? 'selected' : '' }}>Duplicate</option>
                    <option value="Paid in Full" {{ $status == 'Paid in Full' ? 'selected' : '' }}>Paid in Full by Archer</option>
                    <option value="Paid in Full" {{ $status == 'Paid in Full' ? 'selected' : '' }}>Paid in Full Not Paid by Archer</option>
                    <option value="Claimed" {{ $status == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                    <option value="On Hold" {{ $status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="Prospect" {{ $status == 'Prospect' ? 'selected' : '' }}>Prospect</option>
                </select>

                <select name="users" id="users" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"  onchange="this.form.submit()">
                    <option value="-1" {{ $agentId == -1 ? 'selected' : ''}}>All Agents</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $agentId == $user->id ? 'selected' : ''}}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <input
                    type="text"
                    placeholder="Quick Search"
                    class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"
                >
            </form>

           
            <div class="overflow-x-auto mt-7 px-2">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                <i class="fas fa-paper-plane text-[#f18325] text-base"></i>
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
                        @foreach ($reservations as $reservation)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('reservations.reservationDetails', $reservation->id) }}'">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    <input type="checkbox">
                                </td>    

                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->status}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6] border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->created_on}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->reservation_number}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->reservation_name}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">

                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->agent ? $reservation->agent->name : '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->checkin_date}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $reservation->final_payment_due_date }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

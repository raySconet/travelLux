<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                <h2 class=" text-2xl text-gray-500 leading-tight">
                    <i class="fa-solid fa-tag mr-2 text-[#f18325]"></i>{{ __('Reservations') }}
                </h2>

                <div class="space-x-2">
                    <x-secondary-buttonToDelete><i class="fas fa-trash"></i><span>Delete Reservations</span></x-secondary-buttonToDelete>
                    <x-primary-btn><i class="far fa-plus-square"></i><span>Add Reservations</span></x-primary-btn>
                </div>
        </div>
    </x-slot>

    <div class="p-6">
       
        <div class="bg-white shadow rounded-lg">
           
            <div class="flex items-end justify-end px-6 py-4 gap-2">
                <select name="status" id="status" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                    <option value="Active">Active</option>
                    <option value="canceledComissionProtected">Canceled-Commission Protected</option>
                    <option value="canceledWithInsurancePayout">Canceled w/ Insurance Payout</option>
                    <option value="canceled">Canceled</option>
                    <option value="duplicate">Duplicate</option>
                    <option value="paidInFullByArcher">Paid in Full Paid by Archer</option>
                    <option value="paidInFullNotByArcher">Paid in Full Not Paid by Archer</option>
                    <option value="claimed">Claimed</option>
                    <option value="onHold">On Hold</option>
                    <option vallue="prospect">Prospect</option>
                </select>

                
                <select name="agents" id="agents" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                    <option value="-1">All Agents</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <input
                    type="text"
                    placeholder="Quick Search"
                    class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"
                >
            </div>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
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
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('reservations.reservationDetails', $user->id) }}'">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                         
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6] border-b-2 border-t-2 border-[#dee2e6]">
                                 
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

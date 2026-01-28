<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-list mr-2 text-[#f18325]"></i>{{ __('Customers') }}
            </h2>
            
            <x-primary-btn class="flex items-center gap-2"><i class="far fa-plus-square"></i>Add Customer</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-6">
       
        <div class="bg-white shadow rounded-lg">
           
            <div class="flex items-end justify-between px-6 py-4">
                <select name="CustomerTag" id="customerTag" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                    <option value="-1">--Filter by Customer Tags --</option>
                </select>

                <select name="status" id="status" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Invited">Invited</option>
                    <option value="Paused">Paused</option>
                    <option value="Prospect">Prospect</option>
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
                                Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Cell Phone
                            </th> 
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Email
                            </th> 
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Agent name
                            </th>    
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('customers.customerDetails', $user->id) }}'">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6] border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                              
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

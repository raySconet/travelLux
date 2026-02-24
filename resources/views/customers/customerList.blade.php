<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-list mr-2 text-[#f18325]"></i>{{ __('Customers') }}
            </h2>
            
            <x-primary-btn class="flex items-center gap-2"  onclick="window.location='{{ route('customers.create') }}'"><i class="far fa-plus-square"></i>Add Customer</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-1">
           
            <form method="GET" action="{{ route('customers.customerList') }}" class="flex items-end justify-between px-6 py-4 ">
                <select name="CustomerTag" id="customerTag" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                    <option value="-1">--Filter by Customer Tags --</option>
                </select>

                <select name="status" id="status" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1" onchange="this.form.submit()">
                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Invited" {{ $status == 'Invited' ? 'selected' : '' }}>Invited</option>
                    <option value="Paused" {{ $status == 'Paused' ? 'selected' : '' }}>Paused</option>
                    <option value="Prospect" {{ $status == 'Prospect' ? 'selected' : '' }}>Prospect</option>
                </select>

                
                <select name="users" id="agents" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1" onchange="this.form.submit()">
                    <option value="-1" {{ $agentId == -1 ? 'selected' : '' }}>All Agents</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $agentId == $user->id ? 'selected' : '' }}>
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

           <div class="flex justify-end px-6">
                <button
                    type="button"
                    class="exportExcelBtn bg-[#e0e0e0] text-black px-2 py-1 rounded-none border-1 border-[#666]" style="background-image: linear-gradient(to bottom, #f9f9f9 0%, #e0e0e0 100%);">
                    Excel
                </button>
            </div>

            <div class="overflow-x-auto mt-3 px-6">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Name</b>
                            </th>
                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Cell Phone</b>
                            </th> 
                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Email</b>
                            </th> 
                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Status</b>
                            </th>
                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Agent name</b>
                            </th>    
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('customers.customerDetails', $customer->id) }}'">
                                <td class="px-4 py-3 text-[13px] font-normal text-[#212529] border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $customer->lname }}, {{$customer->fname}} {{$customer->mname}}
                                </td>

                                <td class="px-4 py-3 text-[#212529]  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $customer->cellphone }}
                                </td>

                                <td class="px-4 py-3 text-[#212529]  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $customer->email}}
                                </td>

                                <td class="px-4 py-3 text-[#212529]  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{$customer->status}}
                                </td>

                                <td class="px-4 py-3 text-[#212529]  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $customer->agent ? $customer->agent->name : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-user-circle mr-2 text-[#f18325]"></i>{{ __('Agents Report') }}
            </h2>
            
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                First Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Last Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Home Address
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Email
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Cellphone Number
                            </th>  
                            <th class="px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Commission %
                            </th> 
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->fname }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6] border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->lname }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->city . '-' . $user->state . '-' . $user->first_address_line1 }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->cell_phone_number }}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-b-2 border-t-2 border-[#dee2e6]">
                                    {{ $user->commission }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

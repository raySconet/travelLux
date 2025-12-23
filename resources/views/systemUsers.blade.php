<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('System Users') }}
            </h2>

            <x-primary-btn class="space-x-2"><i class="far fa-plus-square"></i><span>Add User</span></x-primary-btn>
        </div>
    </x-slot>

    <div class="p-6">
       
        <div class="bg-white shadow rounded-lg">
           
            <div class="flex items-end justify-end px-6 py-4 border-b">

                <div class="relative">
                    <input
                        type="text"
                        placeholder="Quick Search"
                        class="w-64 border-0 border-b-2 border-gray-400 text-sm px-1 py-1"
                    >
                </div>
            </div>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                UserName
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('system-users.edit', $user->id) }}'">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $user->email }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

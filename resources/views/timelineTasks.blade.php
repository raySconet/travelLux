<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-clock mr-2 text-[#f18325]"></i>{{ __('Timeline Tasks') }}
            </h2>

            <x-primary-btn  class="flex items-center gap-2" onclick="window.location='{{ route('timeline-tasks.create') }}'"><i class="far fa-plus-square"></i>Add Task</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2">
           
            <div class="flex items-end justify-end px-6 py-4">

                <div class="relative flex gap-6">
                    <select name="filterByProduct" id="filterByProduct" class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 text-[#495057]">
                        <option value="-1">--Filter By Product--</option>
                    </select>
                    <select name="filterByDestination" id="filterByDestination" class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 text-[#495057]">
                        <option value="-1">--Filter by Destination--</option>
                    </select>
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
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                Product
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                Destination
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                Task Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                Priority
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                Days
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                       border-b-2 border-t-2 border-[#dee2e6]">
                                DateType
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('timeline-tasks.edit', $user->id) }}'">
                                <td class="px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->name}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->email}}
                                </td>

                                <td class="px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                </td>   

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                </td>
                                
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                </td>
                                
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                </td>   

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

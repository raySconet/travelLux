<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-plane mr-2 text-[#f18325]"></i>{{ __('Itinerary') }}
            </h2>

            <x-primary-btn  class="flex items-center gap-2"><i class="far fa-plus-square"></i>Add Trip</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2 mr-1 px-2">
           
            <div class="flex items-end justify-end gap-4 px-6 py-4">
                <div class="flex flex-col gap-1">
                    <label for="agents" class="text-sm">
                        Select Agent
                    </label>
                    <select name="agents" id="agents" class="w-85 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                        <option value="-1">All Agents</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $agentId == $user->id ? 'selected' : ''}}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="text" placeholder="Quick Search" class="w-85 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none">
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/3 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Name
                            </th>
                            <th class="w-1/3 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Date
                            </th>
                            <th class="w-1/3 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Create by
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('itinerary.edit', $user->id) }}'">
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->name}}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->email}}
                                </td>

                                <td class="px-4 py-3 te border-t-2xt-gray-600 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">

                                </td>    
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

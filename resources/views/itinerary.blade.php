<x-app-layout>
    <x-slot name="header">
        <div class="p-4 px-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-plane mr-2 text-[#B6844A]"></i>{{ __('Itinerary') }}
            </h2>

            <x-primary-btn class="flex items-center gap-2" onclick="window.location='{{ route('itinerary.create') }}'">
                <i class="far fa-plus-square"></i>
                Add Trip
            </x-primary-btn>
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="py-3 bg-white shadow rounded-none px-2">
           
            <div class="flex items-end justify-end gap-4 px-6 py-4">
                <div class="flex flex-col gap-1">
                    <label for="agents" class="text-sm">
                        Select Agent
                    </label>
                    <select name="agents" id="agents" onchange="window.location='?agents=' + this.value" class="w-85 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">

                        <option value="-1">All Agents</option>

                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $agentId == $user->id ? 'selected' : '' }}>
                                {{ $user->fname . ' ' . $user->lname }}
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
                        @foreach ($itineraries as $itinerary)
                            <tr class="hover:bg-gray-50 cursor-pointer"
                                onclick="window.location='{{ route('itinerary.edit', $itinerary->id) }}'">

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $itinerary->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $itinerary->date ? \Carbon\Carbon::parse($itinerary->date)->format('m/d/Y') : ' ' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $itinerary->creator?->fname . ' ' . $itinerary->creator?->lname }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

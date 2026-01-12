<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-envelope mr-2 text-[#f18325]"></i>{{ __('Automated  Emails') }}
            </h2>

            <x-primary-btn  class="flex items-center gap-2"><i class="far fa-plus-square"></i>Add Email</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-6">
       
        <div class="bg-white shadow rounded-lg">
           
            <div class="flex items-end justify-end gap-4 px-6 py-4 border-b">
                <div class="flex flex-col gap-1">
                    <label for="agents" class="text-sm">
                        Select Agent
                    </label>
                    <select
                        name="agents"
                        id="agents"
                        class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"
                    >
                        <option value="-1">All Agents</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input
                    type="text"
                    placeholder="Quick Search"
                    class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1"
                >
            </div>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                Action
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                Destination
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                Subject
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                Days
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600
                                    border-b-2 border-black">
                                Message
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('automated-emails.edit', $user->id) }}'">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $user->name}}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $user->email}}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

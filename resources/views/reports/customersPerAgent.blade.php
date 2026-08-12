<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fas fa-users mr-2 text-[#B6844A]"></i>{{ __('Customers Per Agent Report') }}
            </h2>
            
        </div>
    </x-slot>

    <div class="p-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Agent Name
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            Active
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            Inactive
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            Invited
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            Paused
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            Prospect
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($agents as $agent)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                {{ $agent->agentName }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ number_format($agent->active) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ number_format($agent->inactive) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ number_format($agent->invited) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ number_format($agent->paused) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ number_format($agent->prospect) }}
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>
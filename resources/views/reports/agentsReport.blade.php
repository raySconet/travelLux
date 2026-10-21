<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class="text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-user-circle mr-2 text-[#B6844A]"></i>{{ __('Agents Report') }}
            </h2>
            <div class="flex items-center gap-3">
                <button type="button" data-report-print class="flex items-center gap-2 bg-white border py-2 px-4 border-[#B6844A] text-[#B6844A] cursor-pointer">
                    <i class="fas fa-cloud-download-alt" aria-hidden="true"></i>Print
                </button>
                <a href="{{ route('reports.agentsReport', ['export' => 'excel']) }}" class="flex items-center gap-2 bg-white border py-2 px-4 border-[#B6844A] text-[#B6844A] cursor-pointer">
                    <i class="far fa-file-excel" aria-hidden="true"></i>Download
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div id="reportDataContainer" class="p-3 bg-white shadow sm:rounded-lg">
            <h2 class="hidden print:block text-xl mb-4">Agents Report</h2>
            <div class="overflow-x-auto">
                <table id="agentsReportTable" class="min-w-full text-sm">
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
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->fname }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->lname }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e5]' }}">
                                    {{ $user->city . ' - ' . $user->state . ' - ' . $user->first_address_line }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->cell_phone_number }}
                                </td>

                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No Data Available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @vite('resources/js/dataReports.js')
</x-app-layout>

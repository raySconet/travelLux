<x-app-layout>
    <x-slot name="header">
        <div class="p-4 bg-white shadow sm:rounded-none flex items-center justify-between">
            <h2 class=" text-2xl text-gray-500 leading-tight">
                <i class="fa-solid fa-envelope mr-2 text-[#f18325]"></i>{{ __('Automated  Emails') }}
            </h2>

            <x-primary-btn  class="flex items-center gap-2" onclick="window.location='{{ route('automatedEmails.create') }}'"><i class="far fa-plus-square"></i>Add Email</x-primary-btn>
        </div>
    </x-slot>

    <div class="p-2">
       
        <div class="bg-white shadow rounded-none ml-2 mr-1 px-2">
            <form method="GET" action="{{ route('automatedEmails') }}">
                <div class="flex items-end justify-end gap-4 px-6 py-4">
                    
                    <div class="flex flex-col gap-1">
                        <label for="agent_id" class="text-sm">Select Agent</label>
                        <select name="agent_id" id="agent_id" onchange="this.form.submit()" class="w-80 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none">
                            <option value="-1">All Agents</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('agent_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->fname . ' ' . $user->lname }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Quick Search"
                        class="w-80 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                        oninput="clearTimeout(this.delay); this.delay=setTimeout(()=>this.form.submit(),500)"
                    >
                </div>
            </form>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/16 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Action
                            </th>
                            <th class="w-2/16 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Subject
                            </th>
                            <th class="w-1/16 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Days
                            </th>
                            <th class="w-11/16 px-4 py-3 text-left text-sm font-extrabold border-b-2 border-t-2 border-[#dee2e6]">
                                Message
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @forelse ($automatedEmails as $automatedEmail)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('automatedEmails.edit', $automatedEmail->id) }}'">
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
    
                                    <form method="POST" action="{{ route('automatedEmails.toggle', $automatedEmail->id) }}" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" title="{{ $automatedEmail->is_disabled == 0 ? 'Disable Email' : 'Enable Email' }}">
                                            @if($automatedEmail->is_disabled == 0)
                                                <i class="fas fa-bell text-2xl text-[#f18325]"></i>
                                            @else
                                                <i class="fas fa-bell-slash text-2xl text-[#bdbdbd]"></i>
                                            @endif
                                        </button>
                                    </form>

                                </td>
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}"">
                                    {{ $automatedEmail->subject }}
                                </td>
                                
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}"">
                                    {{ $automatedEmail->days . ' ' . $automatedEmail->before_after }}
                                </td>   

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}"">
                                    {!! nl2br(e($automatedEmail->message)) !!}
                                </td>    
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-2 text-center">
                                    No data available in table
                                </td>
                            </tr>
                        @endforelse    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

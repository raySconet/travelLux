<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fas fa-list mr-2 text-[#B6844A]"></i>{{ __('Customers') }}
            </h2>
            
            <x-primary-btn class="flex items-center gap-2"  onclick="window.location='{{ route('customers.create') }}'"><i class="far fa-plus-square"></i>Add Customer</x-primary-btn>
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="p-3 bg-white shadow sm:rounded-lg">
           
            <form method="GET" action="{{ route('customers.customerList') }}" class="flex items-end justify-end gap-9 px-6 py-4 ">
                {{-- <select name="CustomerTag" id="customerTag" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1">
                    <option value="-1">--Filter by Customer Tags --</option>
                </select> --}}
                
                @php
                    $selectedStatuses = request()->input('status', ['Active']);
                @endphp
                <div class="w-90 relative" x-data="{
                    open: false,
                    selected: {{ json_encode($selectedStatuses) }},
                    options: ['Active','Inactive','Invited','Paused','Prospect'],
                    toggle(val) {
                        if (this.selected.includes(val)) {
                            this.selected = this.selected.filter(v => v !== val);
                        } else {
                            this.selected.push(val);
                        }
                    },
                    label() {
                        return this.selected.length ? this.selected.join(', ') : '-- Filter by Status --';
                    }
                    }" x-cloak>
                    <template x-for="s in selected" :key="s">
                        <input type="hidden" name="status[]" :value="s">
                    </template>
                    <button type="button" @click="open = !open" @click.outside="open = false" class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 bg-white text-left flex justify-between items-center cursor-pointer">
                        <span x-text="label()" class="text-gray-600 truncate"></span>
                        <svg class="w-4 h-4 text-gray-400 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg">
                        <template x-for="opt in options" :key="opt">
                            <div @click="toggle(opt); showLoaderOnSubmit(); $nextTick(() => $el.closest('form').submit())" class="flex items-center justify-between px-3 py-2 text-sm cursor-pointer hover:bg-gray-100">
                                <span x-text="opt"></span>
                                <svg x-show="selected.includes(opt)" class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                </div>

                @if(auth()->user()->isAdmin() || auth()->user()->isSubAdmin())
                    <select name="users" id="agents" class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 cursor-pointer" onchange="showLoaderOnSubmit();this.form.submit()">
                        <option value="-1" {{ $agentId == -1 ? 'selected' : '' }}>All Agents</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $agentId == $user->id ? 'selected' : '' }}>
                                {{ $user->fname . ' ' . $user->lname}}
                            </option>
                        @endforeach
                    </select>
                @endif

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Quick Search"
                    class="w-90 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                    oninput="clearTimeout(this.delay); this.delay=setTimeout(() => this.form.submit(), 500)"
                >
            </form>

           <div class="flex justify-end px-6">
                <button
                    type="button"
                    class="exportExcelBtn bg-[#e0e0e0] text-black px-2 py-1 rounded-none border-1 border-[#666] cursor-pointer" style="background-image: linear-gradient(to bottom, #f9f9f9 0%, #e0e0e0 100%);">
                    Excel
                </button>
            </div>

            @php
                $isRestrictedSubAdminView = auth()->user()->isSubAdmin() && $agentId != auth()->id();
            @endphp
            <div class="overflow-x-auto mt-3 px-6">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>

                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Name</b>
                            </th>

                            @if(!$isRestrictedSubAdminView)

                                <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                    <b>Cell Phone</b>
                                </th>

                                <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                    <b>Email</b>
                                </th>

                                <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                    <b>Status</b>
                                </th>

                            @endif

                            <th class="w-1/5 px-4 py-3 text-left text-sm border-b-2 border-t-2 border-[#dee2e6]">
                                <b>Agent name</b>
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($customers as $customer)

                            @php
                                $isOwner = auth()->id() == $customer->agent_id;

                                $restrictedRow = auth()->user()->isSubAdmin() && !$isOwner;
                            @endphp

                            <tr class="{{ !$restrictedRow ? 'hover:bg-gray-50 cursor-pointer' : '' }}"
                                @if(!$restrictedRow)
                                    onclick="showLoaderOnSubmit(); window.location='{{ route('customers.customerDetails', $customer->id) }}'"
                                @endif
                            >

                                <td class="px-4 py-3 text-[13px] font-normal text-[#212529] border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $customer->lname }}, {{ $customer->fname }} {{ $customer->mname }}
                                </td>

                                @if(!$isRestrictedSubAdminView)

                                    <td class="px-4 py-3 text-[#212529] border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $customer->cellphone }}
                                    </td>

                                    <td class="px-4 py-3 text-[#212529] border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $customer->email }}
                                    </td>

                                    <td class="px-4 py-3 text-[#212529] border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                        {{ $customer->status }}
                                    </td>

                                @endif

                                <td class="px-4 py-3 text-[#212529] border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $customer->agent ? $customer->agent->fname . ' ' . $customer->agent->lname : '-' }}
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="{{ $isRestrictedSubAdminView ? 2 : 5 }}" class="py-2 text-center">
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
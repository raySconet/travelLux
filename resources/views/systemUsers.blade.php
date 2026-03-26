<x-app-layout>
    <x-slot name="header">
        <div class="p-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-user-circle mr-2 text-[#f18325]"></i>{{ __('System Users') }}
            </h2>
            
            <x-primary-btn class="flex items-center gap-2" onclick="window.location='{{ route('system-users.create') }}'"><i class="far fa-plus-square"></i>Add User</x-primary-btn>
        </div>
    </x-slot>

    <div class="mx-auto py-2 px-4">
       
        <div class="py-3 bg-white shadown sm:rounded-lg px-3">
           
            <form method="GET" action="{{ route('system-users') }}" class="flex items-end justify-end px-6 py-4 ">

                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Quick Search"
                        class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                        oninput="clearTimeout(this.delay); this.delay=setTimeout(()=>this.form.submit(),500)"
                    >
                </div>
            </form>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                Name
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                UserName
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                Role
                            </th>   
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                Active
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                Access Level
                            </th>  
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                Phone
                            </th> 
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                City
                            </th> 
                            <th class="px-4 py-3 text-left text-sm font-extrabold border-t-2 border-b-2 border-[#dee2e6]">
                                State
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('system-users.edit', $user->id) }}'">
                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->fname . ' ' . $user->lname }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->role === 1 ? 'Administrator' : 'Agent' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    @if($user->is_disabled === 0)
                                        <i class="fas fa-circle text-green-500 ml-3"></i>
                                    @else
                                        <i class="fas fa-circle text-red-500 ml-3"></i>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2  {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    <span class="ml-8">{{ $user->role }}</span>
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->phone_number }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->city }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $user->state }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

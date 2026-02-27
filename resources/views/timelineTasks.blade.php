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
       
        <div class="bg-white shadow rounded-none ml-2 px-3">
           
            <form method="GET" action="{{ route('timelinetasks') }}" class="flex items-end justify-end px-6 py-4">

                <div class="relative flex gap-6">
                    <select  onchange="this.form.submit()" name="product_id" id="product_id" class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 text-[#495057]">
                        <option value="">--Filter By Product--</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>    
                        @endforeach    
                    </select>
                    <select  onchange="this.form.submit()" name="destination_id" id="destination_id" class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 text-[#495057]">
                        <option value="">--Filter by Destination--</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}"  {{ request('destination_id') == $destination->id ? 'selected' : '' }}>>
                                {{ $destination->destination_name }}
                            </option>
                        @endforeach        
                    </select>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Quick Search"
                        class="w-64 border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-1 focus:outline-none"
                        oninput="clearTimeout(this.delay); this.delay=setTimeout(()=> this.form.submit(),500)"
                    >
                </div>
            </form>

           
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/6 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Product
                            </th>
                            <th class="w-1/6 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Destination
                            </th>
                            <th class="w-1/6 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Task Name
                            </th>
                            <th class="w-1/6 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Priority
                            </th>
                            <th class="w-1/6 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                Days
                            </th>
                            <th class="w-1/6 px-4 py-3 text-left text-sm font-bold border-b-2 border-t-2 border-[#dee2e6]">
                                DateType
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y">
                        @foreach ($timelineTasks as $timelineTask)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('timeline-tasks.edit', $timelineTask->id) }}'">
                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $timelineTask->product ? $timelineTask->product->product_name : '-' }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $timelineTask->destination ? $timelineTask->destination->destination_name : '-' }}
                                </td>

                                <td class="w-1/6 px-4 py-3 text-gray-600  border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $timelineTask->task_name }}
                                </td>   

                                <td class="w-1/6 px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    <i class="fas fa-exclamation-triangle text-[#bdbdbd] text-lg mr-2"></i>{{ $timelineTask->priority }}
                                </td>
                                
                                <td class="w-1/6 px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $timelineTask->due_days . ' ' . $timelineTask->before_after }}
                                </td>
                                
                                <td class="w-1/6 px-4 py-3 text-gray-600 border-t-2 {{ $loop->last ? '' : 'border-b-2 border-[#dee2e6]' }}">
                                    {{ $timelineTask->date_type }}
                                </td>   
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

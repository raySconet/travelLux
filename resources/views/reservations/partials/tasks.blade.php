@php
    $isTaskModalOpen = session('openTaskModal') || $errors->taskStore->any();
@endphp
@if ($isNewReservation)
    <div class="space-x-2">
        <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
        <span class="text-[#6c757d] text-base">Tasks will be available after Reservation is saved.</span>
    </div>
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">
            Reservation Tasks
            @if($overdueTasksCount > 0)
                <span class="text-red-500">({{ $overdueTasksCount }} Overdue)</span>
            @endif
        </h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openReservationsTasksModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>

    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col justify-between gap-3 mt-3">
        <h5 class="text-base">Timeline Tasks</h5>
        <p class="text-center text-base">No Timeline Tasks.</p>
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col justify-between gap-3 mt-3">
        <h5 class="text-base">General Tasks.</h5>

        @forelse($reservation->tasks()->where('is_deleted',0)->get() as $task)
            @php
                $isOverdue = $task->is_completed == 0 && $task->due_date && \Carbon\Carbon::parse($task->due_date)->lte(\Carbon\Carbon::today());
            @endphp
            <div class="flex justify-between mt-1" onclick='openEditTaskModal(@json($task))'>
                <div class="flex gap-5">
                    <form method="POST" action="{{ route('tasks.toggleComplete', $task->id) }}" class="inline">
                        @csrf
                        <button onclick="event.stopPropagation();" type="submit">
                            @if($task->is_completed == 0)
                                <i title="Complete Task" class="far fa-check-circle text-[#bdbdbd] mt-3 text-2xl" title="Complete Task"></i>
                            @else
                                <i title="Undo Complete" class="fas fa-check-circle text-[#50c878] mt-3 text-2xl" title="Undo Complete"></i>
                            @endif
                        </button>
                    </form>       
                    <div class="flex flex-col">
                        @if($task->is_completed == 0)
                            <p class="text-base">{{ $task->task_name }}</p>
                            <div class="flex text-sm gap-1 {{ $isOverdue ? 'text-red-500' : 'text-[#bdbdbd]' }}">
                                <i class="far fa-clock mt-1"></i>
                                <p>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('m/d/Y') : ' ' }}</p>
                            </div>
                        @else
                            <p class="text-base line-through">{{ $task->task_name }}</p>
                            <div class="flex text-[#bdbdbd] text-sm gap-1">
                                <p>Completed By : {{ $task->agent->fname . ' '. $task->agent->lname }} on {{ $task->is_completed_on }}</p>
                            </div>   
                        @endif
                    </div>
                </div>
                <div class="space-x-8 text-2xl">
                    @if($task->priority == 'Low')
                        <i class="fas fa-exclamation-triangle text-green-500" title="Low priority"></i>
                    @elseif($task->priority == 'Medium')
                        <i class="fas fa-exclamation-triangle text-yellow-500" title="Medium priority"></i>
                    @else
                        <i class="fas fa-exclamation-triangle text-red-500" title="High priority"></i>
                    @endif 
                    
                    <form method="POST" action="{{ route('tasks.delete', $task->id) }}" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="event.stopPropagation(); openDeleteModal(this)">
                            <i title="Delete Task" class="fa fa-trash text-[#bdbdbd]"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-center text-base">No General Tasks.</p>
        @endforelse
          
    </div>
@endif

<!-- Reservations Tasks Modal -->
@if (!$isNewReservation)
    <form id="taskForm"  method="POST" action="{{ route('reservations.tasks.store', $reservation->id) }}" data-store-url="{{ route('reservations.tasks.store', $reservation->id)}}">
        @csrf
        <input type="hidden" id="task_id_modal" name="task_id" value="">
        <input type="hidden" name="_method" id="task_method" value="POST">

        <x-reservations-modal id="reservationsTasksModal"  title="Add Task" close="closeReservationsTasksModal()" saveClass="reservationTasksSaveBtn" :open="$isTaskModalOpen" >
            <div class="relative mt-2">
                <x-text-input type="text" id="task_name_modal" name="task_name" value="{{ old('task_name', $reservation->task_name ?? '') }}" />

                <x-input-label for="task_name">Task Name</x-input-label>

                <x-input-error :messages="$errors->taskStore->get('task_name')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                <div  x-data="dateDropdown('{{ old('due_date', $reservation->due_date ?? '') }}')" class="relative mt-5">
                    <label class="block text-sm mb-1">Due Date</label>
                    <div class="flex w-full border-b-2 border-[#bdbdbd] overflow-hidden outline-none">
                        <select x-model="year" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Year</option>
                            <template x-for="y in years" :key="y">
                                <option :value="y" x-text="y"></option>
                            </template>
                        </select>

                        <select x-model="month" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Month</option>
                            <template x-for="(m, i) in months" :key="i">
                                <option :value="i + 1" x-text="m"></option>
                            </template>
                        </select>

                        <select x-model="day" class="flex-1 border-0 focus:ring-0 focus:outline-none px-3 py-2">
                            <option value="">Day</option>
                            <template x-for="d in days" :key="d">
                                <option :value="d" x-text="d"></option>
                            </template>
                        </select>

                    </div>
                    <input type="hidden" id="task_due_date_modal" name="due_date" :value="formattedDate">
                    <x-input-error :messages="$errors->taskStore->get('due_date')" />
                </div>

                <div class="relative mt-9">
                    <label for="priority">Priority</label>
                    <select name="priority" id="task_priority_modal" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                        <option value="">-- Select Priority --</option>
                        <option value="Low" {{ old('priority', $reservation->priority ?? '') == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $reservation->priority ?? '') == 'Medium' ? 'selected' : ''}}>Medium</option>
                        <option value="High" {{ old('priority', $reservation->priority ?? '') == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                    <x-input-error :messages="$errors->taskStore->get('priority')" />
                </div>
            </div>

            <div class="relative mt-8 mb-5">
                <x-text-input type="text" id="task_notes_modal" name="task_notes" value="{{ old('task_notes', $reservation->task_notes ?? '') }}" />

                <x-input-label for="task_notes">Notes</x-input-label>
            </div>

        </x-reservations-modal>
    </form>
@endif    
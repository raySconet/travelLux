@php
    $isDiningInfoModalOpen = session('openDiningInfoModal') || $errors->diningNoteStore->any();
@endphp    
@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Dining Information will be available after Reservation is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Dining Info</h6>

        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openDiningInfoModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>

    @forelse($reservation->diningNotes()->where('is_deleted',0)->get() as $diningNote)
        <div class="flex justify-between mt-5" onclick='openEditDiningInformationModal(@json($diningNote))'>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('diningNotes.toggleCancel', $diningNote->id) }}" class="inline">
                    @csrf
                    <button onclick="event.stopPropagation();" type="submit">
                        @if ($diningNote->is_canceled == 0)
                            <i title="Cancel this Note" class="fas fa-minus-circle text-2xl text-[#bdbdbd] mt-7" title="Cancel this Note"></i>
                        @else
                            <i title="Undo Cancel" class="fas fa-minus-circle text-2xl text-red-500 mt-7" title="Undo Cancel"></i>
                        @endif        
                    </button>
                </form>
                <div class="flex flex-col">
                    <div class="flex gap-1">
                        <i class="fas fa-user text-base mt-1"></i>
                        <p class="text-base">{{ $diningNote->agent->fname . ' '. $diningNote->agent->lname }}</p>
                    </div>
                    @if($diningNote->is_canceled == 0)
                        <p>{{ $diningNote->notes }}</p>
                    @else
                        <p class="line-through">{{ $diningNote->notes }}</p>
                    @endif
                    @if(!empty($diningNote->dining_date) || !empty($diningNote->dining_time))
                        <p>
                            <b>Dining Date Time:</b>
                            {{ (!empty($diningNote->dining_date) ? \Carbon\Carbon::parse($diningNote->dining_date)->format('m/d/Y') : '' ) . ' ' . ($diningNote->dining_time ?? '') }}
                        </p>
                    @endif
                    @if(!empty($diningNote->meal))
                        <p><b>Meal:</b>{{ $diningNote->meal }}</p>
                    @endif
                    @if($diningNote->is_canceled == 1)
                        <p class="text-[#bdbdbd] text-base">Canceled By: {{ $diningNote->agent->fname . ' ' . $diningNote->agent->lname }} on {{ $diningNote->canceled_on }}</p>
                    @endif    
                </div>
            </div>
            <form method="POST" action="{{ route('diningNotes.delete', $diningNote->id) }}" class="inline delete-form">
                @csrf
                @method('DELETE')

                <button type="button" onclick="event.stopPropagation(); openDeleteModal(this)">
                    <i title="Delete note" class="fa fa-trash text-[#bdbdbd] text-xl mt-5"></i>
                </button>
            </form>
        </div>
    @empty
        <p class="text-center text-base">No Dining Notes.</p>
    @endforelse        
@endif  

<!-- Reservation Dining Info Modal -->
@if (!$isNewReservation)
    <form id="diningNoteForm" method="POST" action="{{ route('reservations.diningNotes.store', $reservation->id) }}" data-store-url="{{ route('reservations.diningNotes.store', $reservation->id) }}" >
        @csrf
        <input type="hidden" id="dining_note_id_modal" name="dining_note_id" value="">
        <input type="hidden" name="_method" id="dining_note_method" value="POST">
        
        <x-reservations-modal id="diningInfoModal" title="Add Dining Note" close="closeDiningInfoModal()" saveClass="diningInformationSaveBtn" :open="$isDiningInfoModalOpen">
            <div x-data="dateDropdown('{{ old('dining_date', $reservation->dining_date ?? '') }}')" class="relative mt-3">
                <label class="block text-sm mb-1"> Date</label>
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
                <input type="hidden" id="dining_date_modal" name="dining_date" :value="formattedDate">
            </div>

            <div class="relative mt-3">
                <x-text-input type="time" id="dining_time_modal" name="dining_time" value="{{ old('dining_time', $reservation->dining_time ?? '') }}" />

                <x-input-label for="dining_time">Time</x-input-label>
            </div>

            <div class="relative mt-3">
                <label for="meal">Meal</label>
                <select name="meal" id="meal_modal" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                    <option value="-1">-- Select Meal --</option>
                    <option value="Breakfast" {{ old('meal', $reservation->meal ?? '') == 'Breakfast' ? 'selected' : '' }}>Breakfast</option>
                    <option value="Lunch" {{ old('meal', $reservation->meal ?? '') == 'Lunch' ? 'selected' : '' }}>Lunch</option>
                    <option value="Dinner" {{ old('meal', $reservation->meal ?? '') == 'Dinner' ? 'selected' : ''}}>Dinner</option>
                </select>
            </div>

            <div class="flex flex-col mt-3">
                <label for="notes" class="mb-1 text-sm text-gray-700">Notes</label>
                <textarea
                    id="notes_modal"
                    name="notes"
                    rows="3"
                    class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1"
                >{{ old('notes', $reservation->notes ?? '') }}</textarea>
                <x-input-error :messages="$errors->diningNoteStore->get('notes')" />
            </div>
        </x-reservations-modal>
    </form>
@endif        
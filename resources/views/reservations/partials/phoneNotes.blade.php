@php 
    $isPhoneNoteModalOpen = session('openPhoneNotesModal') || $errors->phoneNoteStore->any();
@endphp
@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Phone Notes will be available after Reservation is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Phone Notes</h6>

        <button type="button" class="text-[#B6844A] text-2xl flex-shrink-0" onclick="openPhoneNotesModal()">
            <i class="fas fa-plus-circle cursor-pointer"></i>
        </button>
    </div>    

    @forelse($reservation->phoneNotes()->where('is_deleted',0)->get() as $phoneNote)
        <div class="flex justify-between mt-5 cursor-pointer" onclick='openEditPhoneNote(@json($phoneNote))'>
            <div class="flex gap-4">
                <button type="button" onclick="togglePhoneNoteCancel({{ $phoneNote->id }}, event)" class="cursor-pointer">
                    @if($phoneNote->is_canceled == 0)
                        <i title="Cancel this note" class="fas fa-minus-circle text-2xl text-[#bdbdbd] mt-7"></i>
                    @else
                        <i title="Undo Cancel" class="fas fa-minus-circle text-2xl text-red-500 mt-7"></i>
                    @endif        
                </button>
                <div class="flex flex-col text-sm">
                    <div class="flex gap-6">
                        <div class="flex gap-1">
                            <i class="fas fa-user text-base mt-1"></i>
                            <p class="text-base">{{ $phoneNote->agent->fname . ' '. $phoneNote->agent->lname }}</p>
                        </div>
                        <div class="flex gap-1 mt-1">
                            <i class="fas fa-calendar-alt mt-1"></i>
                            <p>{{ $phoneNote->created_on }}</p>
                        </div>
                    </div>

                    <div class="flex gap-6 mt-1">
                        @if(!empty($phoneNote->caller_name))
                            <div class="flex gap-1">
                                <i class="fas fa-address-card mt-1"></i>
                                @if($phoneNote->is_canceled == 0)
                                    <p>{{ $phoneNote->caller_name }}</p>
                                @else 
                                    <p class="line-through">{{ $phoneNote->caller_name }}</p> 
                                @endif       
                            </div>
                        @endif
                        
                        @if(!empty($phoneNote->caller_phone_number))
                            <div class="flex gap-1">
                                <i class="fas fa-phone mt-1"></i>
                                @if($phoneNote->is_canceled == 0)
                                    <p>{{ $phoneNote->caller_phone_number }}
                                @else
                                    <p class="line-through">{{ $phoneNote->caller_phone_number }}</p>
                                @endif        
                            </div>
                        @endif    
                    </div>

                    @if($phoneNote->is_canceled == 0)
                        <p>{{ $phoneNote->notes }}</p>
                    @else
                        <p class="line-through">{{ $phoneNote->notes }}</p>
                        <p class="text-base text-[#bdbdbd]">Canceled By: {{ $phoneNote->agent->fname . ' ' . $phoneNote->agent->lname }} on {{ $phoneNote->canceled_on }}</p>
                    @endif    
                </div>
            </div>
            <button type="button" onclick="event.stopPropagation(); openPhoneNoteDeleteModal({{ $phoneNote->id }})">
                <i title="Delete note" class="fas fa-trash text-[#bdbdbd] text-2xl mt-7 cursor-pointer"></i>
            </button>
        </div>
    @empty
        <p class="text-center text-base">No Phone Notes</p>
    @endforelse        
@endif    

<!-- Reservation Phone Notes Modal -->
@if (!$isNewReservation)
    <div id="phoneFormContainer" data-store-url="{{ route('reservations.phoneNotes.store', $reservation->id) }}">

        <input type="hidden" id="phone_note_id_modal">
        <input type="hidden" id="phone_note_method" value="POST">

        <x-reservations-modal id="phoneNotesModal" title="Add Phone Notes" close="closePhoneNotesModal()" saveClass="phoneNotesModalSaveBtn" :open="$isPhoneNoteModalOpen">
            <div class="relative mt-3">
                <label for="category">Category</label>
                <select name="category" id="category" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A]">
                    <option value="">--Select Category --</option>
                    <option value="Breakfast" {{ old('category', $reservation->category ?? '') == 'Breakfast' ? 'selected' : '' }}>Breakfast</option>
                    <option value="Lunch" {{ old('category', $reservation->category ?? '') == 'Lunch' ? 'selected' : '' }}>Lunch</option>
                    <option value="Dinner" {{ old('category', $reservation->category ?? '') == 'Dinner' ? 'selected' : '' }}>Dinner</option>
                </select>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="caller_name" name="caller_name" value="{{ old('caller_name', $reservation->caller_name ?? '') }}" />

                <x-input-label for="caller_name">Caller Name</x-input-label>
            </div>

            <div class="relative mt-3">
                <x-text-input type="text" id="caller_phone_number" name="caller_phone_number" value="{{ old('caller_phone_number', $reservation->caller_phone_number ?? '') }}" />

                <x-input-label for="caller_phone_number">Caller Phone Number</x-input-label>
            </div>

            <div class="flex flex-col mt-3">
                <label for="notes" class="mb-1 text-sm text-gray-700">Notes</label>
                <textarea
                    id="phone_notes_modal"
                    name="notes"
                    rows="3"
                    class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] resize-none pt-1 pb-1"
                >{{ old('notes', $reservation->notes ?? '') }}</textarea>
                <x-input-error :messages="$errors->phoneNoteStore->get('notes')" />
            </div>
        </x-reservations-modal>
    </div>
@endif
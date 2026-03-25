@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Gifts Info will be available after Reservation is saved.</span>
        </div>
    </div>
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Gifts</h6>
        
        <button type="button" class="text-[#f18325] text-2xl flex-shrink-0" onclick="openGiftsModal()">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>
    
    @forelse($reservation->gifts()->where('is_deleted',0)->get() as $gift)
        <div class="flex justify-between mt-5">
            <div class="flex flex-col ml-2 text-sm">
                <div class="flex gap-1">
                    <i class="fas fa-user text-base mt-1"></i>
                    <p class="text-base">{{ $gift->agent->fname . ' '. $gift->agent->lname }}</p>
                </div>
                <p><b>Gift Type:</b>  {{ $gift->gift_type }}</p>
                <p><b>Date:</b>  {{ $gift->gift_date ? \Carbon\Carbon::parse($gift->gift_date)->format('m/d/Y') : '' }}</p>
                <p><b>Amount:</b>  ${{ $gift->amount }}</p>
            </div>

            <form method="POST" action="{{ route('gifts.delete', $gift->id) }}" class="inline delete-form">
                @csrf
                @method('DELETE')

                <button type="button" onclick="openDeleteModal(this)">
                    <i class="fa fa-trash text-[#bdbdbd] text-xl mt-5"></i>
                </button>
            </form>
        </div>
    @empty
        <p class="text-center text-base">No Gifts Available.</p>
    @endforelse

@endif        

<!-- Reservation Gifts Modal -->
@if(!$isNewReservation)
    <form method="POST" action="{{ route('reservations.gifts.store', $reservation->id) }}">
        @csrf
        <x-reservations-modal id="giftsModal" title="Add Gift" close="closeGiftsModal()" saveClass="giftsModalSaveBtn">

            <div class="relative mt-3">
                <label for="gift_type">Gift Type</label>
                <select name="gift_type" id="gift_type" class="w-full mb-4 border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325]">
                    <option value="">-- Select Gift --</option>
                    <option value="Gift To The Room">Gift To The Room</option>
                    <option value="Onboard Credit">Onboard Credit</option>
                    <option value="Spa Day">Spa Day</option>
                    <option value="Other">Other</option>
                </select>
                <x-input-error :messages="$errors->get('gift_type')" />
            </div>

            <div x-data="dateDropdown()" class="relative mt-3">
                <label class="block text-sm mb-1">Gift Date</label>
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

                    <select x-model="day" class="flex-1 border-0 focus:ring-0 focusLoutline-none px-3 py-2">
                        <option value="">Day</option>
                        <template x-for="d in days" :key="d">
                            <option :value="d" x-text="d"></option>
                        </template>
                    </select>
                </div>
                <input type="hidden" name="gift_date" :value="formattedDate">
                <x-input-error :messages="$errors->get('gift_date')" />
            </div>

            <div class="relative mt-5">
                <label for="amount" class="block text-sm">Amount</label>
                
                <div class="relative">
                    <span class="absolute left-2 top-1/2 text-base font-bold"><i class="fas fa-dollar-sign"></i></span>

                    <x-text-input type="text" id="amount" name="amount" class="pl-7" />

                </div>
                <x-input-error :messages="$errors->get('amount')" />
            </div>

            <div class="flex flex-col mt-3">
                <label for="notes" class="mb-1 text-sm text-gray-700">Notes</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    class="w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
                </textarea>
            </div>
        </x-reservations-modal>
    </form>    
@endif    
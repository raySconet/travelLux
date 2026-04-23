@if ($isNewReservation)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Travelers will be available after Reservation is saved.</span>
        </div>
    </div>   
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Travelers</h6>
    </div>

    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

    @php
        $travelers = $reservation->travelers()->where('is_deleted', 0)->with('familyMember')->get();
    @endphp

    @forelse($travelers as $traveler)
        <div x-data="{ open: false }"
            class="flex flex-col mt-3 bg-[#fff] px-5 py-3"
            style="box-shadow: 0 5px 5px -11px rgba(0, 0, 0, 0.2),0 1px 4px -22px rgba(0, 0, 0, 0.14),0 3px 14px 2px rgba(0, 0, 0, 0.12);">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('travelers.toggleInclude', $traveler->id) }}" class="inline">
                        @csrf
                        <button onclick="event.stopPropagation();" type="submit">
                            @if($traveler->is_included == 0)
                                <i class="far fa-check-circle text-[#bdbdbd] text-2xl"></i>
                            @else
                                <i class="fas fa-check-circle text-[#50c878] text-2xl"></i>
                            @endif        
                        </button>
                    </form>

                    <i class="fa fa-user-tie text-xl"></i>

                    <p class="text-xl">
                        {{ $traveler->familyMember->fname ?? '' }}
                        {{ $traveler->familyMember->lname ?? '' }}
                    </p>

                    <p class="text-base bg-[#bdbdbd] text-white px-3">
                        @if($traveler->familyMember->relation != -1)
                            {{ $traveler->familyMember->relation ?? '' }}
                        @endif    
                    </p>
                </div>

                <i 
                    class="fas text-xl cursor-pointer"
                    :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                    @click="open = !open"
                ></i>
            </div>

            <div x-show="open" x-transition class="mt-5 flex flex-col gap-3">

                <div class="flex justify-between text-base font-bold">
                    <p class="w-1/4">Nickname</p>
                    <p class="w-1/4">Birthdate</p>
                    <p class="w-1/4">Email</p>
                    <p class="w-1/4">Phone</p>
                </div>

                <div class="flex justify-between text-sm">
                    
                    <p class="w-1/4">
                        @if(!empty($traveler->familyMember->nickname))
                            {{ $traveler->familyMember->nickname }}
                        @endif
                    </p>

                    <p class="w-1/4">
                        @if(!empty($traveler->familyMember->birth_date))
                            {{ \Carbon\Carbon::parse($traveler->familyMember->birth_date)->format('m/d/Y') }}
                        @endif
                    </p>

                    <p class="w-1/4">
                        @if(!empty($traveler->familyMember->email))
                            {{ $traveler->familyMember->email }}
                        @endif
                    </p>

                    <p class="w-1/4">
                        @if(!empty($traveler->familyMember->cellphone))
                            {{ $traveler->familyMember->cellphone }}
                        @endif
                    </p>

                </div>

                <div class="text-sm mt-2">
                    <p class="font-bold text-base">Notes</p>
                    @if(!empty($traveler->familyMember->special_notes))
                     <p>{{ $traveler->familyMember->special_notes }}</p>
                    @endif
                </div>

            </div>

        </div>
    @empty
        <p class="text-base text-center mt-2">
            No Family Travelers for this Customer
        </p>
    @endforelse
@endif        
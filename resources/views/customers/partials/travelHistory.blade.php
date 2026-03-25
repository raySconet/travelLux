@if ($isNewCustomer)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Travel History will be available after customer is saved.</span>
        </div>
    </div>    
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Travel History</h6>
    </div>    
    <div class="relative flex flex-col mt-5">
        <h6 class="text-base">Leads</h6>
        <p class="text-base text-center">No Leads.</p>
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col mt-3">
        <h6 class="text-base">Upcoming</h6>

        @forelse($customer->reservations()->where('is_deleted',0)->whereDate('checkin_date', '>', \Carbon\Carbon::today())->orderBy('checkin_date', 'asc')->get() as $reservation)
            <div class="flex gap-1 text-lg">
                <i class="fas fa-tag text-2xl mt-1"></i>
                <p>{{ $reservation->reservation_name }}</p>
            </div>
            <div class="flex justify-between mt-1 text-base">
                <div class="flex gap-7">
                    <div class="flex gap-1">
                        <i class="fas fa-calendar-alt mt-1"></i>
                        <p>{{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') : '' }}</p>
                    </div>
                    <p>{{ $reservation->status }}</p>
                    <p><p>{{ $reservation->product?->product_name ?? ' ' }} / {{ $reservation->destination?->destination_name ?? ' ' }}</p></p>
                    <p>{{ $reservation->reservation_number }}</p>
                </div>
                <a href="{{ route('reservations.reservationDetails', $reservation->id) }}">
                    <i class="fas fa-external-link-alt text-[#bdbdbd] text-lg cursor-pointer"></i>
                </a>
            </div>
        @empty
            <p class="text-center text-base">No Upcoming Travel</p>
        @endforelse        
    </div>
    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">
    <div class="relative flex flex-col mt-3">
        <h6 class="text-base">History</h6>
        
        @forelse($customer->reservations()->where('is_deleted',0)->whereDate('checkin_date', '<=', \Carbon\Carbon::today())->orderBy('checkin_date', 'asc')->get() as $reservation)
            <div class="flex gap-1 text-lg">
                <i class="fas fa-tag text-2xl mt-1"></i>
                <p>{{ $reservation->reservation_name }}</p>
            </div>
            <div class="flex justify-between mt-1 text-base">
                <div class="flex gap-7">
                    <div class="flex gap-1">
                        <i class="fas fa-calendar-alt"></i>
                        <p>{{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('m/d/Y') : '' }}
                    </div>
                    <p>{{ $reservation->status }}</p>
                    <p><p>{{ $reservation->product?->product_name ?? ' ' }} / {{ $reservation->destination?->destination_name ?? ' ' }}</p></p>
                    <p>{{ $reservation->reservation_number }}</p>
                </div>
                <a href="{{ route('reservations.reservationDetails', $reservation->id) }}">
                    <i class="fas fa-external-link-alt text-[#bdbdbd] text-lg cursor-pointer"></i>
                </a>
            </div>
        @empty
            <p class="text-center text-base">No Travel History</p>
        @endforelse

    </div>
@endif 
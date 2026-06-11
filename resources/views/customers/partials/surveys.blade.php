@if ($isNewCustomer)
    <div class="space-x-2">
        <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
        <span class="text-[#6c757d] text-base">
            Surveys will be available after record is saved.
        </span>
    </div>
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-lg">Surveys</h6>
    </div>

    <hr class="mt-3 w-full border-b-1 border-[#dee2e6]">

    @php
        $surveys = $customer->reservations->flatMap(fn($r) => $r->customerSurveys);
    @endphp

    @if($surveys->isEmpty())
        <p class="text-base text-center mt-3">No Surveys Available</p>
    @else
        @foreach($surveys as $survey)
            <div class="flex justify-between mt-3 border-b-1 border-[#dee2e6] pb-3">
                <div class="flex flex-col gap-2 text-sm">

                    <div class="flex gap-2">
                        <p>Reservation Number:{{ $survey->reservation->reservation_number ?? 'N/A' }}</p>
                        <p>({{ $survey->reservation->checkin_date }}-{{ $survey->reservation->checkout_date }})</p>
                    </div>

                    <p>{{ $survey->reservation->product->product_name }}-{{ $survey->reservation->destination->destination_name }}</p>

                    <p>Submitted On:{{ $survey->submitted_on }}</p>
                </div>

                <div class="flex mt-5 gap-3 text-xl">
                    <i title="Preview Form" onclick='openSurveyPreviewModal(@json($survey->submitted_survey_content))' class="fas fa-eye text-[#bdbdbd] cursor-pointer"></i>

                    <a href="{{ route('reservations.reservationDetails', $survey->reservation->id) }}" onclick="showLoaderOnSubmit()">
                        <i title="Go To Reservation" class="fas fa-external-link-alt text-[#bdbdbd] cursor-pointer"></i>
                    </a>
                </div>
            </div>
        @endforeach
    @endif
@endif
<x-survey-preview />
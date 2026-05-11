@if ($isNewReservation)
    <div class="space-x-2">
        <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
        <span class="text-[#6c757d] text-base">
            Attachments will be available after Reservation is saved.
        </span>
    </div>
@else
    <div class="relative flex flex-row justify-between gap-3 mt-5">
        <h6 class="text-xl">Reservation Attachments</h6>

        <div>
            <input type="file"
                id="reservationAttachments"
                name="attachments[]"
                multiple
                class="hidden">

            <button type="button"
                    id="attachReservationBtn"
                    class="text-[#B6844A] text-2xl flex-shrink-0">
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    </div>

    <div id="reservationAttachmentsTable">

        @forelse ($reservation->attachments as $attachment)

            <div class="flex justify-between mt-5 attachment-row">

                <div class="flex space-x-3">

                    <i class="fas fa-file text-[#000] text-2xl mt-3"></i>

                    <div class="flex flex-col">

                        <a href="{{ asset('storage/attachments/reservations/' . $attachment->id . '.' . $attachment->file_extension) }}"
                        target="_blank"
                        class="text-base hover:underline">

                            {{ $attachment->file_name }}.{{ $attachment->file_extension }}

                        </a>

                        <p class="text-[#989898] text-sm">
                            Size: {{ number_format($attachment->file_size) }} Bytes
                        </p>

                    </div>

                </div>

                <div class="space-x-4">

                    <a href="{{ asset('storage/attachments/reservations/' . $attachment->id . '.' . $attachment->file_extension) }}" target="_blank">

                        <i title="Download Attachment" class="fas fa-cloud-download-alt text-[#bdbdbd] text-xl mt-3"></i>

                    </a>

                    <form method="POST" action="{{ route('reservations.attachments.destroy', $attachment->id) }}" class="inline">

                        @csrf
                        @method('DELETE')

                        <button type="submit">

                            <i title="Delete Attachment" class="fas fa-trash text-[#bdbdbd] text-xl mt-3" onclick="openDeleteModal(this)"></i>

                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="text-gray-400 mt-5 empty-row">
                
            </div>

        @endforelse

    </div>
@endif
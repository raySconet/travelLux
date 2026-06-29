<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItineraryTrip;
use App\Models\ItineraryDay;
use App\Models\ItineraryEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ItineraryAttachment;
use App\Models\ItineraryImage;
use Illuminate\Support\Facades\Storage;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'fname', 'lname', 'email')->where('isDeleted', 0)->get();

        $agentId = $request->input('agents', auth()->id());

        $itineraries = ItineraryTrip::with('creator')
            ->where('is_deleted', 0)
            ->when($agentId != -1, function ($query) use ($agentId) {
                $query->where('created_by', $agentId);
            })
            ->orderBy('date', 'desc')
            ->get();

        return view('itinerary', compact('users', 'agentId', 'itineraries'));
    }

    public function create()
    {
        return view('itinerary.createTrip');
    }

    public function store(Request $request)
    {
        $messages = [
            'name' => 'The Trip name is required.',
            'date' => 'The Trip date is required.'
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ], $messages);

        $itinerary = ItineraryTrip::create([
            'name' => $request->name,
            'date' => $request->date,
            'created_by' => auth()->id(),
            'is_deleted' => 0,
        ]);

        return redirect()->route('itinerary.index');
    }

    public function update(Request $request, ItineraryTrip $itinerary)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $itinerary->update([
            'name' => $request->name,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function edit(ItineraryTrip $itinerary)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {

            if ($itinerary->agent_id != $user->id) {
                abort(403);
            }
        }

        $itinerary->load([
            'attachments',
            'itineraryDays' => function ($query) {
                $query->where('isDeleted', 0)
                    ->with([
                        'events' => function ($eventQuery) {
                            $eventQuery->where('isDeleted', 0)
                                ->orderBy('eventTime', 'ASC');
                        }
                    ])
                    ->orderBy('dayNumber', 'ASC');
            }
        ]);

        return view('itinerary.edit', compact('itinerary'));
    }

    public function view(ItineraryTrip $itinerary)
    {
        $itinerary->load([
            'attachments',
            'itineraryDays' => function ($query) {
                $query->where('isDeleted', 0)
                    ->with([
                        'events' => function ($eventQuery) {
                            $eventQuery->where('isDeleted', 0)
                                ->orderBy('eventTime', 'ASC');
                        }
                    ])
                    ->orderBy('dayNumber', 'ASC');
            },
            'itineraryImages'
        ]);

        $viewOnly = true;

        return view('itinerary.edit', compact('itinerary', 'viewOnly'));
    }

    public function destroy(ItineraryTrip $itinerary)
    {
        $itinerary->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('itinerary.index')
                ->with('success', 'Itinerary deleted successfully');
    }

    public function addDay(ItineraryTrip $itinerary)
    {
        $lastDay = ItineraryDay::where('itinerary_trip_id', $itinerary->id)->where('isDeleted', 0)->max('dayNumber');

        $nextDayNumber = $lastDay ? $lastDay + 1 : 1;

        ItineraryDay::create([
            'dayNumber' => $nextDayNumber,
            'itinerary_trip_id' => $itinerary->id,
            'dayTitle' => null,
            'isDeleted' => 0,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function updateDay(Request $request, ItineraryDay $day)
    {
        $request->validate([
            'date' => 'nullable|date',
            'title' => 'nullable|string|max:255',
        ]);

        $day->update([
            'dayTitle' => $request->title,
            'dayDate' => $request->date,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroyDay(ItineraryDay $day)
    {
        $day->update([
            'isDeleted' => 1,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function storeEvent(Request $request)
    {
        $eventType = (int) $request->eventType;
        $subcategoryId = (int) $request->subcategoryId;

        $titleFields = [
            1 => 'itineraryActivityFormTitle',
            2 => 'itineraryLodgingFormTitle',
            3 => 'itineraryFlightFormTitle',
            4 => 'itineraryTransportationFormTitle',
            5 => 'itineraryCruiseFormTitle',
            6 => 'itineraryInfoFormTitle',
        ];

        $titleField = $titleFields[$eventType] ?? null;

        if ($titleField) {
            $request->validate([
                $titleField => 'required|string|max:255',
            ], [
                $titleField . '.required' => 'Title is required.',
            ]);
        }

        $data = [
            'eventType' => $eventType,
            'itineraryEventDayId' => $request->itineraryEventDayId,
            'isDeleted' => 0,
        ];

        if ($eventType === 1) {

            $data['itineraryEventFormActivitySubcategory'] = $subcategoryId == 11 ? 1 : 2;

            $data['eventTime'] = $request->itineraryActivityFormTime;
            $data['itineraryActivityFormTitle'] = $request->itineraryActivityFormTitle;
            $data['itineraryActivityFormNote'] = $request->itineraryActivityFormNote;
            $data['itineraryActivityFormBookedThrough'] = $request->itineraryActivityFormBookedThrough;
            $data['itineraryActivityFormConfirmation'] = $request->itineraryActivityFormConfirmation;
            $data['itineraryActivityFormProvider'] = $request->itineraryActivityFormProvider;
            $data['itineraryActivityFormTime'] = $request->itineraryActivityFormTime;
            $data['itineraryActivityFormDuration'] = $request->itineraryActivityFormDuration;
            $data['itineraryActivityFormTimezone'] = $request->itineraryActivityFormTimezone;
            $data['itineraryActivityFormAmount'] = $request->itineraryActivityFormAmount;
            $data['itineraryActivityFormCurrency'] = $request->itineraryActivityFormCurrency;
        } else if ($eventType === 2) {

            $data['itineraryEventFormLodgingSubcategory'] = $subcategoryId == 21 ? 1 : 2;

            $data['eventTime'] = $request->itineraryLodgingFormTime;
            $data['itineraryLodgingFormTitle'] = $request->itineraryLodgingFormTitle;
            $data['itineraryLodgingFormNote'] = $request->itineraryLodgingFormNote;
            $data['itineraryLodgingFormBookedThrough'] = $request->itineraryLodgingFormBookedThrough;
            $data['itineraryLodgingFormConfirmation'] = $request->itineraryLodgingFormConfirmation;
            $data['itineraryLodgingFormRoomBedType'] = $request->itineraryLodgingFormRoomBedType;
            $data['itineraryLodgingFormTime'] = $request->itineraryLodgingFormTime;
            $data['itineraryLodgingFormDuration'] = $request->itineraryLodgingFormDuration;
            $data['itineraryLodgingFormTimezone'] = $request->itineraryLodgingFormTimezone;
            $data['itineraryLodgingFormAmount'] = $request->itineraryLodgingFormAmount;
            $data['itineraryLodgingFormAmountCurrency'] =$request->itineraryLodgingFormAmountCurrency;
        } else if ($eventType === 3) {

            $data['itineraryEventFormFlightSubCategory'] = $subcategoryId == 31 ? 1 : 2;

            $data['eventTime'] = $request->itineraryFlightFormTime;
            $data['itineraryFlightFormTitle'] = $request->itineraryFlightFormTitle;
            $data['itineraryFlightFormNote'] = $request->itineraryFlightFormNote;
            $data['itineraryFlightFormDuration'] = $request->itineraryFlightFormDuration;
            $data['itineraryFlightFormTimezone'] = $request->itineraryFlightFormTimezone;
            $data['itineraryFlightFormTitle'] = $request->itineraryFlightFormTitle;
            $data['itineraryFlightFormBookedThrough'] = $request->itineraryFlightFormBookedThrough;
            $data['itineraryFlightFormConfirmation'] = $request->itineraryFlightFormConfirmation;
            $data['itineraryFlightFormAirline'] = $request->itineraryFlightFormAirline;
            $data['itineraryFlightFormFlightNumber'] = $request->itineraryFlightFormFlightNumber;
            $data['itineraryFlightFormTerminal'] = $request->itineraryFlightFormTerminal;
            $data['itineraryFlightFormGate'] =$request->itineraryFlightFormGate;
            $data['itineraryFlightFormSeatTicketDetails'] =$request->itineraryFlightFormSeatTicketDetails;
            $data['itineraryFlightFormAmount'] =$request->itineraryFlightFormAmount;
            $data['itineraryFlightFormAmountCurrency'] =$request->itineraryFlightFormAmountCurrency;

        } else if ($eventType === 4) {

            $data['itineraryTransportationFormSubCategory'] = $subcategoryId == 41 ? 1 : 2;

            $data['eventTime'] = $request->itineraryTransportationFormTime;
            $data['itineraryTransportationFormTitle'] = $request->itineraryTransportationFormTitle;
            $data['itineraryTransportationFormNote'] = $request->itineraryTransportationFormNote;
            $data['itineraryTransportationFormDuration'] = $request->itineraryTransportationFormDuration;
            $data['itineraryTransportationFormTime'] = $request->itineraryTransportationFormTime;
            $data['itineraryTransportationFormTimezone'] = $request->itineraryTransportationFormTimezone;
            $data['itineraryTransportationFormBookedThrough'] = $request->itineraryTransportationFormBookedThrough;
            $data['itineraryTransportationFormConfirmation'] = $request->itineraryTransportationFormConfirmation;
            $data['itineraryTransportationFormCarrier'] = $request->itineraryTransportationFormCarrier;
            $data['itineraryTransportationFormTransportationNumber'] = $request->itineraryTransportationFormTransportationNumber;
            $data['itineraryTransportationFormAmount'] = $request->itineraryTransportationFormAmount;
            $data['itineraryTransportationFormAmountCurrency'] =$request->itineraryTransportationFormAmountCurrency;

        } else if ($eventType === 5) {

            $data['itineraryCruiseFormSubCategory'] = $subcategoryId == 51 ? 1 : 2;

            $data['eventTime'] = $request->itineraryCruiseFormTime;
            $data['itineraryCruiseFormTitle'] = $request->itineraryCruiseFormTitle;
            $data['itineraryCruiseFormNote'] = $request->itineraryCruiseFormNote;
            $data['itineraryCruiseFormTime'] = $request->itineraryCruiseFormTime;
            $data['itineraryCruiseFormDuration'] = $request->itineraryCruiseFormDuration;
            $data['itineraryCruiseFormTimezone'] = $request->itineraryCruiseFormTimezone;
            $data['itineraryCruiseFormBookedThrough'] = $request->itineraryCruiseFormBookedThrough;
            $data['itineraryCruiseFormConfirmation'] = $request->itineraryCruiseFormConfirmation;
            $data['itineraryCruiseFormCarrier'] = $request->itineraryCruiseFormCarrier;
            $data['itineraryCruiseFormCabinType'] = $request->itineraryCruiseFormCabinType;
            $data['itineraryCruiseFormCabinNumber'] = $request->itineraryCruiseFormCabinNumber;
            $data['itineraryCruiseFormAmount'] =$request->itineraryCruiseFormAmount;
            $data['itineraryCruiseFormAmountCurrency'] =$request->itineraryCruiseFormAmountCurrency;

        } else if ($eventType === 6) {

            $data['itineraryEventFormInfoSubCategory'] = $subcategoryId == 61 ? 1 : 2;

            $data['eventTime'] = $request->itineraryInfoFormTime;
            $data['itineraryInfoFormTitle'] = $request->itineraryInfoFormTitle;
            $data['itineraryInfoFormNote'] = $request->itineraryInfoFormNote;
            $data['itineraryInfoFormTime'] = $request->itineraryInfoFormTime;
            $data['itineraryInfoFormImage'] = $request->itineraryInfoFormImage;
            $data['itineraryInfoFormImageExtension'] = $request->itineraryInfoFormImageExtension;
        }

        $event = ItineraryEvent::create($data);

        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }

    public function updateEvent(Request $request, ItineraryEvent $event)
    {
        $eventType = (int) $request->eventType;
        $subcategoryId = (int) $request->subcategoryId;

        $data = [
            'eventType' => $eventType,
        ];

        $titleFields = [
            1 => 'itineraryActivityFormTitle',
            2 => 'itineraryLodgingFormTitle',
            3 => 'itineraryFlightFormTitle',
            4 => 'itineraryTransportationFormTitle',
            5 => 'itineraryCruiseFormTitle',
            6 => 'itineraryInfoFormTitle',
        ];

        $titleField = $titleFields[$eventType] ?? null;

        if ($titleField) {
            $request->validate([
                $titleField => 'required|string|max:255',
            ], [
                $titleField . '.required' => 'Title is required.',
            ]);
        }

        if ($eventType === 1) {

            $data['itineraryEventFormActivitySubcategory'] = $subcategoryId == 11 ? 1 : 2;

            $data['eventTime'] = $request->itineraryActivityFormTime;
            $data['itineraryActivityFormTitle'] = $request->itineraryActivityFormTitle;
            $data['itineraryActivityFormNote'] = $request->itineraryActivityFormNote;
            $data['itineraryActivityFormBookedThrough'] = $request->itineraryActivityFormBookedThrough;
            $data['itineraryActivityFormConfirmation'] = $request->itineraryActivityFormConfirmation;
            $data['itineraryActivityFormProvider'] = $request->itineraryActivityFormProvider;
            $data['itineraryActivityFormTime'] = $request->itineraryActivityFormTime;
            $data['itineraryActivityFormDuration'] = $request->itineraryActivityFormDuration;
            $data['itineraryActivityFormTimezone'] = $request->itineraryActivityFormTimezone;
            $data['itineraryActivityFormAmount'] = $request->itineraryActivityFormAmount;
            $data['itineraryActivityFormCurrency'] = $request->itineraryActivityFormCurrency;
        }

        else if ($eventType === 2) {

            $data['itineraryEventFormLodgingSubcategory'] = $subcategoryId == 21 ? 1 : 2;

            $data['eventTime'] = $request->itineraryLodgingFormTime;
            $data['itineraryLodgingFormTitle'] = $request->itineraryLodgingFormTitle;
            $data['itineraryLodgingFormNote'] = $request->itineraryLodgingFormNote;
            $data['itineraryLodgingFormBookedThrough'] = $request->itineraryLodgingFormBookedThrough;
            $data['itineraryLodgingFormConfirmation'] = $request->itineraryLodgingFormConfirmation;
            $data['itineraryLodgingFormRoomBedType'] = $request->itineraryLodgingFormRoomBedType;
            $data['itineraryLodgingFormTime'] = $request->itineraryLodgingFormTime;
            $data['itineraryLodgingFormDuration'] = $request->itineraryLodgingFormDuration;
            $data['itineraryLodgingFormTimezone'] = $request->itineraryLodgingFormTimezone;
            $data['itineraryLodgingFormAmount'] = $request->itineraryLodgingFormAmount;
            $data['itineraryLodgingFormAmountCurrency'] = $request->itineraryLodgingFormAmountCurrency;
        } else if ($eventType === 3) {

            $data['itineraryEventFormFlightSubCategory'] = $subcategoryId == 31 ? 1 : 2;

            $data['eventTime'] = $request->itineraryFlightFormTime;
            $data['itineraryFlightFormTitle'] = $request->itineraryFlightFormTitle;
            $data['itineraryFlightFormNote'] = $request->itineraryFlightFormNote;
            $data['itineraryFlightFormDuration'] = $request->itineraryFlightFormDuration;
            $data['itineraryFlightFormTimezone'] = $request->itineraryFlightFormTimezone;
            $data['itineraryFlightFormTitle'] = $request->itineraryFlightFormTitle;
            $data['itineraryFlightFormBookedThrough'] = $request->itineraryFlightFormBookedThrough;
            $data['itineraryFlightFormConfirmation'] = $request->itineraryFlightFormConfirmation;
            $data['itineraryFlightFormAirline'] = $request->itineraryFlightFormAirline;
            $data['itineraryFlightFormFlightNumber'] = $request->itineraryFlightFormFlightNumber;
            $data['itineraryFlightFormTerminal'] = $request->itineraryFlightFormTerminal;
            $data['itineraryFlightFormGate'] =$request->itineraryFlightFormGate;
            $data['itineraryFlightFormSeatTicketDetails'] =$request->itineraryFlightFormSeatTicketDetails;
            $data['itineraryFlightFormAmount'] =$request->itineraryFlightFormAmount;
            $data['itineraryFlightFormAmountCurrency'] =$request->itineraryFlightFormAmountCurrency;

        } else if ($eventType === 4) {

            $data['itineraryTransportationFormSubCategory'] = $subcategoryId == 41 ? 1 : 2;

            $data['eventTime'] = $request->itineraryTransportationFormTime;
            $data['itineraryTransportationFormTitle'] = $request->itineraryTransportationFormTitle;
            $data['itineraryTransportationFormNote'] = $request->itineraryTransportationFormNote;
            $data['itineraryTransportationFormDuration'] = $request->itineraryTransportationFormDuration;
            $data['itineraryTransportationFormTime'] = $request->itineraryTransportationFormTime;
            $data['itineraryTransportationFormTimezone'] = $request->itineraryTransportationFormTimezone;
            $data['itineraryTransportationFormBookedThrough'] = $request->itineraryTransportationFormBookedThrough;
            $data['itineraryTransportationFormConfirmation'] = $request->itineraryTransportationFormConfirmation;
            $data['itineraryTransportationFormCarrier'] = $request->itineraryTransportationFormCarrier;
            $data['itineraryTransportationFormTransportationNumber'] = $request->itineraryTransportationFormTransportationNumber;
            $data['itineraryTransportationFormAmount'] = $request->itineraryTransportationFormAmount;
            $data['itineraryTransportationFormAmountCurrency'] =$request->itineraryTransportationFormAmountCurrency;

        } else if ($eventType === 5) {

            $data['itineraryCruiseFormSubCategory'] = $subcategoryId == 51 ? 1 : 2;

            $data['eventTime'] = $request->itineraryCruiseFormTime;
            $data['itineraryCruiseFormTitle'] = $request->itineraryCruiseFormTitle;
            $data['itineraryCruiseFormNote'] = $request->itineraryCruiseFormNote;
            $data['itineraryCruiseFormTime'] = $request->itineraryCruiseFormTime;
            $data['itineraryCruiseFormDuration'] = $request->itineraryCruiseFormDuration;
            $data['itineraryCruiseFormTimezone'] = $request->itineraryCruiseFormTimezone;
            $data['itineraryCruiseFormBookedThrough'] = $request->itineraryCruiseFormBookedThrough;
            $data['itineraryCruiseFormConfirmation'] = $request->itineraryCruiseFormConfirmation;
            $data['itineraryCruiseFormCarrier'] = $request->itineraryCruiseFormCarrier;
            $data['itineraryCruiseFormCabinType'] = $request->itineraryCruiseFormCabinType;
            $data['itineraryCruiseFormCabinNumber'] = $request->itineraryCruiseFormCabinNumber;
            $data['itineraryCruiseFormAmount'] =$request->itineraryCruiseFormAmount;
            $data['itineraryCruiseFormAmountCurrency'] =$request->itineraryCruiseFormAmountCurrency;

        } else if ($eventType === 6) {

            $data['itineraryEventFormInfoSubCategory'] = $subcategoryId == 61 ? 1 : 2;

            $data['eventTime'] = $request->itineraryInfoFormTime;
            $data['itineraryInfoFormTitle'] = $request->itineraryInfoFormTitle;
            $data['itineraryInfoFormNote'] = $request->itineraryInfoFormNote;
            $data['itineraryInfoFormTime'] = $request->itineraryInfoFormTime;
            $data['itineraryInfoFormImage'] = $request->itineraryInfoFormImage;
            $data['itineraryInfoFormImageExtension'] = $request->itineraryInfoFormImageExtension;
        }


        $event->update($data);

        return response()->json([
            'success' => true,
            'event' => $event->fresh()
        ]);
    }

    public function destroyEvent(ItineraryEvent $event)
    {
        $event->update([
            'isDeleted' => 1,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function downloadPdf(ItineraryTrip $itinerary)
    {
        $itinerary->load([
            'creator',
            'attachments' => function ($q) {
                $q->where('isDeleted', 0);
            },
            'itineraryImages' => function ($q) {
                $q->where('is_deleted', 0);
            },
            'itineraryDays' => function ($query) {
                $query->where('isDeleted', 0)
                    ->with([
                        'events' => function ($eventQuery) {
                            $eventQuery->where('isDeleted', 0)
                                ->orderBy('eventTime', 'ASC');
                        }
                    ])
                    ->orderBy('dayNumber', 'ASC');
            }
        ]);

        $pdf = Pdf::loadView('itinerary.itineraryPdf', compact('itinerary'))->setPaper('a4', 'portrait');

        return $pdf->download($itinerary->name . '.pdf');
    }

    public function storeAttachment(Request $request, ItineraryTrip $itinerary)
    {
        $request->validate([
            'attachments' => 'required',
            'attachments.*' => 'file|max:20480'
        ]);

        $attachments = [];

        foreach ($request->file('attachments') as $file) {

            $originalName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            $extension = $file->getClientOriginalExtension();

            $attachment = ItineraryAttachment::create([
                'trip_id' => $itinerary->id,
                'name' => $originalName,
                'extension' => $extension,
                'isDeleted' => 0,
            ]);

            $fileName = $attachment->id . '.' . $extension;

            $file->storeAs('attachments/itineraries',$fileName,'public');

            $attachments[] = [
                'id' => $attachment->id,
                'name' => $attachment->name,
                'extension' => $attachment->extension,
                'size' => $file->getSize(),
            ];
        }

        return response()->json([
            'success' => true,
            'attachments' => $attachments
        ]);
    }

    public function deleteAttachment(ItineraryAttachment $attachment)
    {
        $attachment->update([
            'isDeleted' => 1
        ]);

        return response()->json([
            'success' => true,
            'id' => $attachment->id
        ]);
    }

    public function viewAttachment(ItineraryAttachment $attachment)
    {
        if ($attachment->isDeleted == 1) {
            abort(404);
        }

        $filePath = "attachments/itineraries/{$attachment->id}.{$attachment->extension}";

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return response()->file(storage_path("app/public/{$filePath}"));
    }

    public function updateCoverPhoto(Request $request, ItineraryTrip $itinerary)
    {
        $request->validate([
            'image' => 'required|image|max:10240'
        ]);

        $itinerary->itineraryImages()->where('is_deleted', 0)->update(['is_deleted' => 1]);

        $file = $request->file('image');

        $extension = $file->getClientOriginalExtension();

        $image = ItineraryImage::create([
            'itinerary_id' => $itinerary->id,
            'name' => 'cover',
            'extension' => $extension,
            'is_deleted' => 0,
        ]);

        $file->storeAs('attachments/itineraries/covers',$image->id . '.' . $extension,'public');

        return response()->json([
            'success' => true,
            'image' => $image
        ]);
    }

    public function duplicate(Request $request, ItineraryTrip $itinerary)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $itinerary->load([
            'itineraryDays.events',
            'attachments',
            'itineraryImages'
        ]);

        $newItinerary = $itinerary->replicate();

        $newItinerary->name = $request->name;
        $newItinerary->date = $request->date;
        $newItinerary->created_by = auth()->id();
        $newItinerary->is_deleted = 0;

        $newItinerary->save();

        foreach ($itinerary->itineraryDays as $day) {

            if ($day->isDeleted == 1) {
                continue;
            }

            $newDay = $day->replicate();

            $newDay->itinerary_trip_id = $newItinerary->id;

            $newDay->save();

            foreach ($day->events as $event) {

                if ($event->isDeleted == 1) {
                    continue;
                }

                $newEvent = $event->replicate();

                $newEvent->itineraryEventDayId = $newDay->id;

                $newEvent->save();
            }
        }

        foreach ($itinerary->attachments as $attachment) {

            if ($attachment->isDeleted == 1) {
                continue;
            }

            $newAttachment = $attachment->replicate();

            $newAttachment->trip_id = $newItinerary->id;

            $newAttachment->save();

            $oldPath = storage_path('app/public/attachments/itineraries/' . $attachment->id . '.' . $attachment->extension);

            $newPath = storage_path('app/public/attachments/itineraries/' . $newAttachment->id . '.' . $newAttachment->extension);

            if (file_exists($oldPath)) {
                copy($oldPath, $newPath);
            }
        }

        foreach ($itinerary->itineraryImages as $image) {

            if ($image->is_deleted == 1) {
                continue;
            }

            $newImage = $image->replicate();

            $newImage->itinerary_id = $newItinerary->id;

            $newImage->save();

            $oldPath = storage_path('app/public/attachments/itineraries/covers/' . $image->id . '.' . $image->extension);

            $newPath = storage_path('app/public/attachments/itineraries/covers/' . $newImage->id . '.' . $newImage->extension);

            if (file_exists($oldPath)) {
                copy($oldPath, $newPath);
            }
        }

        return response()->json([
            'success' => true,
            'redirect' => route('itinerary.index')
        ]);
    }
}
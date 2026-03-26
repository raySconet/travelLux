<form method="POST"
      action="{{ $isNewTimelineTask 
      ? route('timelineTask.store') 
      : route('timelineTask.update', $timelineTask->id ) }}">
    @csrf
    @if(!$isNewTimelineTask)
        @method('PUT')
    @endif        
    <x-app-layout>
        <x-slot name="header">
            <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
                    <h2 class=" text-xl text-gray-500 leading-tight">
                        <i class="fa-solid fa-clock mr-2 text-[#f18325]"></i>{{ __('Timeline Tasks') }}
                    </h2>

                    @if($isNewTimelineTask)
                        <div class="space-x-2">
                            <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Task</span></x-secondary-btn>
                            <x-primary-btn type="button" onclick="window.location='{{ route('timelinetasks') }}'"><i class="far fa-minus-square"></i><span>Close Task</span></x-primary-btn>
                        </div>
                    @else    
                        <div class="space-x-2">
                            <form method="POST" action="{{ route('timelineTask.destroy', $timelineTask->id)}}" class="inline delete-form">
                                @csrf
                                @method('DELETE')

                                <x-secondary-buttonToDelete type="button" onclick="openDeleteModal(this)">
                                    <i class="fas fa-trash"></i><span>Delete</span>
                                </x-secondary-buttonToDelete>
                            </form>
                            <x-secondary-btn type="submit"><i class="fas fa-save"></i><span>Save Task</span></x-secondary-btn>
                            <x-primary-btn type="button" onclick="window.location='{{ route('timelinetasks') }}'"><i class="far fa-minus-square"></i><span>Close Task</span></x-primary-btn>
                        </div>
                    @endif    
            </div>
        </x-slot>

        <div class="mx-auto py-2 px-4 grid grid-cols-1 gap-6 ml-2 mr-1">
            <div class="p-3 bg-white shadow sm:rounded-lg">
                @include('timeline-tasks.partials.product-info')
            </div>
        </div>
    </x-app-layout>
</form>
<x-delete-modal />
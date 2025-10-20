<section class="mt-1">
    <div class="text-center">
        <x-primary-btn id="openManageSectionModal" class="mb-2  ">
            <i class="fas fa-layer-group"></i>
            <span class="ml-2">{{ __('Manage Sections') }}</span>
        </x-primary-btn>
    </div>
        <h2 class="text-xl  text-gray-900 text-center">
            {{ __('To do checklists') }}
        </h2>

    <p class="mb-1 text-md text-gray-600 text-center">
        Drag & drop the following to dos that are in red into the “To Do:” section of the middle column as the case unfolds.<br>
        (Add/change to do based the situation)
    </p>

    <div class="  mt-3 text-green-800">
        <h2  class="text-lg   text-center">Facilitating Settlement</h2>
        <p class="mb-1 text-md  ">
            Once the insurance company has either paid limits OR made a reasonable settlement offer and they are at their top:
        </p>
    </div>

    <div  class="  mt-1 text-red-800">
        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 w-full">
            <div class="2xl:col-span-1 flex flex-col ">
                <button title="Mark task as complete" class="incompleteButton buttonClickForComplete">
                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                </button>
            </div>
            <div class="2xl:col-span-11 flex flex-col ">
                <p>Call adjuster ask for the release (if case was in LIT, ask OC for release)</p>
            </div>
        </div>
    </div>
    <div class="  mt-4 text-green-800">
        {{-- <h2  class="text-lg   text-center">Facilitating Settlement</h2> --}}
        <p class="mb-1 text-md  ">
           Once liens are finalized/resolved & we have release:
        </p>
    </div>

    <div  class="  mt-1 text-red-800">
        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 w-full">
            <div class="2xl:col-span-1 flex flex-col ">
                <button title="Mark task as complete" class="incompleteButton buttonClickForComplete">
                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                </button>
            </div>
            <div class="2xl:col-span-11 flex flex-col ">
                <p>Draft DISBURSAL or ATTY REDUX DISBURSAL (if reducing atty fees)</p>
            </div>
        </div>
        <div class="grid grid-cols-1 mt-1 2xl:grid-cols-12 gap-4 w-full">
            <div class="2xl:col-span-1 flex flex-col ">
                <button title="Mark task as complete" class="incompleteButton buttonClickForComplete">
                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                </button>
            </div>
            <div class="2xl:col-span-11 flex flex-col ">
                <p>Give to NMW for edits</p>
            </div>
        </div>
        <div class="grid grid-cols-1  mt-1  2xl:grid-cols-12 gap-4 w-full">
            <div class="2xl:col-span-1 flex flex-col ">
                <button title="Mark task as complete" class="incompleteButton buttonClickForComplete">
                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                </button>
            </div>
            <div class="2xl:col-span-11 flex flex-col ">
                <p>Update and finalize disbursal post-NMW edits</p>
            </div>
        </div>
        <div class="grid grid-cols-1 mt-1  2xl:grid-cols-12 gap-4 w-full">
            <div class="2xl:col-span-1 flex flex-col ">
                <button title="Mark task as complete" class="incompleteButton buttonClickForComplete">
                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                </button>
            </div>
            <div class="2xl:col-span-11 flex flex-col ">
                <p>Call client, PR them</p>
            </div>
        </div>
        <div class="grid grid-cols-1 mt-1  2xl:grid-cols-12 gap-4 w-full">
            <div class="2xl:col-span-1 flex flex-col ">
                <button title="Mark task as complete" class="incompleteButton buttonClickForComplete">
                    <i class="fas fa-check" style="font-size:10px; color:white; vertical-align:top; margin-top:4px"></i>
                </button>
            </div>
            <div class="2xl:col-span-11 flex flex-col ">
                <p>Have client come into the office and sign release and disbursal (if we already have check, give client check)</p>
            </div>
        </div>
    </div>


    <div id="displayTodosHere">
    </div>
</section>

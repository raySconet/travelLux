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




    <div id="displayTodosHere">
    </div>
</section>

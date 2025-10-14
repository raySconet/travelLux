<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permissions') }}
        </h2>
    </x-slot>

    <div class="py-4 text-sm">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form id="permissionsForm" action="{{ route('permissions.update') }}" method="POST">
                @csrf

                <div class="relative grid grid-cols-12 font-bold bg-white overflow-hidden shadow-xs sm:rounded-lg border border-gray-200">
                    <div class="col-span-3 p-6 text-gray-900">
                        {{ __("Lawyers") }}
                    </div>
                    <div class="col-span-3 p-6 text-gray-900">
                        {{ __("Super Admin") }}
                    </div>
                    <div class="col-span-3 p-6 text-gray-900">
                        {{ __("Admin") }}
                    </div>
                    <div class="col-span-3 p-6 text-gray-900">
                        {{ __("User") }}
                    </div>
                    <button type="submit" class="absolute right-6 top-1/2 -translate-y-1/2 px-4 py-2 bg-[#14548d] text-white text-sm rounded cursor-pointer">
                        Save
                    </button>
                </div>
                <div id="userPermissionTable" class="relative overflow-hidden shadow-xs sm:rounded-lg mt-2 border border-gray-200">
                    @include('permissions.user-permission-table', ['users' => $users])
                </div>
            </form>
        </div>
    </div>

    <x-general-modal id="deleteUserConfirmModal" class="hidden">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation fa-xl text-red-600"></i>
                <h2 class="text-xl font-semibold text-gray-800">Confirm Deletion</h2>
            </div>
            <i
                id="closeUserDeleteConfirmModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                aria-label="Close"
            ></i>
        </x-slot>

        {{-- Body content --}}
        <div class="text-sm text-gray-700 space-y-4">
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <button id="cancelUserDeleteBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm rounded cursor-pointer">
                    Cancel
                </button>
                <x-danger-button id="confirmUserDeleteBtn">
                    Yes, Delete
                </x-danger-button>
            </div>
        </x-slot>
    </x-general-modal>
</x-app-layout>

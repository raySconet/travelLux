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
                    <button type="submit" class="absolute right-6 top-1/2 -translate-y-1/2 px-4 py-2 bg-[#14548d] text-white text-sm rounded">
                        Save
                    </button>
                </div>
                <div class="relative overflow-hidden shadow-xs sm:rounded-lg mt-2 border border-gray-200">
                    @foreach($users as $user)
                        @php
                            $disableInputs = ($user->id === auth()->id() && $user->isSuperAdmin());
                        @endphp
                        <div
                            id="userRow{{ $user->id }}"
                            class="user-row relative grid grid-cols-12
                            {{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-[#f3f4f6]' }}
                            {{ !$loop->last ? 'border-b border-gray-200' : ''}}
                            shadow-xl">
                            <div class="col-span-3 p-6 text-gray-900 font-semibold">
                                {{ $user->name }} {{ $user->id === auth()->id() ? '(You)' : '' }}
                            </div>
                            <div class="col-span-3 p-6 text-gray-900">
                                <label class="relative w-[max-content] flex justify-center items-center">
                                    <input
                                        type="radio"
                                        name="permissions[{{ $user->id }}]"
                                        value="super_admin"
                                        {{ $user->isSuperAdmin() ? 'checked' : '' }}
                                        {{ $disableInputs ? 'disabled' : '' }}
                                        class="{{ $disableInputs ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer' }} peer appearance-none h-[18px] w-[18px] bg-white border border-gray-300 rounded-sm checked:bg-blue-600 checked:border-transparent focus:outline-none"
                                    >
                                    <i class="fa-solid fa-check fa-xs absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block hidden pointer-events-none cursor-pointer"></i>
                                </label>
                            </div>
                            <div class="col-span-3 p-6 text-gray-900">
                                <label class="relative w-[max-content] flex justify-center items-center">
                                    <input
                                        type="radio"
                                        name="permissions[{{ $user->id }}]"
                                        value="admin"
                                        {{ $user->isAdmin() ? 'checked' : '' }}
                                        {{ $disableInputs ? 'disabled' : '' }}
                                        class="{{ $disableInputs ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer' }} peer appearance-none h-[18px] w-[18px] bg-white border border-gray-300 rounded-sm checked:bg-blue-600 checked:border-transparent focus:outline-none"
                                    >
                                    <i class="fa-solid fa-check fa-xs absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block hidden pointer-events-none cursor-pointer"></i>
                                </label>
                            </div>
                            <div class="col-span-3 p-6 text-gray-900">
                                <label class="relative w-[max-content] flex justify-center items-center">
                                    <input
                                        type="radio"
                                        name="permissions[{{ $user->id }}]"
                                        value="user"
                                        {{ $user->isRegularUser() ? 'checked' : '' }}
                                        {{ $disableInputs ? 'disabled' : '' }}
                                        class="{{ $disableInputs ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer' }} peer appearance-none h-[18px] w-[18px] bg-white border border-gray-300 rounded-sm checked:bg-blue-600 checked:border-transparent focus:outline-none"
                                    >
                                    <i class="fa-solid fa-check fa-xs absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block hidden pointer-events-none cursor-pointer"></i>
                                </label>
                            </div>
                            <div aria-label="Delete item" class="deleteUserBtn group absolute top-1/2 right-4 transform -translate-y-1/2 p-1 border-none bg-transparent cursor-pointer text-[0.875em] transition-transform duration-200 ease-in-out" data-user-id="{{ $user->id }}">
                                <svg
                                    class="w-10 h-8 transition-transform duration-[300ms] [transition-timing-function:cubic-bezier(0.34,1.56,0.64,1)] drop-shadow-sm overflow-visible group-hover:scale-[1.08] group-hover:rotate-[3deg] group-active:scale-[0.96] group-active:rotate-[-1deg] mb-1"
                                    viewBox="0 -10 64 74"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g id="trash-can">
                                    <rect x="16" y="24" width="32" height="30" rx="3" ry="3" fill="#e74c3c"></rect>
                                        <g class="transition-transform duration-[300ms] [transform-origin:12px_18px] [transition-timing-function:cubic-bezier(0.34,1.56,0.64,1)] group-hover:[transform:rotate(-28deg)_translateY(2px)] group-active:[transform:rotate(-12deg)_scale(0.98)]">
                                            <rect x="12" y="12" width="40" height="6" rx="2" ry="2" fill="#c0392b"></rect>
                                            <rect x="26" y="8" width="12" height="4" rx="2" ry="2" fill="#c0392b"></rect>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    @endforeach
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

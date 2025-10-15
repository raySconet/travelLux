@php
    $user = auth()->user();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permissions') }}
            </h2>
            @if ($user && $user->isSuperAdmin())
                <x-primary-btn id="openAddUserModal">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="ml-2">{{ __('Add user') }}</span>
                </x-primary-btn>
            @endif
        </div>
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

    <x-general-modal id="addUserModal" class="hidden m-auto max-w-xl">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fa-solid fa-user-plus shadow-lg fa-xl primary-color" style="color: #14548d;"></i>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Add User') }}
                </h2>
            </div>
            <i
                id="closeAddUserModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                data-bs-dismiss="modal"
                aria-label="Close">
            </i>
        </x-slot>

        <form method="POST" id="addUserForm" action="{{ route('store.user') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter a name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter an email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="Enter a password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </form>

        {{-- @include('permissions.add-user-form') --}}

        <x-slot name="footer">
            <x-primary-btn class="ml-auto" form="addUserForm" id="submitAddUserBtn">
                {{ __('Add User') }}
            </x-primary-btn>
        </x-slot>
    </x-general-modal>

    <x-general-modal id="editUserModal" class="hidden m-auto max-w-xl">
        <x-slot name="header">
            <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                <i class="fa-solid fa-user-plus shadow-lg fa-xl primary-color" style="color: #14548d;"></i>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Edit User') }}
                </h2>
            </div>
            <i
                id="closeEditUserModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                role="button"
                data-bs-dismiss="modal"
                aria-label="Close">
            </i>
        </x-slot>

        <form method="POST" id="editUserForm" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <x-input-label for="edit_name" :value="__('Name')" />
                <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter a name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="edit_email" :value="__('Email')" />
                <x-text-input id="edit_email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter an email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="edit_password" :value="__('Password')" />

                <x-text-input id="edit_password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                autocomplete="new-password" placeholder="Leave blank to keep current password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="edit_password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="edit_password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" autocomplete="new-password" placeholder="Confirm password if changed" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </form>

        {{-- @include('permissions.add-user-form') --}}

        <x-slot name="footer">
            <x-primary-btn class="ml-auto" form="editUserForm" id="submitEditUserBtn">
                {{ __('Update User') }}
            </x-primary-btn>
        </x-slot>
    </x-general-modal>
</x-app-layout>

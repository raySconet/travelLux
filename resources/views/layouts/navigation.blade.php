@php
    $user = auth()->user();
@endphp

<nav x-data="{ open: false, notificationsOpen: false }" class="bg-[#292727] w-full">
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>


    <!-- Primary Navigation Menu -->
    <div class="max-w-360 mx-auto w-full">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8 flex-shrink-0">
                <!-- Logo -->
                <div class="flex items-center">

                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8">

                </div>
            </div>

            <div class="hidden sm:flex items-center gap-4 flex-wrap">
                <span class="text-white text-base">
                    Welcome, {{ Auth::user()->fname . ' ' . Auth::user()->lname }}
                </span>

                <a href="/profile" class="text-white text-base flex items-center gap-1">
                    <i class="fas fa-user"></i>
                    My Profile
                </a>

                <a href="/agentDashboard" class="text-white text-base flex items-center gap-1">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a>

                <div class="relative">

                    <a href="#" @click.prevent="notificationsOpen = !notificationsOpen" class="text-white text-base flex items-center gap-2">

                        <div class="relative">

                            <i class="fas fa-bell text-lg"></i>

                            @if($notificationCounter)
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full text-[10px] leading-4 min-w-4 h-4 px-1 flex items-center justify-center">
                                    {{ $notificationCounter }}
                                </span>
                            @endif

                        </div>

                        <span>Notifications</span>

                    </a>
                    @if($notifications->count())

                        <div x-show="notificationsOpen" @click.outside="notificationsOpen = false" x-transition class="absolute left-0 mt-2 w-70 bg-white shadow-lg rounded border z-50 text-base">

                            @foreach($notifications as $notification)

                                <div id="notification-{{ $notification->id }}" class="flex border-b p-3">

                                    <div class="mr-3">
                                        <a href="#" class="markNotificationAsRead text-[#B6844A]" data-id="{{ $notification->id }}">
                                            <i title="Mark as Read" class="far fa-check-circle text-lg mt-4"></i>
                                        </a>
                                    </div>
                                    <div class="flex-1 notificationItem cursor-pointer" data-url="{{ $notification->module_name === 'customers' ? route('customers.customerDetails', $notification->record_id) : route('reservations.reservationDetails', $notification->record_id) }}" data-notificationid="{{ $notification->id }}">
                                        <div>{{ $notification->message }}</div>

                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($notification->date)->format('F d, Y h:i A') }}
                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif
                </div>

                <a href="#" onclick="openLogoutModal()" class="text-white text-base flex items-center gap-1">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
           
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if ($user && $user->isSuperAdmin())
                    <x-responsive-nav-link :href="route('permissions')" :active="request()->routeIs('permissions')">
                        {{ __('Manage users') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<x-logout-modal />

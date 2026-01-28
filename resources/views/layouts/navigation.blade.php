@php
    $user = auth()->user();
@endphp

<nav x-data="{ open: false }" class="bg-[#f18325] fixed top-0  w-full z-50" style="float:right; ">
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>


    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto w-full">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8 flex-shrink-0">
                <!-- Logo -->
                <div class="flex items-center">
                    {{-- <a href="{{ route('calendar') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a> --}}
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8">
                    {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link> --}}
                    {{-- @if ($user && $user->isSuperAdmin())
                    <x-nav-link :href="route('insurance.index')" :active="request()->routeIs('insurance.index')">
                        {{ __('Insurance') }}
                    </x-nav-link>
                    @endif --}}
                </div>
            </div>

            <!-- Settings Dropdown -->
            {{-- <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <span class="text-white text-base">Welcome, {{ Auth::user()->name }}</span>
                        <a href="/profile" class="block py-1  sidebarA">My Profile <i class="fas fa-user"></i> </a>
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white  focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if ($user && $user->isSuperAdmin())
                            <x-dropdown-link :href="route('permissions')" :active="request()->routeIs('permissions')">
                                {{ __('Manage users') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div> --}}

            <div class="hidden sm:flex items-center gap-4 flex-wrap">
                <span class="text-white text-base">
                    Welcome, {{ Auth::user()->name }}
                </span>

                <a href="/profile" class="text-white text-base flex items-center gap-1">
                    <i class="fas fa-user"></i>
                    My Profile
                </a>

                <a href="#" class="text-white text-base flex items-center gap-1">
                    <i class="fas fa-chart-line"></i>
                        Dashboard
                </a>
                
                <a href="#" class="text-white text-base flex items-center gap-1">
                    <i class="fas fa-bell"></i>
                        Notifications
                </a>
                
                <a href="#" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-white text-base flex items-center gap-1">
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
             {{-- <x-responsive-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">
                {{ __('Calendar') }}
            </x-responsive-nav-link> --}}
        </div>

        <!-- Responsive Settings Options -->
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

                <!-- Authentication -->
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

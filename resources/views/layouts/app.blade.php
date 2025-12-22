<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Travelux') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css" />

        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif


    </head>
    <body class="font-sans "
        x-data="{ sidebarOpen: true, first: true, second: true, third: true }">
        @include('layouts.sidebar')


        <!-- MAIN WRAPPER -->
        <div class="min-h-screen bg-gray-100 transition-all duration-500"
            :class="sidebarOpen ? 'md:ml-64' : 'md:ml-0'">

            <!-- TOP NAV (your existing navigation) -->
            <div class="flex items-center bg-[#f18325]  shadow px-4">
               <a id="menu-toggle"
                    @click="sidebarOpen = !sidebarOpen;
                            first = !first; second = !second; third = !third;"
                    href="javascript:void(0);"
                    class="sidebarBtn flex items-center justify-center z-100 w-10 h-16">
                        <div class="menuIcon">
                            <div class="line1" :class="first ? 'animateFirstLine' : ''"></div>
                            <div class="line2" :class="second ? 'animateSecondLine' : ''"></div>
                            <div class="line3" :class="third ? 'animateThirdLine' : ''"></div>
                        </div>
                </a>

                @include('layouts.navigation')
            </div>

            <!-- PAGE HEADING -->
            @isset($header)
                <header class="shadow text-white mt-2">
                    <div class=" mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- PAGE CONTENT -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>


        <!-- MODALS (unchanged) -->
        <x-general-modal id="errorModal" class="hidden">
            <x-slot name="header">
                <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation fa-xl text-yellow-600"></i>
                    <h2 class="text-xl font-semibold text-yellow-700">Attention</h2>
                </div>
                <i id="closeErrorModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 cursor-pointer"
                role="button"></i>
            </x-slot>
            <div id="modalErrorContent" class="text-sm text-gray-700 space-y-2"></div>
        </x-general-modal>

        <x-general-modal id="successModal" class="hidden">
            <x-slot name="header">
                <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                    <i class="fa-solid fa-circle-check fa-xl text-green-600"></i>
                    <h2 class="text-xl font-semibold text-green-700">Success</h2>
                </div>
                <i id="closeSuccessModal"
                class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 cursor-pointer"
                role="button"></i>
            </x-slot>
            <div id="modalSuccessContent" class="text-sm text-gray-700 space-y-2"></div>
        </x-general-modal>

        <!-- EXISTING SCRIPTS (unchanged) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

        @if(request()->is('caseInfo') || request()->is('caseInfo/*'))
            @vite('resources/js/courtCases.js')
        @endif

        @if(request()->is('permissions') || request()->is('permissions/*'))
            @vite('resources/js/permissions.js')
        @endif

        <div id="ajaxLoader"
            class="fixed top-0 left-0 w-full h-full z-50 flex items-center justify-center"
            style="background-color: rgba(0, 0, 0, 0.1); display:none;">
            <div class="loader h-12 w-12 border-4 border-t-[#14548d] border-gray-200 rounded-full animate-spin"></div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    </body>

</html>

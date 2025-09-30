<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else

        @endif
           @if(request()->is('calendar') || request()->is('calendar/*')) {{-- added by rony the king  --}}
            @vite('resources/css/calendar.css')

        @endif
        @if(request()->is('category') || request()->is('category/*')) {{-- added by rony --}}
            @vite('resources/css/category.css')
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset




            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <x-general-modal id="errorModal" class="hidden">
            <x-slot name="header">
                <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                    <!-- Triangle with exclamation icon -->
                    <i class="fa-solid fa-triangle-exclamation fa-xl text-yellow-600"></i>
                    <h2 class="text-xl font-semibold text-yellow-700">Attention</h2>
                </div>
                <i
                    id="closeErrorModal"
                    class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                    role="button"
                    aria-label="Close">
                </i>
            </x-slot>

            <div id="modalErrorContent" class="text-sm text-gray-700 space-y-2"></div>
        </x-general-modal>

        <x-general-modal id="successModal" class="hidden">
            <x-slot name="header">
                <div class="grid grid-cols-[auto_1fr] items-center gap-2">
                    <!-- Check circle icon -->
                    <i class="fa-solid fa-circle-check fa-xl text-green-600"></i>
                    <h2 class="text-xl font-semibold text-green-700">Success</h2>
                </div>
                <i
                    id="closeSuccessModal"
                    class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
                    role="button"
                    aria-label="Close">
                </i>
            </x-slot>

            <div id="modalSuccessContent" class="text-sm text-gray-700 space-y-2"></div>
        </x-general-modal>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> {{-- added by rony --}}

        @if(request()->is('calendar') || request()->is('calendar/*')) {{-- added by rony the king  --}}
            @vite('resources/js/calendar.js')

        @endif

        @if(request()->is('category') || request()->is('category/*')) {{-- added by rony --}}
            @vite('resources/js/category.js')
        @endif

         @if(request()->is('caseInfo') || request()->is('caseInfo/*')) {{-- added by rony --}}
            @vite('resources/js/courtCases.js')
        @endif



    <div id="ajaxLoader" class="fixed top-0 left-0 w-full h-full z-50 flex items-center justify-center " style="background-color: rgba(0, 0, 0, 0.1); display:none;">
        <div class="loader h-12 w-12 border-4 border-t-[#14548d] border-gray-200 rounded-full animate-spin"></div>
    </div>



        {{-- Flatpickr JS --}}
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    </body>
</html>

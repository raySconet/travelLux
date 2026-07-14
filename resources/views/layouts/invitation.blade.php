@props([
    'bodyClass' => 'bg-gray-100',
    'containerClass' => 'bg-gray-100 px-4',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Travelux') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/administration.css'])
    @vite(['resources/js/customers.js'])

    @if(request()->routeIs('customer.invitation') || request()->routeIs('invitation.submit'))
        @vite('resources/js/invitations.js')
    @endif

    @if(request()->routeIs('customer.intake-form') || request()->routeIs('customer.intake-form.submit'))
        @vite('resources/js/intakeForm.js')
    @endif

    @if(request()->routeIs('form.show') || request()->routeIs('form.submit'))
        @vite('resources/js/customerForms.js')
    @endif

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="font-sans text-gray-900 antialiased {{ $bodyClass }}">

    <div class="min-h-screen {{ $containerClass }} px-4">
        <div class="max-w-[1500px] mx-auto">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
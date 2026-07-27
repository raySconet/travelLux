@props([
    'name'=> '',
    'class' => '',
    'id' => null,
])
<div
    x-data="{ show: false }"

    x-on:open-modal.window="
        if ($event.detail === '{{ $name }}') show = true
    "

    x-on:close-modal.window="
        if ($event.detail === '{{ $name }}') show = false
    "

    x-show="show"
    x-transition.opacity

    x-on:keydown.escape.window="show = false"

    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    style="display:none;"
>

    <div
        @click.outside="show = false"
        class="bg-white rounded-lg shadow-2xl w-full max-w-3xl relative border border-gray-300 {{ $class }}"
        id="{{ $id }}"
    >

        @isset($header)
            <div class="flex justify-between items-center p-4">
                {{ $header }}
            </div>
        @endisset

        <hr class="border-gray-200">
        <div class="p-2">
            {{ $slot }}
        </div>

        @isset($footer)
            <hr class="border-gray-200">

            <div class="p-4">
                {{ $footer }}
            </div>
        @endisset

    </div>

</div>

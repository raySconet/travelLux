@props(['id' => '', 'class' => ''])

<button
    id="{{ $id ?? '' }}"
    {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-[#f18325] text-white font-semibold py-2 px-4 rounded cursor-pointer ' . $class]) }}>
    {{ $slot }}
</button>

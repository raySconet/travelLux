@props(['class' => '', 'id' => null])

<button
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'bg-[#e4e9ee42] border border-[#d2d8dd] text-gray-800 py-2 px-4 rounded cursor-pointer ' . $class]) }}>
    {{ $slot }}
</button>

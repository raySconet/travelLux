@props(['class' => '', 'id' => null])

<div
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'block px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden hover:bg-[#eaf1ff] cursor-pointer w-31 ' . $class]) }}>
    {{ $slot }}
</div>

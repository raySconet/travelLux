@props(['class' => '', 'id' => null])

<div
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'flex items-center w-full ' . $class]) }}>
    {{ $slot }}
</div>

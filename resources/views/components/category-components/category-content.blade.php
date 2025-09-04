@props(['class' => '', 'id' => null])

<div
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'panel p-2 ' . $class]) }}>
    {{ $slot }}
</div>

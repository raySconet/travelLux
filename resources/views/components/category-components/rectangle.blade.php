@props(['class' => '', 'id' => null])

<div
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'w-[13px] h-[28px] border ' . $class]) }}>
    {{ $slot }}
</div>

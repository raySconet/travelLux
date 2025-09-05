@props(['class' => '', 'id' => null])

<div
    {{ $attributes->merge([
        'class' => 'grid grid-cols-12 gap-2 mb-1 ' . $class,
        'id' => $id,
    ]) }}
>
    {{ $slot }}
</div>

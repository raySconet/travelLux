@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1  gap-2 w-full ' . $class]) }}>
    {{ $slot }}
</div>

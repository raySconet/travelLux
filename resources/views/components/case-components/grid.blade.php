@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1  gap-0.5 w-full ' . $class]) }}>
    {{ $slot }}
</div>

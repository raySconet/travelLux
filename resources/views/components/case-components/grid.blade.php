@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1 2xl:grid-cols-12 gap-2 w-full ' . $class]) }}>
    {{ $slot }}
</div>

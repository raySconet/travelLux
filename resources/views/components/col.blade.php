@props(['class' => ''])

<div {{ $attributes->merge(['class' => ' flex flex-col ' . $class]) }}>
    {{ $slot }}
</div>

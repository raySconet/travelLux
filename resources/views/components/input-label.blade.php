@props(['value',  'id' => null, 'class' => ''])

<label
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'block  text-gray-700 ' . $class]) }}>
        {{ $value ?? $slot }}
</label>

@props(['value',  'id' => null, 'class' => ''])

<label
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'block  text-gray-700 text-center' . $class]) }}>
        {{ $value ?? $slot }}
</label>

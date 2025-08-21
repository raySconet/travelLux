@props(['class' => '', 'id' => null])

<input
    type="checkbox"
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer ' . $class]) }}
>


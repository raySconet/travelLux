@props([
    'disabled' => false,
    'checked' => false,
    'id' => null,
    'class' => '',
])

<input
    type="radio"
    @if($checked) checked @endif
    @if($disabled) disabled @endif
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge([
        'class' => 'text-indigo-600 border-gray-300 focus:ring-indigo-500 ' . $class
    ]) }}
>

@props(['id' => null, 'class' => ''])

<button
    type="button"
    id="{{$id}}"
    {{ $attributes->merge(['class' => 'text-sm text-gray-600 hover:text-gray-800 justify-self-start ' . $class]) }}
>
    {{ $slot }}
</button>

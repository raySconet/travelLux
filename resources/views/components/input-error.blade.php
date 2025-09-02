@props(['messages', 'id' => null, 'class' => ''])

@if ($messages)
    <ul
        id="{{ $id }}"
        {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 ' . $class]) }}>
            @foreach ((array) $messages as $message)
                <li>{{ $message }}</li>
            @endforeach
    </ul>
@endif

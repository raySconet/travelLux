@props(['messages', 'id' => null, 'class' => ''])

@if ($messages)
    <ul
        id="{{ $id }}"
        {{ $attributes->merge(['class' => 'text-sm text-red-600  mt-1' . $class]) }}>
            @foreach ((array) $messages as $message)
                <li> <i class="fas fa-exclamation-triangle"></i>{{ $message }}</li>
            @endforeach
    </ul>
@endif

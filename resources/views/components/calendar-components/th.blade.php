@props(['class' => '', 'id' => null])

<th scope="col"
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'border border-[#fff] w-full p-2 ' . $class]) }}>
    {{ $slot }}
</th>

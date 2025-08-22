@props(['class' => '', 'id' => null])

<td
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'd-flex flex-column border border-[#fff] w-full calendarDay p-2 ' . $class]) }}>
    {{ $slot }}
</td>

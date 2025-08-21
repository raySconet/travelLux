@props(['class' => '', 'id' => null])

<td
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'd-flex flex-column min-h-[144px] border border-[#fff] calendarDay w-full p-2 ' . $class]) }}>
    {{ $slot }}
</td>

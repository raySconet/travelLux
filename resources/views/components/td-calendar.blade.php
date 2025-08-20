@props(['class' => ''])

<td {{ $attributes->merge(['class' => 'd-flex flex-column min-h-[110px] bg-[#f3f4f6] border border-[#fff] calendar-day w-full p-2 ' . $class]) }}>
    {{ $slot }}
</td>

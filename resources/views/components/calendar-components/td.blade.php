@props(['class' => ''])

<td {{ $attributes->merge(['class' => 'd-flex flex-column min-h-[110px] bg-[#eaf1ff] border border-[#fff] calendar-day w-full p-2 ' . $class]) }}>
    {{ $slot }}
</td>

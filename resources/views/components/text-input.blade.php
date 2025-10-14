@props(['disabled' => false,  'id' => null, 'class' => ''])

<input
    @disabled($disabled)
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'border border-[#00000014] p-1.5 h-[27px] outline-none text-center text-sm focus:border-indigo-400 w-full rounded-md shadow-xs ' . $class]) }}
>

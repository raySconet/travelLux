@props(['disabled' => false,  'id' => null, 'class' => ''])

<input
    @disabled($disabled)
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'border border-[#e1e1e1] p-1.5 h-[28px] outline-none text-sm focus:border-indigo-400 w-full rounded-md shadow-xs ' . $class]) }}
>

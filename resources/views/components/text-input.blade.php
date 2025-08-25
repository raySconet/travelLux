@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-[#e1e1e1] p-1.5 h-[26px] outline-none text-sm focus:border-indigo-400  w-full  rounded-md shadow-xs']) }}>

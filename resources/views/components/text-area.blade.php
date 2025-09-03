@props(['disabled' => false, 'id' => null, 'class' => ''])

<textarea
    @disabled($disabled)
    id="{{ $id }}"
    {{ $attributes->merge([
        'class' => 'border border-[#e1e1e1] p-1.5 outline-none text-sm h-[77px] focus:border-indigo-400 w-full rounded-md shadow-xs ' . $class
    ]) }}>
</textarea>

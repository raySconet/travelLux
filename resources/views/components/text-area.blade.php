@props(['disabled' => false, 'id' => null, 'class' => ''])

<textarea
    @disabled($disabled)
    id="{{ $id }}"
    {{ $attributes->merge([
        'class' => 'border  border-[#00000014] p-1.5 outline-none text-sm  focus:border-indigo-400 w-full rounded-md shadow-xs ' . $class
    ]) }}>
</textarea>

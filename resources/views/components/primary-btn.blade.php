@props(['id' => '', 'class' => ''])

<button
    id="{{ $id ?? '' }}"
    {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-[#14548d]  text-white font-semibold py-2 px-4 rounded cursor-pointer ' . $class]) }}>
    {{ $slot }}
</button>

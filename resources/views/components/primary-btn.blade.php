@props(['id' => '', 'class' => ''])

<button
    id="{{ $id ?? '' }}"
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent
                    hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A]
                    transition-all duration-200 ' . $class]) }}>
    {{ $slot }}
</button>
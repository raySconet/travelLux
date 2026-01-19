@props(['id' => '', 'class' => ''])

<button
    id="{{ $id ?? '' }}"
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'space-x-2 bg-[#f18325] text-white font-semibold py-2 px-8 rounded cursor-pointer border border-transparent
                    hover:bg-white hover:border-[#f18325] hover:text-[#f18325]
                    transition-all duration-200 ' . $class]) }}>
    {{ $slot }}
</button>
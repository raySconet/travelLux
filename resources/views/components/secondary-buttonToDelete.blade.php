@props(['class' => '', 'id' => null])

<button
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'space-x-2 bg-[#000] text-white font-semibold px-8 py-2 rounded cursor-pointer
                                       border border-transparent  hover:bg-white hover:border-[#000] hover:text-[#000]
                                       transition-all duration-200' . $class]) }}>
    {{ $slot }}
</button>
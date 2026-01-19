@props(['class' => '', 'id' => null])

<button
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd]
                    transition-all duration-200' . $class]) }}>
    {{ $slot }}
</button>

@props(['class' => '', 'id' => null])

<button
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'bg-[#eaf1ff] border border-[#cacaff] text-gray-800  py-2 px-4 rounded cursor-pointer ' . $class]) }}>
    {{ $slot }}
</button>

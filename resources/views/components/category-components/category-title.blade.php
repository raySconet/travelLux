@props(['class' => '', 'id' => null])

<div
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'flex items-center gap-2 flip bg-[#eaf1ff] border border-[#cacaff] text-gray-800  py-2 px-4 rounded cursor-pointer ' . $class]) }}>
    {{ $slot }}
</div>

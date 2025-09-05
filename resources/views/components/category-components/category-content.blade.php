@props(['class' => '', 'id' => null])

<div
    {{ $attributes->merge([
        'class' => 'panel bg-[#eaf1ff] border border-[#cacaff] text-gray-800 p-2 ' . $class,
        'id' => $id,
    ]) }}
>
    {{ $slot }}
</div>

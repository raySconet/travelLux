@props(['class' => '', 'id' => null, 'dataTarget' => null])

<div
    {{ $attributes->merge([
        'class' => 'flex items-center gap-2 flip bg-[#14548d] text-white py-2 px-4 rounded cursor-pointer ' . $class,
        'id' => $id,
        'data-target' => $dataTarget,
    ]) }}
>
    {{ $slot }}
</div>

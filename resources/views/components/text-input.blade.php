@props(['type' => 'text','id' => '', 'name' => '', 'value' => '', 'class' => '', 'placeholder' => ' ', ])

<input
    type="{{ $type }}"
    id="{{ $id }}"
    name="{{ $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    class="peer w-full border-b-2 border-[#bdbdbd] focus:outline-none focus:border-[#f18325] pt-5 pb-1 {{ $class }}"
/>

@props(['class' => '', 'id' => null])

<div
    {{ $attributes->merge([
        'class' => 'fixed inset-0 flex items-center justify-center z-50 hidden ' . $class,
        'id' => $id
    ]) }}
>
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl relative border border-gray-300">
        @isset($header)
            <div class="modal-title grid grid-cols-2 items-center p-6">
                {{ $header }}
            </div>
        @endisset

        <hr style="color: #cccccc80">

        <div class="modal-content p-6">
            {{ $slot }}
        </div>

        <hr style="color: #cccccc80">

        @isset($footer)
            <div class="modal-footer grid grid-cols-2 gap-4 p-6">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>

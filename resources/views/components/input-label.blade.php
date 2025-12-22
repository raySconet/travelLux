@props(['for' => '','class' => '',])

<label
    for="{{ $for }}"
    class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-5  peer-focus:top-1 peer-focus:text-sm  {{ $class }}">
    {{ $slot }}
</label>

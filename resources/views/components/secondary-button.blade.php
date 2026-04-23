@props(['disabled' => false, 'type' => 'button'])

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }} 
    {{ $attributes->merge(['class' => 'btn-secondary']) }}
>
    {{ $slot }}
</button>
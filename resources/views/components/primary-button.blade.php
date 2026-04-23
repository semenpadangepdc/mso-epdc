@props(['disabled' => false, 'type' => 'submit'])

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }} 
    {{ $attributes->merge(['class' => 'btn-submit']) }}
>
    {{ $slot }}
</button>
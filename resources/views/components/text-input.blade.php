@props(['disabled' => false, 'error' => false])

<input 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => 'field-input ' . ($error ? 'error' : '')]) !!}
>
@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'field-error']) }}>
        @foreach ((array) $messages as $message)
            <div>{{ $message }}</div>
        @endforeach
    </div>
@endif
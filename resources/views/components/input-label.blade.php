@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block text-sm font-medium text-accent mb-1 transition-colors duration-300'
]) }}>
    {{ $value ?? $slot }}
</label>


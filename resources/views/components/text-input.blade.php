@props(['disabled' => false])

<input 
    @disabled($disabled) 
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-3 border-secondary rounded-md shadow-sm bg-background text-text placeholder-text-secondary
                   focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50
                   disabled:opacity-50 disabled:cursor-not-allowed
                   transition-colors duration-300' . ($disabled ? ' opacity-50 cursor-not-allowed' : '')
    ]) }}
>


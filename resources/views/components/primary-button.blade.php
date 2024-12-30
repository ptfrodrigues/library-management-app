<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center px-6 py-3 bg-primary border border-transparent rounded-md font-sans text-sm font-medium text-white uppercase tracking-wider hover:bg-secondary focus:bg-secondary active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors duration-300'
]) }}>
    {{ $slot }}
</button>


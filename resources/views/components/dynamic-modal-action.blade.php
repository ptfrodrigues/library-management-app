@props(['item', 'route', 'action'])

@php
    $modalId = "dynamic-modal-{$action}-{$item->id}";
    $title = ucfirst($action);
    $buttonClass = $action === 'restore' ? 'bg-secondary hover:bg-primary' : 
                   ($action === 'force_delete' ? 'bg-tertiary hover:bg-primary' : 
                   'bg-tertiary hover:bg-primary');
    $message = $action === 'restore' ? 'Are you sure you want to restore this item?' :
               ($action === 'force_delete' ? 'Are you sure you want to permanently delete this item? This action cannot be undone.' :
               'Are you sure you want to delete this item?');
@endphp

<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-background rounded-lg shadow-xl p-8 max-w-sm w-full border border-secondary">
        <h2 class="text-2xl font-display font-bold mb-4 text-primary">Confirm {{ $title }}</h2>
        <p class="mb-6 text-text">{{ $message }}</p>
        <form action="{{ route($route, $item) }}" method="POST" class="flex justify-end space-x-4">
            @csrf
            @if($action === 'delete' || $action === 'force delete')
                @method('DELETE')
            @elseif($action === 'restore')
                @method('PUT')
            @endif
            <button type="button" onclick="closeActionModal('{{ $item->id }}', '{{$action}}')" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors duration-300">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 {{ $buttonClass }} text-white rounded-lg transition-colors duration-300">
                {{ $title }}
            </button>
        </form>
    </div>
</div>
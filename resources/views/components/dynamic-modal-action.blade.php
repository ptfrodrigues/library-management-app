@props(['item', 'route', 'action'])

@php
    $modalId = "dynamic-modal-{$action}-{$item->id}";
    $title = ucfirst($action);
    $buttonClass = $action === 'restore' ? 'bg-yellow-500 hover:bg-yellow-600' : 
                   ($action === 'force_delete' ? 'bg-red-600 hover:bg-red-700' : 
                   'bg-red-500 hover:bg-red-600');
    $message = $action === 'restore' ? 'Are you sure you want to restore this item?' :
               ($action === 'force_delete' ? 'Are you sure you want to permanently delete this item? This action cannot be undone.' :
               'Are you sure you want to delete this item?');
@endphp

<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
        <h2 class="text-2xl font-bold mb-4">Confirm {{ $title }}</h2>
        <p class="mb-4">{{ $message }}</p>
        <form action="{{ route($route, $item) }}" method="POST" class="flex justify-end space-x-2">
            @csrf
            @if($action === 'delete' || $action === 'force delete')
                @method('DELETE')
            @elseif($action === 'restore')
                @method('PUT')
            @endif
            <button type="button" onclick="closeActionModal('{{ $item->id }}', '{{$action}}')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 {{ $buttonClass }} text-white rounded">
                {{ $title }}
            </button>
        </form>
    </div>
</div>


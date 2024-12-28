@props(['item', 'fields', 'title'])

<div id="dynamic-modal-{{ $item->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-background rounded-lg shadow-xl p-8 max-w-3xl w-full max-h-[80vh] overflow-y-auto border border-secondary">
        <h2 class="text-3xl font-display font-bold mb-6 text-primary">{{ $title }}</h2>
        
        <div id="view-mode-{{ $item->id }}">
            <ul class="list-none space-y-4 text-sm text-text">
                @foreach($fields as $field)
                    @if(!in_array($field, ['password', 'remember_token', 'deleted_at']) || ($field === 'deleted_at' && $item->deleted_at !== null))
                        <li>
                            <strong class="text-accent">{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                            @if($field === 'permissions')
                                <ul class="list-disc pl-5 mt-2">
                                    @foreach($item->getAllPermissions() as $permission)
                                        <li>{{ str_replace('_', ' ', $permission->name) }}</li>
                                    @endforeach
                                </ul>
                            @elseif($field === 'roles')
                                {{ $item->roles->pluck('name')->implode(', ') }}
                            @elseif($field === 'authors')
                                <ul class="list-disc pl-5 mt-2">
                                    @foreach($item->authors as $author)
                                        <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                                    @endforeach
                                </ul>
                            @elseif($field === 'books')
                                <ul class="list-disc pl-5 mt-2">
                                    @foreach($item->books as $book)
                                        <li>{{ $book->title }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $item->$field }}
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="mt-8 flex justify-end">
            <button onclick="closeModal({{ $item->id }})" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-secondary transition-colors duration-300">
                Close
            </button>
        </div>
    </div>
</div>

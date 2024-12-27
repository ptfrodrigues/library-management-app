@props(['item', 'fields', 'title'])

<div id="dynamic-modal-{{ $item->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">{{ $title }}</h2>
        
        <div id="view-mode-{{ $item->id }}">
            <ul class="list-none space-y-2 text-sm text-gray-600">
                @foreach($fields as $field)
                    @if(!in_array($field, ['password', 'remember_token']))
                        <li>
                            <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                            @if($field === 'permissions')
                                <ul class="list-disc pl-5">
                                    @foreach($item->getAllPermissions() as $permission)
                                        <li>{{ str_replace('_', ' ', $permission->name) }}</li>
                                    @endforeach
                                </ul>
                            @elseif($field === 'roles')
                                {{ $item->roles->pluck('name')->implode(', ') }}
                            @elseif($field === 'authors')
                                <ul class="list-disc pl-5">
                                    @foreach($item->authors as $author)
                                        <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                                    @endforeach
                                </ul>
                            @elseif($field === 'books')
                                <ul class="list-disc pl-5">
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

        <div class="mt-6 flex justify-end">
            <button onclick="closeModal({{ $item->id }})" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-150 ease-in-out">
                Close
            </button>
        </div>
    </div>
</div>


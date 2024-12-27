<style>
    .content-cell {
        max-width: none;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }
    .trashed-row {
        background-color: #FEF2F2;
    }
</style>

@props(['items', 'tableFields'])

<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach($tableFields as $field)
                    @if($field !== 'deleted_at')
                        @if($field === 'isbn' || $field === 'authors' || $field === 'books')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap hidden lg:table-cell">
                                {{ str_replace('_', ' ', $field) }}
                            </th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                {{ str_replace('_', ' ', $field) }}
                            </th>
                        @endif
                    @endif
                @endforeach
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap items-center">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
                <tr class="{{ $item->deleted_at ? 'trashed-row' : '' }}">
                    @foreach($tableFields as $field)
                        @if($field !== 'deleted_at')
                            @if($field === 'isbn' || $field === 'authors' || $field === 'books')
                                <td class="px-6 py-4 text-sm text-gray-500 content-cell hidden lg:table-cell">
                                    @if($field === 'roles')
                                        <span class="content-cell">{{ $item->roles->pluck('name')->implode(', ') }}</span>
                                    @elseif($field === 'authors')
                                        <span class="content-cell">{{ $item->authors->pluck('first_name')->map(function($firstName, $key) use ($item) {
                                            return $firstName . ' ' . $item->authors[$key]->last_name;
                                        })->implode(', ') }}</span>
                                    @elseif($field === 'books')
                                        <span class="content-cell">{{ $item->books->pluck('title')->implode(', ') }}</span>
                                    @elseif(is_object($item->$field) && method_exists($item->$field, 'pluck'))
                                        <span class="content-cell">{{ $item->$field->pluck('name')->implode(', ') }}</span>
                                    @else
                                        {{ $item->$field }}
                                    @endif
                                </td>
                            @else
                                <td class="px-6 py-4 text-sm text-gray-500 content-cell">
                                    @if($field === 'roles')
                                        <span class="content-cell">{{ $item->roles->pluck('name')->implode(', ') }}</span>
                                    @elseif($field === 'authors')
                                        <span class="content-cell">{{ $item->authors->pluck('first_name')->map(function($firstName, $key) use ($item) {
                                            return $firstName . ' ' . $item->authors[$key]->last_name;
                                        })->implode(', ') }}</span>
                                    @elseif($field === 'books')
                                        <span class="content-cell">{{ $item->books->pluck('title')->implode(', ') }}</span>
                                    @elseif(is_object($item->$field) && method_exists($item->$field, 'pluck'))
                                        <span class="content-cell">{{ $item->$field->pluck('name')->implode(', ') }}</span>
                                    @else
                                        {{ $item->$field }}
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endforeach
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center justify-center h-full space-x-2">
                            <button onclick="openModal({{ $item->id }})" class="text-blue-500 hover:text-blue-700 inline-flex items-center px-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1">View</span>
                            </button>
                            @if($item->deleted_at)
                                @if(auth()->user()->can('edit_' . Str::plural(strtolower(class_basename($item)))))
                                    <button onclick="openActionModal({{ $item->id }}, 'restore')" class="text-yellow-500 hover:text-yellow-700 inline-flex items-center px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-1">Restore</span>
                                    </button>
                                @endif
                                @if(auth()->user()->can('delete_' . Str::plural(strtolower(class_basename($item)))))
                                    <button onclick="openActionModal({{ $item->id }}, 'force delete')" class="text-red-500 hover:text-red-700 inline-flex items-center px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-1">Force</span>
                                    </button>
                                @endif
                            @else
                                @if(auth()->user()->canAny(['edit_books', 'edit_users', 'edit_authors']) && 
                                    !(isset($item->roles) && $item->roles->contains('name', 'member')))
                                    <button onclick="openEditModal({{ $item->id }})" class="text-green-500 hover:text-green-700 inline-flex items-center px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        <span class="ml-1">Edit</span>
                                    </button>
                                @endif
                                @if(auth()->user()->canAny(['delete_books', 'delete_users', 'delete_authors']) && 
                                    !(isset($item->roles) && $item->roles->contains('name', 'member')))
                                    <button onclick="openActionModal({{ $item->id }}, 'delete')" class="text-red-500 hover:text-red-700 inline-flex items-center px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-1">Delete</span>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>



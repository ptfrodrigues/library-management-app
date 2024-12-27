@props(['item', 'fields', 'title', 'route'])

<div id="dynamic-modal-edit-{{ $item->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">Edit {{ $title }}</h2>
        
        <form action="{{ route($route, $item) }}" method="POST">
            @csrf
            @method('PUT')
            
            @foreach($fields as $field)
                @if(!in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at', 'password', 'remember_token', 'email_verified_at', 'permissions']))
                    @if($field === 'roles')
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                    <option value="{{ $role->name }}" {{ $item->hasRole($role) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($field === 'authors')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ ucfirst($field) }}</label>
                            <div id="authors-container-{{ $item->id }}">
                                @if($item->authors->isNotEmpty())
                                    @foreach($item->authors as $index => $bookAuthor)
                                        <div class="flex items-center mb-2 author-row">
                                            <select name="authors[]" class="mr-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                @foreach(\App\Models\Author::all() as $author)
                                                    <option value="{{ $author->id }}" {{ $bookAuthor->id == $author->id ? 'selected' : '' }}>
                                                        {{ $author->first_name }} {{ $author->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" onclick="removeAuthorDropdown(this)" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                Remove
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center mb-2 author-row">
                                        <select name="authors[]" class="mr-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Select an author</option>
                                            @foreach(\App\Models\Author::all() as $author)
                                                <option value="{{ $author->id }}">
                                                    {{ $author->first_name }} {{ $author->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="removeAuthorDropdown(this)" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                            Remove
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addAuthorDropdown('authors-container-{{ $item->id }}')" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                Add Author
                            </button>
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                            <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ $item->$field }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    @endif
                @endif
            @endforeach

            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal({{ $item->id }})" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function addAuthorDropdown(containerId) {
    const container = document.getElementById(containerId);
    const template = container.querySelector('.author-row');
    const newRow = template ? template.cloneNode(true) : createNewAuthorRow();
    const select = newRow.querySelector('select');
    select.selectedIndex = 0;
    container.appendChild(newRow);
}

function createNewAuthorRow() {
    const div = document.createElement('div');
    div.className = 'flex items-center mb-2 author-row';
    div.innerHTML = `
        <select name="authors[]" class="mr-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">Select an author</option>
            @foreach(\App\Models\Author::all() as $author)
                <option value="{{ $author->id }}">
                    {{ $author->first_name }} {{ $author->last_name }}
                </option>
            @endforeach
        </select>
        <button type="button" onclick="removeAuthorDropdown(this)" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
            Remove
        </button>
    `;
    return div;
}

function removeAuthorDropdown(button) {
    const row = button.closest('.author-row');
    if (row && row.parentElement.children.length > 1) {
        row.remove();
    }
}

function openEditModal(id) {
    document.getElementById(`dynamic-modal-edit-${id}`).classList.remove('hidden');
}

function closeEditModal(id) {
    document.getElementById(`dynamic-modal-edit-${id}`).classList.add('hidden');
}
</script>


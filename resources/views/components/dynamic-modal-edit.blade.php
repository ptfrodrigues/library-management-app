@props(['item', 'fields', 'title', 'route'])

<div id="dynamic-modal-edit-{{ $item->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-background rounded-lg shadow-xl p-8 max-w-3xl w-full max-h-[80vh] overflow-y-auto border border-secondary">
        <h2 class="text-3xl font-display font-bold mb-6 text-primary">Edit {{ $title }}</h2>
        
        <form action="{{ route($route, $item) }}" method="POST">
            @csrf
            @method('PUT')
            
            @foreach($fields as $field)
                @if(!in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at', 'password', 'remember_token', 'email_verified_at']))
                    @if($field === 'books')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-accent mb-2">{{ ucfirst($field) }}</label>
                            <div id="books-container-{{ $item->id }}">
                                @foreach($item->books as $book)
                                    <div class="flex items-center mb-2 book-row">
                                        <select name="books[]" class="mr-2 block w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            @foreach(\App\Models\Book::all() as $bookOption)
                                                <option value="{{ $bookOption->id }}" {{ $book->id == $bookOption->id ? 'selected' : '' }}>
                                                    {{ $bookOption->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="removeDropdown(this, 'book')" class="px-3 py-2 bg-tertiary text-white rounded-md hover:bg-tertiary-dark transition-colors duration-300">
                                            Remove
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addDropdown('books-container-{{ $item->id }}', 'book')" class="mt-2 px-4 py-2 bg-secondary text-white rounded-md hover:bg-secondary-dark transition-colors duration-300">
                                Add Book
                            </button>
                        </div>
                    @elseif($field === 'authors')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-accent mb-2">{{ ucfirst($field) }}</label>
                            <div id="authors-container-{{ $item->id }}">
                                @foreach($item->authors as $author)
                                    <div class="flex items-center mb-2 author-row">
                                        <select name="authors[]" class="mr-2 block w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            @foreach(\App\Models\Author::all() as $authorOption)
                                                <option value="{{ $authorOption->id }}" {{ $author->id == $authorOption->id ? 'selected' : '' }}>
                                                    {{ $authorOption->first_name }} {{ $authorOption->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="removeDropdown(this, 'author')" class="px-3 py-2 bg-tertiary text-white rounded-md hover:bg-tertiary-dark transition-colors duration-300">
                                            Remove
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addDropdown('authors-container-{{ $item->id }}', 'author')" class="mt-2 px-4 py-2 bg-secondary text-white rounded-md hover:bg-secondary-dark transition-colors duration-300">
                                Add Author
                            </button>
                        </div>
                    @elseif($field === 'roles')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-accent mb-2">{{ ucfirst($field) }}</label>
                            <select name="role" class="block w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                    <option value="{{ $role->name }}" {{ $item->hasRole($role) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($field === 'permissions')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-accent mb-2">{{ ucfirst($field) }}</label>
                            <p class="text-sm text-text-secondary mb-2">Permissions are managed through roles. Please select the appropriate role to grant or revoke permissions.</p>
                        </div>
                    @else
                        <div class="mb-6">
                            <label for="{{ $field }}" class="block text-sm font-medium text-accent mb-2">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                            <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ $item->$field }}" class="block w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                    @endif
                @endif
            @endforeach

            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" onclick="closeEditModal({{ $item->id }})" class="px-6 py-3 bg-accent text-white rounded-md hover:bg-accent-dark transition-colors duration-300">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function addDropdown(containerId, type) {
    const container = document.getElementById(containerId);
    const template = container.querySelector(`.${type}-row`);
    const newRow = template ? template.cloneNode(true) : createNewRow(type);
    const select = newRow.querySelector('select');
    select.selectedIndex = 0;
    container.appendChild(newRow);
}

function createNewRow(type) {
    const div = document.createElement('div');
    div.className = `flex items-center mb-2 ${type}-row`;
    const options = type === 'book' 
        ? `@foreach(\App\Models\Book::all() as $book)
            <option value="{{ $book->id }}">{{ $book->title }}</option>
           @endforeach`
        : `@foreach(\App\Models\Author::all() as $author)
            <option value="{{ $author->id }}">{{ $author->first_name }} {{ $author->last_name }}</option>
           @endforeach`;
    
    div.innerHTML = `
        <select name="${type}s[]" class="mr-2 block w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            <option value="">Select a ${type}</option>
            ${options}
        </select>
        <button type="button" onclick="removeDropdown(this, '${type}')" class="px-3 py-2 bg-tertiary text-white rounded-md hover:bg-tertiary-dark transition-colors duration-300">
            Remove
        </button>
    `;
    return div;
}

function removeDropdown(button, type) {
    const row = button.closest(`.${type}-row`);
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


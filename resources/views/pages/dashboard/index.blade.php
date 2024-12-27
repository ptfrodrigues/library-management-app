@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto py-8">

    <h1 class="text-3xl font-bold mb-6">Overview</h1>

    <!-- Statistics Area -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        @foreach($statistics as $key => $value)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-2">{{ ucfirst($key) }}</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <!-- User Roles Statistics -->
    @if(!empty($userRoles))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach($userRoles as $key => $value)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ ucfirst($key) }}</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        @can('create', App\Models\User::class)
            <button type="button" onclick="openModal('create-user-modal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create User
            </button>
        @endcan
        <button type="button" onclick="openModal('create-book-modal')" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Create Book
        </button>
    </div>

    <!-- User Creation Modal -->
    @can('create', App\Models\User::class)
        <x-dynamic-modal-create 
            id="create-user-modal"
            title="Create User"
            action="{{ route('dashboard.create.user') }}"
            :fields="[
                ['name' => 'name', 'label' => 'Name', 'type' => 'text'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
                ['name' => 'password', 'label' => 'Password', 'type' => 'password'],
                ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => [
                    'admin' => auth()->user()->hasRole('admin') ? 'Admin' : null,
                    'manager' => auth()->user()->hasRole('admin') ? 'Manager' : null,
                    'librarian' => auth()->user()->hasAnyRole(['admin', 'manager']) ? 'Librarian' : null,
                    'member' => 'Member'
                ]]
            ]"
        />
    @endcan

    <!-- Book Creation Modal -->
    <div id="create-book-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Create Book</h2>
            <form action="{{ route('dashboard.books.store') }}" method="POST" id="create-book-form">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                    <input type="text" id="genre" name="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                    <input type="text" id="language" name="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="year" class="block text-sm font-medium text-gray-700">Publication Year</label>
                    <input type="number" id="year" name="year" min="1900" max="{{ date('Y') + 1 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                    <textarea id="observations" name="observations" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                <div class="mb-4">
                    <label for="author_id" class="block text-sm font-medium text-gray-700">Author</label>
                    <div class="flex items-center">
                        <select id="author_id" name="author_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="">Select an author</option>
                            @foreach(App\Models\Author::all() as $author)
                                <option value="{{ $author->id }}">{{ $author->full_name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="create-author-btn" class="ml-2 px-4 py-2 bg-green-500 whitespace-nowrap text-white rounded hover:bg-green-600">
                            New Author
                        </button>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" onclick="closeModal('create-book-modal')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">
                        Close
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Author Creation Modal -->
    <div id="create-author-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Create Author</h2>
            <form id="create-author-form">
                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" id="country" name="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" onclick="closeModal('create-author-modal')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">
                        Close
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if (session('flash_message'))
        <div id="flash-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('flash_message') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="closeFlashMessage()">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif
</div>

<script>
// Check for flash message and show it
   const flashMessage = document.getElementById('flash-message');
   if (flashMessage) {
       flashMessage.style.display = 'block';
   }

document.addEventListener('DOMContentLoaded', function() {
    window.openModal = function(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    window.closeModal = function(id) {
        document.getElementById(id).classList.add('hidden');
    }

    const createAuthorBtn = document.getElementById('create-author-btn');
    const createAuthorModal = document.getElementById('create-author-modal');
    const createAuthorForm = document.getElementById('create-author-form');
    const authorSelect = document.getElementById('author_id');

    createAuthorBtn.addEventListener('click', function() {
        openModal('create-author-modal');
    });

    createAuthorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(createAuthorForm);
        fetch('{{ route('dashboard.authors.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newOption = new Option(data.author.full_name, data.author.id, true, true);
                authorSelect.appendChild(newOption);
                closeModal('create-author-modal');
                createAuthorForm.reset();
            } else {
                alert('Error creating author: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the author.');
        });
    });
});

function closeFlashMessage() {
    document.getElementById('flash-message').style.display = 'none';
}
</script>
@endsection


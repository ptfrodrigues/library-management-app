@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto py-12">
    <h1 class="text-4xl font-display font-bold mb-8 text-primary">Overview</h1>

    @if(session('success'))
        <div class="bg-secondary bg-opacity-10 border border-secondary text-secondary px-6 py-4 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-tertiary bg-opacity-10 border border-tertiary text-tertiary px-6 py-4 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
        @foreach($statistics as $key => $value)
            <div class="bg-background rounded-lg shadow-lg p-6 border border-secondary">
                <h2 class="text-xl font-display font-semibold mb-3 text-text">{{ ucfirst($key) }}</h2>
                <p class="text-3xl font-bold text-primary">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    @if(!empty($userRoles))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            @foreach($userRoles as $key => $value)
                <div class="bg-background rounded-lg shadow-lg p-6 border border-secondary">
                    <h3 class="text-lg font-display font-semibold mb-3 text-text">{{ ucfirst($key) }}</h3>
                    <p class="text-2xl font-bold text-secondary">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex flex-wrap gap-4 mb-12">
        @can('create', App\Models\User::class)
            <button type="button" onclick="openCreateModal('create-user-modal')" class="flex-grow md:flex-grow-1 bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300">
                Create User
            </button>
        @endcan
        @can('create', App\Models\Book::class)
            <button type="button" onclick="openCreateModal('create-book-modal')" class="flex-grow md:flex-grow-1 bg-secondary hover:bg-primary text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300">
                Create Book
            </button>
        @endcan
        @can('create', App\Models\Author::class)
            <button type="button" onclick="openCreateModal('create-author-modal')" class="flex-grow md:flex-grow-1 bg-accent hover:bg-accent-dark text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300">
                Create Author
            </button>
        @endcan
    </div>

    @can('create', App\Models\User::class)
        <x-dynamic-modal-create 
            id="create-user-modal"
            title="Create User"
            action="{{ route('dashboard.user.store') }}"
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
    @can('create', App\Models\Book::class)
        <x-dynamic-modal-create 
            id="create-book-modal"
            title="Create Book"
            action="{{ route('dashboard.books.store') }}"
            :fields="[
                ['name' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['name' => 'genre', 'label' => 'Genre', 'type' => 'text'],
                ['name' => 'language', 'label' => 'Language', 'type' => 'text'],
                ['name' => 'isbn', 'label' => 'ISBN (13 digits)', 'type' => 'text', 'pattern' => '[0-9]{13}'],
                ['name' => 'year', 'label' => 'Publication Year', 'type' => 'number', 'min' => 1900, 'max' => date('Y') + 1],
                ['name' => 'observations', 'label' => 'Observations', 'type' => 'textarea'],
                ['name' => 'authors[]', 'label' => 'Authors', 'type' => 'select', 'options' => App\Models\Author::all()->pluck('full_name', 'id')->toArray(), 'multiple' => true]
            ]"
        />
    @endcan
    @can('create', App\Models\Author::class)
        <x-dynamic-modal-create 
            id="create-author-modal"
            title="Create Author"
            action="{{ route('dashboard.authors.store') }}"
            :fields="[
                ['name' => 'first_name', 'label' => 'First Name', 'type' => 'text'],
                ['name' => 'last_name', 'label' => 'Last Name', 'type' => 'text'],
                ['name' => 'country', 'label' => 'Country', 'type' => 'text']
            ]"
        />
    @endcan
</div>
@endsection


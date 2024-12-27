@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container mx-auto py-6">
        <h1 class="text-3xl font-bold mb-6">Users</h1>

        <form action="{{ route('dashboard.users') }}" method="GET" class="mb-6">
            <div class="flex items-center shadow-sm">
                <input
                    type="text"
                    name="search"
                    placeholder="@can('edit_users') Search by name, email or role @else Search by name or email @endcan"
                    value="{{ request('search') }}"
                    class="flex-grow px-4 py-2 border-2 border-r-0 border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white border-2 border-blue-500 rounded-r-md hover:bg-blue-600 hover:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    Search
                </button>
            </div>
            @if(request('search'))
                <div class="mt-2">
                    <a href="{{ route('dashboard.users') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear search
                    </a>
                </div>
            @endif
        </form>

        <x-dynamic-table :items="$items" :tableFields="$tableFields" title="Users" />

        @foreach ($items as $user)
            <x-dynamic-modal :item="$user" :fields="$fields" title="User Details"/>
            <x-dynamic-modal-edit :item="$user" :fields="$fields" title="Users" route="dashboard.users.update"/>
            <x-dynamic-modal-action :item="$user" route="dashboard.users.destroy" acctioin='delete/>
        @endforeach
    </div>
@endsection



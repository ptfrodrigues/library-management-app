@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container mx-auto mt-20 py-12">
        <h1 class="text-4xl font-display font-bold mb-8 text-primary">Catalog</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @livewire('catalog-table', ['tableFields' => $tableFields])
    </div>
@endsection


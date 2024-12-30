@extends('layouts.app')

@section('header-content')
@include('partials.secondary-navbar')
@stop

@section('content')

    <div id="main" class=" mx-auto px-4 py-8 text-text">
        
        @yield('dashboard-content')
    </div>
@stop

<script>
    function openModal(id) {
        document.getElementById(`dynamic-modal-${id}`).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(`dynamic-modal-${id}`).classList.add('hidden');
    }

    function openEditModal(id) {
        document.getElementById(`dynamic-modal-edit-${id}`).classList.remove('hidden');
    }

    function closeEditModal(id) {
        document.getElementById(`dynamic-modal-edit-${id}`).classList.add('hidden');
    }

    function openActionModal(id, action) {
        document.getElementById(`dynamic-modal-${action}-${id}`).classList.remove('hidden');
    }

    function closeActionModal(id, action) {
        document.getElementById(`dynamic-modal-${action}-${id}`).classList.add('hidden');
    }

    function openCreateModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeCreateModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
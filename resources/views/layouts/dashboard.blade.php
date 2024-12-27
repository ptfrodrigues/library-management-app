@extends('layouts.default')
@section('header-content')
    @include('partials.secondary-navbar')
@stop
@section('content')
    <div id="main" class=" leading-5 text-gray-500 max-w-7xl mx-auto">
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
</script>

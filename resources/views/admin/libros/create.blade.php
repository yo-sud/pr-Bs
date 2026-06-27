@extends('layouts.admin')

@section('title', 'Nuevo libro - Administración')

@section('contenido')
<div class="max-w-4xl">
    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-amber-900">Registrar libro</h2>
        <p class="text-sm text-gray-500">Agrega sus datos y stock inicial.</p>
    </div>
    <form method="POST" action="{{ route('admin.libros.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border p-6 shadow-sm">
        @include('admin.libros._form')
    </form>
</div>
@endsection

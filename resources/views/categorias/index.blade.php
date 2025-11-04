@extends('layouts.app')

@section('content')
    <h1>Categorías</h1>

    <a href="{{ route('categorias.create') }}">Crear categoría</a>

    @if(session('success'))
        <div style="color:green">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color:red">{{ session('error') }}</div>
    @endif

    <ul>
        @foreach($categorias as $cat)
            <li>
                {{ $cat->nombre ?? $cat->name ?? 'Sin nombre' }}
                <a href="{{ route('categorias.edit', ['id' => $cat->id ?? $cat->ID_categoria ?? 0]) }}">Editar</a>
                <form action="{{ route('categorias.destroy', ['id' => $cat->id ?? $cat->ID_categoria ?? 0]) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection

@extends('layouts.app')

@section('content')
    <h1>Editar Categoría</h1>

    @if($errors->any())
        <div style="color:red">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.update', ['id' => $categoria->id ?? $categoria->ID_categoria ?? 0]) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}" />
        <button type="submit">Actualizar</button>
    </form>

    <a href="{{ route('categorias.index') }}">Volver</a>
@endsection

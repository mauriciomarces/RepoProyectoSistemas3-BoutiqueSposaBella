@extends('layouts.app')

@section('content')
    <h1>Crear Categoría</h1>

    @if($errors->any())
        <div style="color:red">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" />
        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('categorias.index') }}">Volver</a>
@endsection

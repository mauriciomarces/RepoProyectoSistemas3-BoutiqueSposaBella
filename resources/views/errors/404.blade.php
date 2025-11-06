@extends('layouts.app')

@section('title', 'Página No Encontrada')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <!-- Animated Icon -->
        <div class="mb-4">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#8E805E" stroke-width="2" fill="none"/>
                <path d="M15 9l-6 6m0-6l6 6" stroke="#8E805E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="9.5" cy="9.5" r="0.5" fill="#8E805E"/>
                <circle cx="14.5" cy="14.5" r="0.5" fill="#8E805E"/>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 class="display-1 fw-bold text-primary mb-3" style="color: #8E805E !important;">404</h1>

        <!-- Error Title -->
        <h2 class="h3 mb-4" style="color: #C1BAA2;">Página No Encontrada</h2>

        <!-- Error Message -->
        <p class="lead mb-4" style="color: #A19E94;">
            Lo sentimos, la página que buscas no existe o ha sido movida.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="{{ url()->previous() ?: route('welcome') }}" class="btn btn-outline-primary px-4 py-2" style="border-color: #8E805E; color: #8E805E;">
                <i class="fas fa-arrow-left me-2"></i>Volver Atrás
            </a>
            <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2" style="background-color: #8E805E; border-color: #8E805E;">
                <i class="fas fa-home me-2"></i>Ir al Inicio
            </a>
        </div>

        <!-- Search Suggestion -->
        <div class="mt-4">
            <p class="mb-3" style="color: #A19E94;">¿Buscas algo específico?</p>
            <form action="{{ route('catalogo.index') }}" method="GET" class="d-inline">
                <div class="input-group" style="max-width: 400px; margin: 0 auto;">
                    <input type="text" name="search" class="form-control" placeholder="Buscar productos..." style="border-color: #8E805E;">
                    <button class="btn btn-primary" type="submit" style="background-color: #8E805E; border-color: #8E805E;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-4">
            <small class="text-muted">
                Si crees que esto es un error, contacta al administrador del sistema.
            </small>
        </div>
    </div>
</div>

<style>
.btn-outline-primary:hover {
    background-color: #8E805E !important;
    border-color: #8E805E !important;
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Token Expirado')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <!-- Animated Icon -->
        <div class="mb-4">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#8E805E" stroke-width="2" fill="none"/>
                <path d="M12 6v6l4 2" stroke="#8E805E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-6.364l-1.414 1.414M6.05 17.95l-1.414 1.414M17.95 17.95l-1.414-1.414M6.05 6.05l-1.414-1.414" stroke="#8E805E" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 class="display-1 fw-bold text-primary mb-3" style="color: #8E805E !important;">419</h1>

        <!-- Error Title -->
        <h2 class="h3 mb-4" style="color: #C1BAA2;">Token Expirado</h2>

        <!-- Error Message -->
        <p class="lead mb-4" style="color: #A19E94;">
            Tu sesión ha expirado por seguridad. Por favor, actualiza la página o inicia sesión nuevamente.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mb-4">
            <button onclick="window.location.reload()" class="btn btn-outline-primary px-4 py-2" style="border-color: #8E805E; color: #8E805E;">
                <i class="fas fa-refresh me-2"></i>Actualizar Página
            </button>
            <a href="{{ url()->previous() ?: route('welcome') }}" class="btn btn-outline-primary px-4 py-2" style="border-color: #8E805E; color: #8E805E;">
                <i class="fas fa-arrow-left me-2"></i>Volver Atrás
            </a>
            <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2" style="background-color: #8E805E; border-color: #8E805E;">
                <i class="fas fa-home me-2"></i>Ir al Inicio
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-4">
            <small class="text-muted">
                Si el problema persiste, contacta al administrador del sistema.
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

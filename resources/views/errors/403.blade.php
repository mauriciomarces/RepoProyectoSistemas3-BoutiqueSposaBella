@extends('layouts.app')

@section('title', 'Error 403 - Prohibido')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <!-- Animated Icon -->
        <div class="mb-4">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#8E805E" stroke-width="2" fill="none"/>
                <rect x="7" y="9" width="10" height="6" rx="1" stroke="#8E805E" stroke-width="2" fill="none"/>
                <circle cx="12" cy="6" r="1" fill="#8E805E"/>
                <path d="M9 15l6-6" stroke="#8E805E" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 class="display-1 fw-bold text-primary mb-3" style="color: #8E805E !important;">403</h1>

        <!-- Error Title -->
        <h2 class="h3 mb-4" style="color: #C1BAA2;">Acceso Prohibido</h2>

        <!-- Error Message -->
        <p class="lead mb-4" style="color: #A19E94;">
            No tienes permisos para acceder a este recurso.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2" style="background-color: #8E805E; border-color: #8E805E;">
                <i class="fas fa-home me-2"></i>Ir al Inicio
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-primary px-4 py-2" style="border-color: #8E805E; color: #8E805E;">
                <i class="fas fa-arrow-left me-2"></i>Volver Atrás
            </a>
        </div>

        <!-- How to trigger -->
        <div class="mt-4 p-3 bg-light rounded">
            <small class="text-muted">
                <strong>Para probar este error:</strong> Intenta acceder a rutas protegidas con permisos insuficientes o recursos restringidos.
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

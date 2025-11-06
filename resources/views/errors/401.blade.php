@extends('layouts.app')

@section('title', 'Error 401 - No Autorizado')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <!-- Animated Icon -->
        <div class="mb-4">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#8E805E" stroke-width="2" fill="none"/>
                <rect x="7" y="10" width="10" height="4" rx="1" stroke="#8E805E" stroke-width="2" fill="none"/>
                <circle cx="12" cy="7" r="1" fill="#8E805E"/>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 class="display-1 fw-bold text-primary mb-3" style="color: #8E805E !important;">401</h1>

        <!-- Error Title -->
        <h2 class="h3 mb-4" style="color: #C1BAA2;">No Autorizado</h2>

        <!-- Error Message -->
        <p class="lead mb-4" style="color: #A19E94;">
            Necesitas autenticarte para acceder a este recurso.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2" style="background-color: #8E805E; border-color: #8E805E;">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </a>
            <a href="{{ route('welcome') }}" class="btn btn-outline-primary px-4 py-2" style="border-color: #8E805E; color: #8E805E;">
                <i class="fas fa-home me-2"></i>Ir al Inicio
            </a>
        </div>

        <!-- How to trigger -->
        <div class="mt-4 p-3 bg-light rounded">
            <small class="text-muted">
                <strong>Para probar este error:</strong> Intenta acceder a rutas protegidas sin estar autenticado, como paneles de administración o áreas privadas.
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

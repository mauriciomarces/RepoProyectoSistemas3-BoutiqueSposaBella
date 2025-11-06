@extends('layouts.app')

@section('title', 'Error 100 - Continue')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <!-- Animated Icon -->
        <div class="mb-4">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="animate-pulse">
                <circle cx="12" cy="12" r="10" stroke="#8E805E" stroke-width="2" fill="none" stroke-dasharray="31.4" stroke-dashoffset="31.4" class="animate-spin">
                    <animate attributeName="stroke-dashoffset" values="31.4;0" dur="2s" repeatCount="indefinite"/>
                </circle>
                <path d="M9 12l2 2 4-4" stroke="#8E805E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0">
                    <animate attributeName="opacity" values="0;1" dur="1s" begin="0.5s" fill="freeze"/>
                </path>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 class="display-1 fw-bold text-primary mb-3" style="color: #8E805E !important;">100</h1>

        <!-- Error Title -->
        <h2 class="h3 mb-4" style="color: #C1BAA2;">Continue</h2>

        <!-- Error Message -->
        <p class="lead mb-4" style="color: #A19E94;">
            La solicitud puede continuar. El servidor ha recibido los encabezados de la solicitud y el cliente debe proceder a enviar el cuerpo de la solicitud.
        </p>

        <!-- Action Button -->
        <a href="{{ url()->previous() ?: route('welcome') }}" class="btn btn-primary btn-lg px-4 py-2" style="background-color: #8E805E; border-color: #8E805E;">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>

        <!-- Additional Info -->
        <div class="mt-4">
            <small class="text-muted">
                Si el problema persiste, contacta al administrador del sistema.
            </small>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 2s linear infinite;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>
@endsection

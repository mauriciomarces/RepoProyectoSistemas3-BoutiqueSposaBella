@extends('layouts.app')

@section('title', 'Error 400 - Solicitud Incorrecta')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <!-- Animated Icon -->
        <div class="mb-4">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#8E805E" stroke-width="2" fill="none"/>
                <path d="M8 9l8 6M16 9l-8 6" stroke="#8E805E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="9" cy="9" r="1" fill="#8E805E"/>
                <circle cx="15" cy="15" r="1" fill="#8E805E"/>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 class="display-1 fw-bold text-primary mb-3" style="color: #8E805E !important;">400</h1>

        <!-- Error Title -->
        <h2 class="h3 mb-4" style="color: #C1BAA2;">Solicitud Incorrecta</h2>

        <!-- Error Message -->
        <p class="lead mb-4" style="color: #A19E94;">
            La solicitud no pudo ser entendida por el servidor debido a una sintaxis incorrecta.
        </p>

        <!-- Action Button -->
        <a href="{{ url()->previous() ?: route('welcome') }}" class="btn btn-primary btn-lg px-4 py-2" style="background-color: #8E805E; border-color: #8E805E;">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>

        <!-- How to trigger -->
        <div class="mt-4 p-3 bg-light rounded">
            <small class="text-muted">
                <strong>Para probar este error:</strong> Envía datos inválidos en formularios o realiza solicitudes con parámetros incorrectos.
            </small>
        </div>
    </div>
</div>
@endsection

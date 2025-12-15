@extends('layouts.admin')

@section('title', 'Página no encontrada')

@push('styles')
<style>
    .error-404-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
        padding: 40px;
    }

    .error-404-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
</style>
@endpush

@section('content')
<div class="error-404-wrapper">
    <div class="error-404-card">
        {{-- Solo el mensaje de advertencia --}}
        <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong>Advertencia:</strong> No existe esa página.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

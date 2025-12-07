@extends('layouts.judge-panel')

@section('title', 'Mis evaluaciones')

@push('styles')
<style>
    .eval-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .eval-status.completada {
        background: #dcfce7;
        color: #166534;
    }
    .eval-status.pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    .eval-actions {
        display: flex;
        gap: 6px;
    }
    .eval-actions .btn {
        flex: 1;
        font-size: 0.8rem;
        padding: 6px 10px;
    }
</style>
@endpush

@section('content')
    <h1 class="h3 mb-4">📋 Mis Evaluaciones Completadas</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✅ Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-card">
        @forelse($evaluations as $evaluation)
            <div class="mb-3 p-3" style="border: 1px solid #ddd; border-radius: 8px; background: #fafafa;">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <h6 class="mb-1">{{ $evaluation->project->name }}</h6>
                        <small class="text-muted">Equipo: <strong>{{ $evaluation->project->team?->name ?? '—' }}</strong></small>
                    </div>
                    <div class="col-md-2">
                        <span class="eval-status {{ $evaluation->status }}">
                            {{ ucfirst($evaluation->status) }}
                        </span>
                    </div>
                    <div class="col-md-2" style="text-align: center;">
                        <div style="font-weight: bold; color: #2563eb; font-size: 18px;">
                            ⭐ {{ number_format($evaluation->final_score ?? 0, 2) }}/10
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="eval-actions">
                            <a href="{{ route('judge.evaluations.show', $evaluation->project_id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="{{ route('judge.evaluations.export-pdf', $evaluation) }}"
                               class="btn btn-sm btn-outline-danger"
                               target="_blank"
                               title="Descargar PDF">
                                <i class="bi bi-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
                @if($evaluation->general_comments)
                    <small class="text-muted d-block mt-2">
                        <strong>Comentarios:</strong> {{ substr($evaluation->general_comments, 0, 80) }}{{ strlen($evaluation->general_comments) > 80 ? '...' : '' }}
                    </small>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #999;">
                <p style="font-size: 18px; margin-bottom: 10px;">📭 Aún no tienes evaluaciones completadas</p>
                <small>Una vez completes la evaluación de un proyecto, aparecerá aquí.</small>
            </div>
        @endforelse
    </div>
@endsection

@extends('layouts.admin-panel')

@section('title', 'Crear rúbrica')

@push('styles')
<style>
    /* ===== COLORES BLANCOS PARA TEXTO ===== */
    .h4, .mb-3, .form-label, .admin-card-title {
        color: #fff !important;
    }
</style>
@endpush

@section('content')
    <h1 class="h4 mb-3">Crear nueva rúbrica</h1>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2 mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card">
        <form action="{{ route('admin.rubrics.store') }}" method="POST" id="create-rubric-form">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre de la rúbrica</label>
                    <input type="text" name="name" class="form-control rounded-pill" value="{{ old('name') }}" placeholder="Ej: Rúbrica Innovatec 2025" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Evento (opcional)</label>
                    <select name="event_id" class="form-select rounded-pill">
                        <option value="">-- Sin evento específico --</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>{{ $ev->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select rounded-pill" required>
                        <option value="activa" {{ old('status', 'activa') == 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="inactiva" {{ old('status') == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.rubrics.index') }}" class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>
                <button class="admin-btn-primary" type="submit">
                    <i class="bi bi-save"></i> Crear rúbrica
                </button>
            </div>
        </form>
    </div>

    {{-- Sección para agregar criterios (se muestra después de crear la rúbrica) --}}
    <div id="criteria-section" style="display: none;">
        <hr style="border-color: rgba(148, 163, 184, 0.2); margin: 30px 0;">
        
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="admin-card-title">Agregar criterios a la rúbrica</div>
                <div id="weight-indicator" class="badge" style="font-size: 1rem; padding: 8px 16px;">
                    <span id="current-weight">0</span> / 100
                </div>
            </div>
            
            <div id="criteria-list" class="mb-3">
                {{-- Aquí se mostrarán los criterios agregados --}}
                <p class="text-muted" id="no-criteria-msg">No hay criterios agregados aún.</p>
            </div>
            
            <div id="error-messages" class="alert alert-danger d-none mb-3"></div>
            
            <form id="add-criterion-form" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Nombre del criterio</label>
                    <input type="text" name="criterion_name" class="form-control rounded-pill" placeholder="Ej: Creatividad" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="criterion_description" class="form-control rounded-pill" placeholder="Descripción (opcional)">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Peso</label>
                    <input type="number" name="criterion_weight" class="form-control rounded-pill" placeholder="1.0" min="0" max="100" step="0.01" value="1">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Min</label>
                    <input type="number" name="criterion_min" class="form-control rounded-pill" placeholder="0" min="0" value="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Max</label>
                    <input type="number" name="criterion_max" class="form-control rounded-pill" placeholder="10" min="1" value="10">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="admin-btn-primary w-100" type="submit">
                        <i class="bi bi-plus-circle"></i> Agregar
                    </button>
                </div>
            </form>
            
            <div class="mt-4 text-center">
                <a href="{{ route('admin.rubrics.index') }}" class="admin-btn-primary" style="background: #16a34a;">
                    <i class="bi bi-check-circle"></i> Finalizar
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let rubricId = null;
    let currentWeightSum = 0;
    
    // Actualizar indicador de peso
    function updateWeightIndicator() {
        const indicator = document.getElementById('weight-indicator');
        const currentWeightSpan = document.getElementById('current-weight');
        currentWeightSpan.textContent = currentWeightSum.toFixed(2);
        
        if (currentWeightSum === 100) {
            indicator.className = 'badge bg-success';
        } else if (currentWeightSum > 100) {
            indicator.className = 'badge bg-danger';
        } else {
            indicator.className = 'badge bg-warning';
        }
    }
    
    // Manejar creación de rúbrica
    document.getElementById('create-rubric-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.rubrics.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                rubricId = data.rubric_id;
                
                // Deshabilitar formulario de rúbrica
                document.getElementById('create-rubric-form').querySelectorAll('input, select, button').forEach(el => {
                    el.disabled = true;
                });
                
                // Mostrar sección de criterios
                document.getElementById('criteria-section').style.display = 'block';
                
                // Scroll hacia la sección de criterios
                document.getElementById('criteria-section').scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => console.error('Error:', error));
    });
    
    // Manejar agregar criterio
    document.getElementById('add-criterion-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('name', this.criterion_name.value);
        formData.append('description', this.criterion_description.value);
        formData.append('weight', this.criterion_weight.value);
        formData.append('min_score', this.criterion_min.value);
        formData.append('max_score', this.criterion_max.value);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch(`/admin/rubricas/${rubricId}/criterios`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ocultar mensaje de error si existe
                document.getElementById('error-messages').classList.add('d-none');
                
                // Actualizar suma de pesos
                currentWeightSum = data.current_weight_sum;
                updateWeightIndicator();
                
                // Ocultar mensaje de "no hay criterios"
                const noMsg = document.getElementById('no-criteria-msg');
                if (noMsg) noMsg.style.display = 'none';
                
                // Agregar criterio a la lista
                const criteriaList = document.getElementById('criteria-list');
                const criterionDiv = document.createElement('div');
                criterionDiv.className = 'alert alert-success py-2 mb-2';
                criterionDiv.innerHTML = `<i class="bi bi-check-circle me-2"></i>${data.criterion.name} - Peso: ${data.criterion.weight}, Rango: ${data.criterion.min_score}-${data.criterion.max_score}`;
                criteriaList.appendChild(criterionDiv);
                
                // Limpiar formulario
                this.reset();
                this.criterion_weight.value = '1';
                this.criterion_min.value = '0';
                this.criterion_max.value = '10';
            } else {
                // Mostrar mensaje de error
                const errorDiv = document.getElementById('error-messages');
                errorDiv.textContent = data.message;
                errorDiv.classList.remove('d-none');
                errorDiv.scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorDiv = document.getElementById('error-messages');
            errorDiv.textContent = 'Error al agregar el criterio. Por favor intenta de nuevo.';
            errorDiv.classList.remove('d-none');
        });
    });
</script>
@endpush

{{-- Sistema de notificaciones Toast --}}
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>

<style>
.toast-notification {
    background: white;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 320px;
    animation: slideInRight 0.3s ease-out;
    border-left: 4px solid;
    position: relative;
    overflow: hidden;
}

.toast-notification.toast-success {
    border-left-color: #22c55e;
}

.toast-notification.toast-error {
    border-left-color: #ef4444;
}

.toast-notification.toast-warning {
    border-left-color: #f59e0b;
}

.toast-notification.toast-info {
    border-left-color: #3b82f6;
}

.toast-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 20px;
}

.toast-success .toast-icon {
    color: #22c55e;
}

.toast-error .toast-icon {
    color: #ef4444;
}

.toast-warning .toast-icon {
    color: #f59e0b;
}

.toast-info .toast-icon {
    color: #3b82f6;
}

.toast-content {
    flex: 1;
    color: #1e293b;
}

.toast-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 2px;
}

.toast-message {
    font-size: 13px;
    color: #64748b;
    line-height: 1.4;
}

.toast-close {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
    font-size: 18px;
}

.toast-close:hover {
    color: #1e293b;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: currentColor;
    opacity: 0.3;
    animation: progressBar 3s linear forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.toast-notification.hiding {
    animation: slideOutRight 0.3s ease-in forwards;
}
</style>

<script>
// Sistema de Toast global
window.Toast = {
    show: function(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const icons = {
            success: '<i class="bi bi-check-circle-fill"></i>',
            error: '<i class="bi bi-x-circle-fill"></i>',
            warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
            info: '<i class="bi bi-info-circle-fill"></i>'
        };

        const titles = {
            success: 'Éxito',
            error: 'Error',
            warning: 'Advertencia',
            info: 'Información'
        };

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
            <div class="toast-progress"></div>
        `;

        container.appendChild(toast);

        // Auto-remove después de la duración especificada
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success: function(message, duration = 3000) {
        this.show(message, 'success', duration);
    },

    error: function(message, duration = 4000) {
        this.show(message, 'error', duration);
    },

    warning: function(message, duration = 3500) {
        this.show(message, 'warning', duration);
    },

    info: function(message, duration = 3000) {
        this.show(message, 'info', duration);
    }
};

// Mostrar mensajes flash de Laravel al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Toast.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        Toast.error("{{ session('error') }}");
    @endif

    @if(session('warning'))
        Toast.warning("{{ session('warning') }}");
    @endif

    @if(session('info'))
        Toast.info("{{ session('info') }}");
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            Toast.error("{{ $error }}", 5000);
        @endforeach
    @endif
});
</script>

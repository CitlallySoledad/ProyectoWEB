/**
 * Sistema Global de Notificaciones Toast
 * Úsalo en cualquier parte con: Toast.success('Mensaje')
 */

// Ejemplos de uso en JavaScript:
// Toast.success('Guardado correctamente');
// Toast.error('Error al guardar');
// Toast.warning('Advertencia: verifica los datos');
// Toast.info('Información importante');

// Para confirmar acciones peligrosas:
function confirmDelete(message = '¿Estás seguro de eliminar esto?') {
    return new Promise((resolve) => {
        if (confirm(message)) {
            resolve(true);
        } else {
            Toast.info('Operación cancelada');
            resolve(false);
        }
    });
}

// Usar en formularios:
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación antes de enviar formularios con class="confirm-submit"
    document.querySelectorAll('form.confirm-submit').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const confirmed = await confirmDelete(
                this.getAttribute('data-confirm-message') || '¿Confirmar esta acción?'
            );
            if (confirmed) {
                this.submit();
            }
        });
    });

    // Botones de eliminación con confirmación
    document.querySelectorAll('[data-confirm-delete]').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-confirm-message') || '¿Estás seguro de eliminar?';
            const confirmed = await confirmDelete(message);
            if (confirmed) {
                // Si es un formulario, envíalo
                const form = this.closest('form');
                if (form) {
                    form.submit();
                } else if (this.href) {
                    window.location.href = this.href;
                }
            }
        });
    });
});

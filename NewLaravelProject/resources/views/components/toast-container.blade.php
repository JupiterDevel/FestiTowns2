<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <!-- Toasts will be dynamically inserted here -->
</div>

@push('scripts')
<script>
    // Toast Service
    class ToastService {
        constructor() {
            this.container = document.getElementById('toast-container');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'toast-container';
                this.container.className = 'toast-container position-fixed top-0 end-0 p-3';
                this.container.style.zIndex = '9999';
                document.body.appendChild(this.container);
            }
            this.initSessionToasts();
        }

        /**
         * Show a toast notification
         * @param {string} message - The message to display
         * @param {string} type - The type: 'success', 'error', 'warning', 'info'
         * @param {number|null} duration - Duration in milliseconds (null = no auto-dismiss)
         * @param {boolean} allowDismiss - Whether the toast can be manually dismissed
         */
        show(message, type = 'info', duration = 5000, allowDismiss = true) {
            const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            const toast = this.createToast(toastId, message, type, allowDismiss);
            
            this.container.appendChild(toast);
            
            // Initialize Bootstrap toast
            const Bootstrap = window.bootstrap || bootstrap;
            const bsToast = new Bootstrap.Toast(toast, {
                autohide: duration !== null,
                delay: duration || 0
            });
            
            bsToast.show();
            
            // Remove element after hiding
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
            
            return bsToast;
        }

        /**
         * Create toast HTML element
         */
        createToast(id, message, type, allowDismiss) {
            const toast = document.createElement('div');
            toast.id = id;
            toast.className = 'toast';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            const bgClass = this.getBackgroundClass(type);
            const icon = this.getIcon(type);
            
            toast.innerHTML = `
                <div class="toast-header ${bgClass} text-white">
                    <i class="${icon} me-2"></i>
                    <strong class="me-auto">${this.getTypeTitle(type)}</strong>
                    ${allowDismiss ? '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>' : ''}
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            
            return toast;
        }

        /**
         * Get background class for toast type using brand colors
         */
        getBackgroundClass(type) {
            const classes = {
                'success': 'bg-success-green',
                'error': 'bg-brand-red',
                'warning': 'bg-brand-rating',
                'info': 'bg-brand-teal'
            };
            return classes[type] || classes['info'];
        }

        /**
         * Get icon for toast type
         */
        getIcon(type) {
            const icons = {
                'success': 'bi bi-check-circle-fill',
                'error': 'bi bi-exclamation-triangle-fill',
                'warning': 'bi bi-exclamation-circle-fill',
                'info': 'bi bi-info-circle-fill'
            };
            return icons[type] || icons['info'];
        }

        /**
         * Get title for toast type (en español)
         */
        getTypeTitle(type) {
            const titles = {
                'success': 'Éxito',
                'error': 'Error',
                'warning': 'Advertencia',
                'info': 'Información'
            };
            return titles[type] || titles['info'];
        }

        /**
         * Initialize toasts from session flash messages
         */
        initSessionToasts() {
            // Check for session flash messages
            @php
                $sessionData = [
                    'success' => session('success'),
                    'error' => session('error'),
                    'warning' => session('warning'),
                    'info' => session('info')
                ];
                $statusMessage = session('status');
            @endphp
            
            const sessionData = @json($sessionData);

            Object.keys(sessionData).forEach(type => {
                const message = sessionData[type];
                if (message) {
                    // Use setTimeout to ensure DOM is ready
                    setTimeout(() => {
                        this.show(message, type, 5000, true);
                    }, 100);
                }
            });

            // Also check for status messages and translate to Spanish
            const statusMessage = @json($statusMessage);
            if (statusMessage) {
                setTimeout(() => {
                    // Translate status keys to Spanish messages
                    const statusTranslations = {
                        'password-updated': 'Contraseña actualizada correctamente.',
                        'profile-updated': 'Perfil actualizado correctamente.',
                        'verification-link-sent': 'Se ha enviado un enlace de verificación a tu correo electrónico.'
                    };
                    
                    let message = statusTranslations[statusMessage] || statusMessage;
                    let type = 'info';
                    
                    // Determine type from status message context
                    if (statusMessage === 'password-updated' || statusMessage === 'profile-updated' || 
                        statusMessage === 'verification-link-sent' ||
                        statusMessage.includes('actualizado') || statusMessage.includes('creado') || 
                        statusMessage.includes('enviado') || statusMessage.includes('guardado')) {
                        type = 'success';
                    }
                    
                    this.show(message, type, 5000, true);
                }, 100);
            }
        }

        // Convenience methods
        success(message, duration = 5000) {
            return this.show(message, 'success', duration);
        }

        error(message, duration = 7000) {
            return this.show(message, 'error', duration);
        }

        warning(message, duration = 6000) {
            return this.show(message, 'warning', duration);
        }

        info(message, duration = 5000) {
            return this.show(message, 'info', duration);
        }
    }

    // Initialize Toast Service
    document.addEventListener('DOMContentLoaded', function() {
        window.toast = new ToastService();
    });
</script>
@endpush


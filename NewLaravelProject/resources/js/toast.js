/**
 * Toast Service - Standalone JavaScript module for toast notifications
 * Can be imported and used independently or alongside the Blade component
 */

export class ToastService {
    constructor() {
        this.container = this.getOrCreateContainer();
        this.initSessionToasts();
    }

    /**
     * Get or create the toast container
     */
    getOrCreateContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        return container;
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
     * This will be called from the Blade component which has access to session data
     */
    initSessionToasts() {
        // Session toasts are handled by the Blade component
        // This method is here for potential future use
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

// Create global instance when DOM is ready
if (typeof window !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.toast) {
            window.toast = new ToastService();
        }
    });
}


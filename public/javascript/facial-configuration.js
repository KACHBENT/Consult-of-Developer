// javascript/facial-configuration.js

class FacialConfiguration {
    constructor() {
        // Estado interno
        this.descriptors = [];
        this.maxDescriptors = 5;

        // Elementos del DOM
        this.video = null;
        this.canvas = null;
        this.formFacial = null;
        this.hiddenInput = null;

        this.btnIniciarCamara = null;
        this.btnCapturar = null;
        this.btnReiniciar = null;
        this.btnGuardar = null;       // Botón dentro del form que abre el modal
        this.btnConfirmReiniciar = null;
        this.btnReintentarCamara = null;

        this.estadoSistema = null;
        this.estadoTexto = null;
        this.estadoDeteccion = null;
        this.contadorNumero = null;
        this.barraProgreso = null;
        this.mensajeDiv = null;
        this.modalCountSpan = null;
    }

    init() {
        try {
            // Referencias a elementos del DOM
            this.video = document.getElementById('video');
            this.canvas = document.getElementById('canvas');
            this.formFacial = document.getElementById('formFacial');
            this.hiddenInput = document.getElementById('descriptores_faciales');

            this.btnIniciarCamara = document.getElementById('btnIniciarCamara');
            this.btnCapturar = document.getElementById('btnCapturar');
            this.btnReiniciar = document.getElementById('btnReiniciar');
            this.btnGuardar = document.getElementById('btnGuardar');
            this.btnConfirmReiniciar = document.getElementById('confirmReiniciar');
            this.btnReintentarCamara = document.getElementById('reintentarCamara');

            this.estadoSistema = document.getElementById('estadoSistema');
            this.estadoTexto = document.getElementById('estadoTexto');
            this.estadoDeteccion = document.getElementById('estadoDeteccion');
            this.contadorNumero = document.getElementById('contadorNumero');
            this.barraProgreso = document.getElementById('barraProgreso');
            this.mensajeDiv = document.getElementById('mensaje');
            this.modalCountSpan = document.getElementById('modalCount');

            if (!this.video || !this.canvas) {
                console.error('No se encontró video o canvas en la página.');
                return false;
            }

            // Event listeners
            this.btnIniciarCamara?.addEventListener('click', () => this.startCamera());
            this.btnCapturar?.addEventListener('click', () => this.captureDescriptor());
            this.btnReiniciar?.addEventListener('click', () => {
                // Sólo abre el modal, el confirmReiniciar hace el reset
                // El modal se gestiona con Bootstrap (data-bs-target ya en el HTML)
            });

            // Confirmar reinicio desde el modal
            this.btnConfirmReiniciar?.addEventListener('click', () => {
                this.resetCapture();
                // Cerrar modal manualmente (si Bootstrap está cargado)
                const modalEl = document.getElementById('modalReiniciar');
                if (modalEl && window.bootstrap) {
                    const modal = window.bootstrap.Modal.getInstance(modalEl);
                    modal?.hide();
                }
            });

            // Botón de ayuda de cámara → reintentar
            this.btnReintentarCamara?.addEventListener('click', () => this.startCamera());

            // Botón de guardar: solo actualiza el contador del modal
            this.btnGuardar?.addEventListener('click', () => {
                if (this.modalCountSpan) {
                    this.modalCountSpan.textContent = String(this.descriptors.length);
                }
            });

            // Estado inicial
            this.updateSystemStatus('info', 'Inicializando…');
            this.updateDetectionStatus('secondary', 'Cámara no iniciada');
            this.updateCounter();
            this.updateProgress();

            console.log('FacialConfiguration inicializado');
            return true;
        } catch (error) {
            console.error('Error inicializando FacialConfiguration:', error);
            return false;
        }
    }

    async startCamera() {
        try {
            this.updateSystemStatus('info', 'Iniciando cámara…');
            this.showMessage('Iniciando cámara...', 'info');

            if (!window.cameraManager) {
                console.error('cameraManager no está disponible');
                this.showMessage('❌ No se pudo acceder al administrador de cámara.', 'danger');
                return;
            }

            const ok = await window.cameraManager.startCamera(this.video);

            if (ok) {
                this.updateSystemStatus('success', 'Cámara iniciada correctamente.');
                this.updateDetectionStatus('success', 'Cámara activa');
                this.showMessage('Cámara iniciada. Posiciónate frente a la cámara y captura varios ángulos.', 'success');

                if (this.btnCapturar) this.btnCapturar.disabled = false;
                if (this.btnReiniciar) this.btnReiniciar.disabled = false;
            } else {
                this.updateSystemStatus('danger', 'No se pudo iniciar la cámara.');
                this.updateDetectionStatus('secondary', 'Cámara no iniciada');
                this.showMessage('No se pudo acceder a la cámara. Verifica permisos y dispositivos.', 'danger');
            }
        } catch (error) {
            console.error('Error al iniciar la cámara:', error);
            this.updateSystemStatus('danger', 'Error al iniciar la cámara.');
            this.showMessage('Error al iniciar la cámara: ' + error.message, 'danger');
        }
    }

    captureDescriptor() {
        // Verificar cámara lista
        if (!this.video || this.video.readyState !== 4) {
            this.showMessage('La cámara no está lista. Espera un momento e inténtalo de nuevo.', 'warning');
            return;
        }

        if (!window.cameraManager) {
            this.showMessage('No se puede capturar: cameraManager no disponible.', 'danger');
            return;
        }

        // Límite de descriptores
        if (this.descriptors.length >= this.maxDescriptors) {
            this.showMessage('Máximo de descriptores alcanzado. Guarda tu configuración.', 'info');
            if (this.btnCapturar) {
                this.btnCapturar.disabled = true;
                this.btnCapturar.innerHTML = 'Límite alcanzado';
            }
            return;
        }

        try {
            // 1) Capturar foto (por ahora sólo para posible uso futuro)
            window.cameraManager.takePhoto(this.video, this.canvas);

            // 2) Generar descriptor simulado (128 dimensiones)
            const descriptor = this.generateDescriptor();

            this.descriptors.push(descriptor);
            this.updateCounter();
            this.updateProgress();

            // Actualizar campo hidden con el JSON completo
            if (this.hiddenInput) {
                this.hiddenInput.value = JSON.stringify(this.descriptors);
            }

            // Mostrar formulario cuando al menos hay 3
            if (this.descriptors.length >= 3 && this.formFacial) {
                this.formFacial.classList.remove('d-none');
            }

            // Mensajes
            let msg = `Descriptor ${this.descriptors.length} capturado correctamente.`;
            if (this.descriptors.length < 5) {
                msg += '<br><small>Cambia ligeramente tu posición (giro, inclinación) y captura otro ángulo.</small>';
            }
            this.showMessage(msg, 'success');

            // Actualizar contador del modal
            if (this.modalCountSpan) {
                this.modalCountSpan.textContent = String(this.descriptors.length);
            }

            // Si se llega al máximo
            if (this.descriptors.length >= this.maxDescriptors && this.btnCapturar) {
                this.btnCapturar.disabled = true;
                this.btnCapturar.innerHTML = 'Límite alcanzado';
                this.showMessage('🎊 ¡Máximo de descriptores alcanzado! Ahora puedes guardar tu configuración facial.', 'info');
            }

        } catch (error) {
            console.error('Error capturando descriptor:', error);
            this.showMessage('Error al capturar descriptor: ' + error.message, 'danger');
        }
    }

    generateDescriptor() {
        // Genera un descriptor pseudoaleatorio de 128 floats entre 0 y 1
        const descriptor = [];
        const seed = Date.now() % 1000;

        for (let i = 0; i < 128; i++) {
            const value = Math.sin(seed + i * 0.13) * 0.5 + 0.5; // rango aproximado 0..1
            descriptor.push(parseFloat(value.toFixed(6)));
        }

        return descriptor;
    }

    resetCapture() {
        this.descriptors = [];

        this.updateCounter();
        this.updateProgress();
        this.showMessage('Captura reiniciada. Comienza de nuevo con nuevos ángulos de tu rostro.', 'info');

        if (this.hiddenInput) {
            this.hiddenInput.value = '';
        }

        if (this.formFacial) {
            // ocultar otra vez el formulario
            this.formFacial.classList.add('d-none');
        }

        if (this.btnCapturar) {
            this.btnCapturar.disabled = false;
            this.btnCapturar.innerHTML = '<i class="bi bi-camera2 me-1"></i> Capturar';
        }

        if (this.modalCountSpan) {
            this.modalCountSpan.textContent = '0';
        }
    }

    updateCounter() {
        if (this.contadorNumero) {
            this.contadorNumero.textContent = String(this.descriptors.length);
        }
    }

    updateProgress() {
        if (!this.barraProgreso) return;
        const n = Math.min(this.descriptors.length, this.maxDescriptors);
        const porcentaje = (n / this.maxDescriptors) * 100;
        this.barraProgreso.style.width = porcentaje + '%';
        this.barraProgreso.setAttribute('aria-valuenow', String(n));
    }

    updateSystemStatus(type, text) {
        if (this.estadoSistema) {
            this.estadoSistema.className = `alert alert-${type} d-flex align-items-center gap-2`;
        }
        if (this.estadoTexto) {
            this.estadoTexto.textContent = text;
        }
    }

    updateDetectionStatus(badgeType, text) {
        if (!this.estadoDeteccion) return;

        this.estadoDeteccion.innerHTML = `
            <span class="badge text-bg-${badgeType}">
              <i class="bi bi-camera-video${badgeType === 'success' ? '' : '-off'} me-1"></i> ${text}
            </span>
        `;
    }

    showMessage(html, type) {
        if (!this.mensajeDiv) return;
        this.mensajeDiv.innerHTML = `
            <div class="alert alert-${type}">
              ${html}
            </div>
        `;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    if (!window.facialConfig) {
        window.facialConfig = new FacialConfiguration();
    }
    const ok = window.facialConfig.init();
    if (!ok) {
        console.error('No se pudo inicializar FacialConfiguration');
    }
});

// Limpiar recursos al cerrar la pestaña
window.addEventListener('beforeunload', () => {
    if (window.cameraManager) {
        window.cameraManager.stopCamera();
    }
});

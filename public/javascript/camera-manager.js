// javascript/camera-manager.js

class CameraManager {
    constructor() {
        this.currentStream = null;
    }

    /**
     * Inicia la cámara y asigna el stream al elemento <video>.
     * @param {HTMLVideoElement} videoElement
     * @param {MediaStreamConstraints} constraints
     * @returns {Promise<boolean>}
     */
    async startCamera(videoElement, constraints = { video: true, audio: false }) {
        try {
            // Detener cámara actual si ya hay una
            if (this.currentStream) {
                this.stopCamera();
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                console.error('getUserMedia no soportado');
                return false;
            }

            this.currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            videoElement.srcObject = this.currentStream;

            return new Promise((resolve) => {
                videoElement.onloadedmetadata = () => {
                    videoElement.play().then(() => resolve(true)).catch(() => resolve(true));
                };
            });
        } catch (error) {
            console.error('Error al iniciar cámara:', error);
            return false;
        }
    }

    /**
     * Detiene por completo la cámara actual.
     */
    stopCamera() {
        if (this.currentStream) {
            this.currentStream.getTracks().forEach(track => track.stop());
            this.currentStream = null;
        }
    }

    /**
     * Toma una “foto” del video y la dibuja en el canvas.
     * @param {HTMLVideoElement} videoElement
     * @param {HTMLCanvasElement} canvasElement
     * @returns {string} dataURL de la imagen en PNG
     */
    takePhoto(videoElement, canvasElement) {
        if (!videoElement || !canvasElement) return '';

        const context = canvasElement.getContext('2d');
        canvasElement.width = videoElement.videoWidth || 640;
        canvasElement.height = videoElement.videoHeight || 480;

        context.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

        return canvasElement.toDataURL('image/png');
    }
}

// Instancia global disponible para otros scripts
window.cameraManager = new CameraManager();

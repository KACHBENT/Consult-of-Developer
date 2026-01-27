// public/javascript/registro-pase-lista.js

(function () {
  let currentTipo = null; // 'asistencia' | 'pausa' | 'salida'

  const modalEl        = document.getElementById('modalAsistencia');
  const modalTitleSpan = document.getElementById('modalTituloTipo');
  const modalTexto     = document.getElementById('modalTextoAyuda');

  const tipoInput      = document.getElementById('tipo_evento');
  const descriptorInput= document.getElementById('descriptor_facial');
  const fotoInput      = document.getElementById('foto_base64');

  const video          = document.getElementById('videoAsistencia');
  const canvas         = document.getElementById('canvasAsistencia');
  const btnInitCam     = document.getElementById('btnIniciarCamaraAsistencia');
  const btnCapturar    = document.getElementById('btnCapturarRegistrar');
  const mensajeDiv     = document.getElementById('mensajeAsistencia');
  const form           = document.getElementById('formAsistencia');

  const msgGeneral     = document.getElementById('msg');

  if (!modalEl || !video || !canvas || !btnInitCam || !btnCapturar || !form) {
    console.error('registro-pase-lista.js: faltan elementos en el DOM');
    return;
  }

  function showInnerMessage(text, type = 'info') {
    if (!mensajeDiv) return;
    mensajeDiv.innerHTML = `<div class="alert alert-${type} mb-0">${text}</div>`;
  }

  function clearInnerMessage() {
    if (!mensajeDiv) return;
    mensajeDiv.innerHTML = '';
  }

  function showPageMessage(text, type = 'info') {
    if (!msgGeneral) return;
    msgGeneral.className = `alert mt-4 alert-${type}`;
    msgGeneral.textContent = text;
    msgGeneral.classList.remove('d-none');
  }

  function clearPageMessage() {
    if (!msgGeneral) return;
    msgGeneral.classList.add('d-none');
  }

  function getTituloPorTipo(tipo) {
    switch (tipo) {
      case 'asistencia': return 'Marcar asistencia';
      case 'pausa':      return 'Pausa de comida';
      case 'salida':     return 'Salida laboral';
      default:           return 'Asistencia';
    }
  }

  function getTextoPorTipoYEstado(tipo, entradaAbierta, comidaAbierta) {
    if (tipo === 'asistencia') {
      if (entradaAbierta) {
        return 'Ya tienes una entrada registrada hoy. La cámara solo se usará para validar tu identidad.';
      }
      return 'Vas a registrar tu entrada laboral. Sitúate frente a la cámara y toma una foto para continuar.';
    }

    if (tipo === 'pausa') {
      if (!entradaAbierta) {
        return 'No tienes jornada abierta. Primero debes registrar tu entrada laboral.';
      }
      if (comidaAbierta) {
        return 'Vas a registrar el fin de tu pausa de comida. Sitúate frente a la cámara y toma una foto para cerrar la pausa.';
      }
      return 'Vas a registrar el inicio de tu pausa de comida. Sitúate frente a la cámara y toma una foto.';
    }

    if (tipo === 'salida') {
      if (comidaAbierta) {
        return 'Tienes una pausa de comida abierta. Debes registrar primero el fin de comida antes de finalizar la jornada.';
      }
      return 'Vas a registrar tu salida laboral. Sitúate frente a la cámara y toma una foto para cerrar la jornada.';
    }

    return 'Sitúate frente a la cámara y toma una foto para registrar el evento.';
  }

  function generarDescriptorSimulado() {
    const arr  = [];
    const seed = Date.now() % 1000;
    for (let i = 0; i < 128; i++) {
      const v = Math.sin(seed + i * 0.13) * 0.5 + 0.5;
      arr.push(parseFloat(v.toFixed(6)));
    }
    return arr;
  }

  // Abrir modal desde los tiles
  document.querySelectorAll('.js-open-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      clearPageMessage();

      currentTipo = btn.getAttribute('data-tipo') || 'asistencia';
      const entradaAbierta = btn.getAttribute('data-entrada-abierta') === '1';
      const comidaAbierta  = btn.getAttribute('data-comida-abierta') === '1';

      // Reglas de interfaz (no bloquean backend, solo informan):
      if (currentTipo === 'pausa' && !entradaAbierta) {
        showPageMessage('Primero debes registrar tu entrada laboral antes de la pausa de comida.', 'warning');
        return;
      }

      if (currentTipo === 'salida' && comidaAbierta) {
        showPageMessage('Tienes una pausa de comida abierta. Cierra la pausa antes de finalizar la jornada.', 'warning');
        return;
      }

      if (modalTitleSpan) {
        modalTitleSpan.textContent = getTituloPorTipo(currentTipo);
      }

      if (tipoInput) {
        tipoInput.value = currentTipo;
      }

      if (modalTexto) {
        modalTexto.textContent = getTextoPorTipoYEstado(currentTipo, entradaAbierta, comidaAbierta);
      }

      descriptorInput.value = '';
      fotoInput.value = '';
      btnCapturar.disabled = true;
      clearInnerMessage();

      if (window.bootstrap) {
        const modal = new window.bootstrap.Modal(modalEl);
        modal.show();
      }
    });
  });

  // Iniciar cámara
  btnInitCam.addEventListener('click', async () => {
    clearInnerMessage();

    if (!window.cameraManager) {
      showInnerMessage('No se encontró CameraManager. Verifica los scripts.', 'danger');
      return;
    }

    showInnerMessage('Iniciando cámara…', 'info');

    const ok = await window.cameraManager.startCamera(video);
    if (ok) {
      showInnerMessage('Cámara lista. Cuando estés preparado, toma la foto para registrar el evento.', 'success');
      btnCapturar.disabled = false;
    } else {
      showInnerMessage('No se pudo iniciar la cámara. Verifica permisos y dispositivo.', 'danger');
      btnCapturar.disabled = true;
    }
  });

  // Capturar foto y enviar formulario
  btnCapturar.addEventListener('click', () => {
    clearInnerMessage();

    if (!window.cameraManager) {
      showInnerMessage('No se encontró CameraManager. Verifica los scripts.', 'danger');
      return;
    }

    if (!video || video.readyState !== 4) {
      showInnerMessage('La cámara aún no está lista. Espera un momento.', 'warning');
      return;
    }

    try {
      const foto = window.cameraManager.takePhoto(video, canvas);
      fotoInput.value = foto;

      const descriptor = generarDescriptorSimulado();
      descriptorInput.value = JSON.stringify(descriptor);

      showInnerMessage('Foto capturada. Registrando evento...', 'info');

      form.submit(); // El controlador redirige a acceso/access-assistance
    } catch (e) {
      console.error(e);
      showInnerMessage('Error al capturar la foto: ' + e.message, 'danger');
    }
  });

  // Al cerrar el modal, apagar cámara
  modalEl.addEventListener('hidden.bs.modal', () => {
    if (window.cameraManager) {
      window.cameraManager.stopCamera();
    }
    btnCapturar.disabled = true;
    clearInnerMessage();
  });

})();

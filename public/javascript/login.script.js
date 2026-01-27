 document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('modalClavePaseLista');
    if (!modalEl) return;

    var modal = new bootstrap.Modal(modalEl);
    modal.show();

    var inputClave = document.getElementById('clavePaseListaInput');
    var btnCopiar = document.getElementById('btnCopiarClavePaseLista');

    if (btnCopiar && inputClave) {
      btnCopiar.addEventListener('click', function () {
        inputClave.select();
        inputClave.setSelectionRange(0, 99999); // soporte móvil

        try {
          document.execCommand('copy');
        } catch (e) {
          if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(inputClave.value);
          }
        }
      });
    }
  });
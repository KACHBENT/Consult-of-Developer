<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    <?= esc($title ?? 'TechNova Consulting') ?>
    <?php if (session()->get('usuario')): ?>
      | <?= esc(session()->get('usuario')['rol']) ?>
    <?php endif; ?>
  </title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="<?= base_url('bootstrap-5.3.3/css/bootstrap.min.css') ?>">

  <!-- Estilos base -->
  <link rel="stylesheet" href="<?= base_url('css/index.styles.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/footer.styles.css') ?>">

  <?= $this->renderSection('css') ?>

  <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
</head>

<body class="fondo">

  <!-- NAVBAR -->
  <?= $this->renderSection('navbar') ?>

  <!-- CONTENEDOR DE TOAST -->
  <div id="toastMount" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="toastContainer"></div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container-fluid py-4">
    <?= $this->renderSection('content') ?>
  </main>

  <!-- FOOTER -->
  <?= $this->renderSection('footer') ?>

  <!-- SCRIPTS -->
  <script src="<?= base_url('bootstrap-5.3.3/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('JQuery/jquery.min.js') ?>"></script>

  <?= $this->renderSection('scripts') ?>

  <!-- SISTEMA DE TOAST GLOBAL -->
  <script>
    (function () {
      function makeToast(message, bsType) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        let formattedMessage = '';
        if (Array.isArray(message)) {
          formattedMessage = `<ul class="mb-0 ps-3">${message.map(m => `<li>${m}</li>`).join('')}</ul>`;
        } else if (typeof message === 'string') {
          formattedMessage = message.includes('<br>')
            ? message
            : message.replace(/\n/g, '<br>');
        }

        const el = document.createElement('div');
        el.className = `toast align-items-center text-bg-${bsType} border-0 shadow mb-2`;
        el.innerHTML = `
          <div class="d-flex">
            <div class="toast-body">${formattedMessage}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>`;

        container.appendChild(el);
        const inst = bootstrap.Toast.getOrCreateInstance(el, { delay: 6000 });
        inst.show();
        el.addEventListener('hidden.bs.toast', () => el.remove());
      }

      window.showToastSuccess = m => makeToast(m, 'success');
      window.showToastError   = m => makeToast(m, 'danger');
    })();
  </script>

  <!-- FLASHDATA -->
  <?php if ($ok = session()->getFlashdata('toast_success')): ?>
    <script>
      showToastSuccess(<?= json_encode($ok, JSON_UNESCAPED_UNICODE) ?>);
    </script>
  <?php endif; ?>

  <?php if ($err = session()->getFlashdata('toast_error')): ?>
    <script>
      showToastError(<?= json_encode($err, JSON_UNESCAPED_UNICODE) ?>);
    </script>
  <?php endif; ?>

</body>
</html>

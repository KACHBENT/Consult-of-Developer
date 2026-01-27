<?= $this->extend('layout/main') ?> <!-- ➊ heredamos layout -->

<?= $this->section('css') ?> <!-- ➋ CSS sólo de esta página -->
<link rel="stylesheet" href="<?= base_url('css/acceso-pase-lista.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="content" class="container py-4">

  <div class="d-flex justify-content-center align-items-center min-vh-80">
    <div class="card shadow-lg card-auth">
      <!-- HEADER -->
      <div class="card-header bg-gradient-menta text-white linea-contenedor p-4">
        <h5 class="mb-0 d-flex align-items-center gap-2">
          <img src="<?= base_url('images/icons/inventory.svg') ?>" alt="Inventario" width="20" height="20">
          Acceso a pase de lista
        </h5>
      </div>

      <!-- BODY -->
      <div class="card-body p-4">
        <p class="text-secondary text-center mb-3">
          Ingresa tu clave de acceso para iniciar el pase de lista.
        </p>

        <!-- MENSAJES POR FLASHDATA -->
        <?php if (session()->getFlashdata('toast_error')): ?>
          <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('toast_error')) ?>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('toast_success')): ?>
          <div class="alert alert-success">
            <?= esc(session()->getFlashdata('toast_success')) ?>
          </div>
        <?php endif; ?>

        <!-- FORMULARIO (PURO PHP) -->
        <form id="formAccess" action="<?= site_url('acceso/pin-verify') ?>" method="post" autocomplete="off">
          <?= csrf_field() ?>

          <!-- Input PIN -->
          <div class="input-group mb-3">
            <span class="input-group-text">
              <img src="<?= base_url('images/icons/password.svg') ?>" class="darken" alt="PIN" loading="lazy">
            </span>
            <input
              type="password"
              id="passInput"
              name="pin"
              class="form-control"
              value="<?= old('pin') ?>"
              placeholder="Clave de acceso"
              required
            >
            <button
              type="button"
              id="btnToggle"
              class="btn btn-outline-secondary border-lighter"
              data-icon-view="<?= base_url('images/icons/view.svg') ?>"
              data-icon-hide="<?= base_url('images/icons/visibility_off.svg') ?>"
            >
              <img
                src="<?= base_url('images/icons/visibility_off.svg') ?>"
                class="darken"
                alt="Ocultar"
                width="20"
                height="20"
              >
            </button>
          </div>

          <!-- Indicador de seguridad / estado -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <small id="lengthHint" class="text-muted">Mínimo 4 dígitos</small>
            <span id="strengthDot" class="dot dot-weak" title="Fortaleza"></span>
          </div>

          <!-- Teclado numérico -->
          <div class="keypad-grid">
            <!-- fila 1 -->
            <button type="button" class="btn btn-key" data-key="1">1</button>
            <button type="button" class="btn btn-key" data-key="2">2</button>
            <button type="button" class="btn btn-key" data-key="3">3</button>
            <!-- fila 2 -->
            <button type="button" class="btn btn-key" data-key="4">4</button>
            <button type="button" class="btn btn-key" data-key="5">5</button>
            <button type="button" class="btn btn-key" data-key="6">6</button>
            <!-- fila 3 -->
            <button type="button" class="btn btn-key" data-key="7">7</button>
            <button type="button" class="btn btn-key" data-key="8">8</button>
            <button type="button" class="btn btn-key" data-key="9">9</button>
            <!-- fila 4 -->
            <button type="button" class="btn btn-key btn-action" data-key="clear" title="Limpiar">
              <img
                src="<?= base_url('images/icons/delete.svg') ?>"
                class="darken"
                alt="Limpiar"
                width="30"
                height="30"
              >
            </button>
            <button type="button" class="btn btn-key" data-key="0">0</button>
            <button type="button" class="btn btn-key btn-action" data-key="back" title="Borrar">
              <img
                src="<?= base_url('images/icons/backspace.svg') ?>"
                class="darken"
                alt="Borrar"
                width="30"
                height="30"
              >
            </button>
          </div>

          <!-- Acciones -->
          <div class="d-flex gap-2 mt-4">
            <button id="btnSubmit" class="btn btn-menta w-100" type="submit">
              <i class="bi bi-play-circle me-1"></i> Iniciar pase de lista
            </button>
            <button id="btnClearAll" class="btn btn-outline-secondary border-lighter" type="button">
              <img
                src="<?= base_url('images/icons/select_all.svg') ?>"
                class="darken"
                alt="Limpiar todo"
                width="30"
                height="30"
              >
            </button>
          </div>
        </form>

        <!-- Mensaje interno (si quieres usarlo con JS) -->
        <div id="msg" class="alert mt-3 d-none" role="alert"></div>
      </div>

      <!-- FOOTER -->
      <div class="card-footer text-center small text-muted">
        <i class="bi bi-info-circle"></i> Por seguridad, evita compartir tu contraseña.
      </div>
    </div>
  </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('javascript/AccesoPaseLista.script.js') ?>"></script>
<?= $this->endSection() ?>

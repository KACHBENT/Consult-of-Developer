<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('css/registro-pase-lista.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="content" class="container py-4">

  <div class="d-flex justify-content-center align-items-center min-vh-60">
    <div class="card shadow-lg card-auth">

      <!-- Encabezado -->
      <div class="card-header bg-gradient-menta text-white linea-contenedor p-4">
        <h5 class="mb-0 d-flex align-items-center gap-2">
          <img src="<?= base_url('images/icons/inventory.svg') ?>" alt="Pase lista" width="20" height="20">
          Registrar pase de lista
        </h5>
      </div>

      <!-- Cuerpo -->
      <div class="card-body p-4">

        <!-- Mensajes de servidor -->
        <?php if (session()->getFlashdata('toast_error')): ?>
          <div class="alert alert-danger">
            <?= is_array(session()->getFlashdata('toast_error'))
              ? esc(implode(' ', session()->getFlashdata('toast_error')))
              : esc(session()->getFlashdata('toast_error')) ?>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('toast_success')): ?>
          <div class="alert alert-success">
            <?= esc(session()->getFlashdata('toast_success')) ?>
          </div>
        <?php endif; ?>

        <!-- Estado actual de la jornada según la BD -->
        <div class="mb-3">
          <?php if (!$tieneEntradaAbierta && !$tieneComidaAbierta): ?>
            <div class="alert alert-secondary d-flex align-items-center gap-2 mb-0">
              <i class="bi bi-clock-history"></i>
              <span>No tienes eventos abiertos hoy. Comienza registrando tu <strong>entrada laboral</strong>.</span>
            </div>
          <?php elseif ($tieneEntradaAbierta && !$tieneComidaAbierta): ?>
            <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
              <i class="bi bi-briefcase"></i>
              <span>Jornada laboral en curso. Puedes registrar <strong>pausa de comida</strong> o <strong>salida laboral</strong>.</span>
            </div>
          <?php elseif ($tieneComidaAbierta): ?>
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
              <i class="bi bi-cup-hot"></i>
              <span>Pausa de comida en curso. Primero debes registrar el <strong>fin de comida</strong> antes de finalizar jornada.</span>
            </div>
          <?php endif; ?>
        </div>

        <p class="text-secondary text-center mb-4">
          Selecciona una opción para registrar tu evento de asistencia con validación facial.
        </p>

        <!-- Acciones como “tiles” grandes -->
        <div class="row g-3 action-grid">

          <!-- Entrada / Asistencia -->
          <div class="col-12">
            <button type="button"
                    class="action-tile asistencia w-100 js-open-modal"
                    data-tipo="asistencia"
                    data-entrada-abierta="<?= $tieneEntradaAbierta ? '1' : '0' ?>"
                    data-comida-abierta="<?= $tieneComidaAbierta ? '1' : '0' ?>">
              <span class="action-icon">
                <img src="<?= base_url('images/icons/work.svg') ?>" alt="asistencia" width="30" height="30">
              </span>
              <span class="action-text">
                <strong>Marcar asistencia</strong>
                <small class="d-block">
                  <?= $tieneEntradaAbierta ? 'Ya tienes una entrada hoy' : 'Entrada / Reingreso' ?>
                </small>
              </span>
              <span class="action-chevron">
                <?php if ($tieneEntradaAbierta): ?>
                  <span class="badge bg-success">Activa hoy</span>
                <?php else: ?>
                  <img src="<?= base_url('images/icons/exit_to_app.svg') ?>" class="darken" alt="ir" width="30" height="30">
                <?php endif; ?>
              </span>
            </button>
          </div>

          <!-- Pausa comida -->
          <div class="col-12">
            <button type="button"
                    class="action-tile pausa w-100 js-open-modal"
                    data-tipo="pausa"
                    data-entrada-abierta="<?= $tieneEntradaAbierta ? '1' : '0' ?>"
                    data-comida-abierta="<?= $tieneComidaAbierta ? '1' : '0' ?>">
              <span class="action-icon">
                <img src="<?= base_url('images/icons/free_breakfast.svg') ?>" alt="comida" width="30" height="30">
              </span>
              <span class="action-text">
                <strong>Pausa de comida</strong>
                <small class="d-block">
                  <?php if (!$tieneEntradaAbierta): ?>
                    Requiere tener entrada registrada
                  <?php elseif ($tieneComidaAbierta): ?>
                    Registrar fin de comida
                  <?php else: ?>
                    Inicio / Fin de comida
                  <?php endif; ?>
                </small>
              </span>
              <span class="action-chevron">
                <?php if ($tieneComidaAbierta): ?>
                  <span class="badge bg-warning text-dark">En pausa</span>
                <?php else: ?>
                  <img src="<?= base_url('images/icons/exit_to_app.svg') ?>" class="darken" alt="ir" width="30" height="30">
                <?php endif; ?>
              </span>
            </button>
          </div>

          <!-- Salida laboral -->
          <div class="col-12">
            <button type="button"
                    class="action-tile salida w-100 js-open-modal"
                    data-tipo="salida"
                    data-entrada-abierta="<?= $tieneEntradaAbierta ? '1' : '0' ?>"
                    data-comida-abierta="<?= $tieneComidaAbierta ? '1' : '0' ?>">
              <span class="action-icon">
                <img src="<?= base_url('images/icons/work_history.svg') ?>" alt="salida" width="30" height="30">
              </span>
              <span class="action-text">
                <strong>Salida laboral</strong>
                <small class="d-block">
                  <?php if ($tieneComidaAbierta): ?>
                    Cierra primero tu pausa de comida
                  <?php elseif (!$tieneEntradaAbierta): ?>
                    No hay jornada abierta hoy
                  <?php else: ?>
                    Fin de jornada
                  <?php endif; ?>
                </small>
              </span>
              <span class="action-chevron">
                <img src="<?= base_url('images/icons/exit_to_app.svg') ?>" class="darken" alt="ir" width="30" height="30">
              </span>
            </button>
          </div>
        </div>

        <!-- Mensaje dinámico cliente -->
        <div id="msg" class="alert mt-4 d-none" role="alert"></div>

        <!-- Info secundaria -->
        <div class="mt-3 small text-muted d-flex align-items-center gap-2">
          <i class="bi bi-info-circle"></i>
          <p class="mb-0">
            Cada evento queda registrado con fecha y hora del sistema.
          </p>
        </div>

        <div class="text-center pt-2">
          <a href="<?= base_url('settings-faces') ?>" class="reference-login">
            ¿Ya configuraste tu acceso facial? ¡Comienza ahora!
          </a>
        </div>
      </div>

      <!-- Footer -->
      <div class="card-footer text-center small text-muted">
        <i class="bi bi-shield-lock"></i> Sus datos se protegen conforme a las políticas internas.
      </div>
    </div>
  </div>

  <!-- MODAL DE CÁMARA PARA TODOS LOS EVENTOS -->
  <div class="modal fade" id="modalAsistencia" tabindex="-1"
       aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <div class="modal-header bg-gradient-menta linea-contenedor">
          <h6 class="modal-title text-white" id="modalAsistenciaLabel">
            <i class="bi bi-person-bounding-box me-1"></i>
            <span id="modalTituloTipo">Asistencia</span> con reconocimiento facial
          </h6>
          <button type="button" class="btn-close invert" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- formulario CI4 -->
          <form id="formAsistencia"
                method="post"
                action="<?= site_url('pase-lista/registrar') ?>">
            <?= csrf_field() ?>

            <!-- Tipo de evento (asistencia|pausa|salida) -->
            <input type="hidden" name="tipo_evento" id="tipo_evento">
            <!-- Descriptor facial simulado -->
            <input type="hidden" name="descriptor_facial" id="descriptor_facial">
            <!-- Foto opcional en base64 -->
            <input type="hidden" name="foto_base64" id="foto_base64">

            <p class="small text-muted mb-2" id="modalTextoAyuda">
              Sitúate frente a la cámara y presiona <strong>“Iniciar cámara”</strong>. Luego toma una foto para registrar el evento.
            </p>

            <!-- Video + canvas -->
            <div class="video-box rounded-3 overflow-hidden mb-3 position-relative">
              <div class="ratio ratio-4x3 bg-dark-subtle">
                <video id="videoAsistencia" class="w-100 h-100 object-fit-cover" autoplay muted playsinline></video>
              </div>

              <div class="video-toolbar">
                <button type="button" class="btn btn-sm btn-primary" id="btnIniciarCamaraAsistencia">
                  <i class="bi bi-camera-video me-1"></i> Iniciar cámara
                </button>
                <button type="button" class="btn btn-sm btn-success" id="btnCapturarRegistrar" disabled>
                  <i class="bi bi-camera2 me-1"></i> Tomar foto y registrar
                </button>
              </div>

              <canvas id="canvasAsistencia" class="d-none"></canvas>
            </div>

            <!-- Mensaje dentro del modal -->
            <div id="mensajeAsistencia"></div>

          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
        </div>

      </div>
    </div>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('javascript/camera-manager.js') ?>"></script>
<script src="<?= base_url('javascript/registro-pase-lista.script.js') ?>"></script>
<?= $this->endSection() ?>

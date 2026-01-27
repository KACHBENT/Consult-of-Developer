<?= $this->extend('layout/main') ?> <!-- ➊ heredamos layout -->

<?= $this->section('css') ?> <!-- ➋ CSS sólo de esta página -->
<link rel="stylesheet" href="<?= base_url('css/configuraciones-faciales.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="content" class="container mt-6 py-4">
  <div class="row justify-content-center g-4">
    <div class="col-12 col-lg-10">
      <div class="card shadow-sm card-lift">
        <!-- ENCABEZADO -->
        <div class="card-header bg-gradient-menta text-white linea-contenedor p-4">
          <h4 class="card-title mb-0 d-flex align-items-center gap-2">
            <img src="<?= base_url('images/icons/settings-face.svg') ?>" class="invert" alt="Configuración facial"
              width="50" height="50">
            Configuración de Reconocimiento Facial
          </h4>
        </div>

        <!-- CUERPO -->
        <div class="card-body">
          <div class="row g-4">
            <!-- LADO IZQUIERDO: estado + instrucciones -->
            <aside class="col-12 col-md-5">
              <div class="position-md-sticky top-md-16 d-flex flex-column gap-4">

                <!-- Estado actual -->
                <div class="card border-0 shadow-xs">
                  <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-activity"></i> Estado actual
                    </h5>
                  </div>
                  <div class="card-body">
                    <?php if (!empty($descriptores_actuales)): ?>
                      <div class="alert alert-success d-flex align-items-start gap-2 mb-0">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>
                          <strong>Configurado</strong>
                          <p class="mb-0 mt-1">
                            Tienes <strong><?= count($descriptores_actuales); ?></strong>
                            descriptor(es) almacenado(s).
                          </p>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="alert alert-warning d-flex align-items-start gap-2 mb-0">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                          <strong>Pendiente</strong>
                          <p class="mb-0 mt-1">Aún no has configurado el reconocimiento facial.</p>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Instrucciones -->
                <div class="card border-0 shadow-xs">
                  <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-list-check"></i> Instrucciones
                    </h5>
                  </div>
                  <div class="card-body">
                    <ol class="list-group list-group-numbered list-group-flush">
                      <li class="list-group-item px-0">Buena iluminación frontal.</li>
                      <li class="list-group-item px-0">Mira a la cámara.</li>
                      <li class="list-group-item px-0">Expresión neutral.</li>
                      <li class="list-group-item px-0">Captura 3–5 ángulos.</li>
                      <li class="list-group-item px-0">Evita accesorios que oculten el rostro.</li>
                    </ol>
                    <div class="mt-3 p-3 bg-light rounded d-flex gap-2 small text-muted">
                      <i class="bi bi-shield-check"></i>
                      <span><strong>Nota:</strong> Todo se procesa en tu navegador.</span>
                    </div>
                    <button class="btn btn-outline-secondary w-100 mt-2" data-bs-toggle="modal"
                      data-bs-target="#modalAyudaCamara">
                      <i class="bi bi-question-circle me-1"></i> Problemas con la cámara
                    </button>
                  </div>
                </div>

              </div>
            </aside>

            <!-- LADO DERECHO: captura -->
            <section class="col-12 col-md-7">
              <div class="card border-0 shadow-xs">
                <div class="card-header bg-success text-white">
                  <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-person-bounding-box"></i>
                    Configurar reconocimiento facial
                  </h5>
                </div>

                <div class="card-body">

                  <!-- MENSAJES DE SERVIDOR (flashdata) -->
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

                  <!-- Estado del sistema -->
                  <div id="estadoSistema" class="alert alert-info d-flex align-items-center gap-2">
                    <span class="status-dot dot-info"></span>
                    <strong>Estado del sistema:</strong>
                    <span id="estadoTexto" class="ms-1">Inicializando…</span>
                  </div>

                  <!-- Video + toolbar -->
                  <div class="video-box rounded-3 overflow-hidden mb-3 position-relative">
                    <div class="ratio ratio-4x3 bg-dark-subtle">
                      <video id="video" class="w-100 h-100 object-fit-cover" autoplay muted playsinline></video>
                    </div>

                    <!-- Barra de acciones sobre el video -->
                    <div class="video-toolbar">
                      <button type="button" class="btn btn-sm btn-primary" id="btnIniciarCamara">
                        <i class="bi bi-camera-video me-1"></i> Iniciar
                      </button>
                      <button type="button" class="btn btn-sm btn-success" id="btnCapturar" disabled>
                        <i class="bi bi-camera2 me-1"></i> Capturar
                      </button>
                      <button type="button" class="btn btn-sm btn-warning" id="btnReiniciar" disabled
                        data-bs-toggle="modal" data-bs-target="#modalReiniciar">
                        <i class="bi bi-arrow-repeat me-1"></i> Reiniciar
                      </button>
                    </div>

                    <!-- Canvases ocultos -->
                    <canvas id="canvas" class="d-none"></canvas>
                    <canvas id="canvasDibujo" width="640" height="480" class="d-none"></canvas>
                  </div>

                  <!-- Estado detección -->
                  <div id="estadoDeteccion" class="mb-2">
                    <span class="badge text-bg-secondary">
                      <i class="bi bi-camera-video-off me-1"></i> Cámara no iniciada
                    </span>
                  </div>

                  <!-- Progreso descriptores -->
                  <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small class="text-muted">Descriptores capturados</small>
                      <small class="text-muted"><span id="contadorNumero">0</span>/5</small>
                    </div>
                    <div class="progress" role="progressbar" aria-label="Progreso de descriptores" aria-valuenow="0"
                      aria-valuemin="0" aria-valuemax="5">
                      <div id="barraProgreso" class="progress-bar progress-bar-striped" style="width:0%"></div>
                    </div>
                  </div>

                  <!-- Mensaje dinámico -->
                  <div id="mensaje" class="mb-3"></div>

                  <!-- FORMULARIO PARA GUARDAR PLANTILLAS -->
                  <form id="formFacial" method="post" action="<?= site_url('settings-faces/save') ?>"
                    class="mt-2 d-none">
                    <?= csrf_field() ?>
                    <input type="hidden" name="descriptores_faciales" id="descriptores_faciales">
                    <div class="d-grid">
                      <button type="button" class="btn btn-menta btn-lg" data-bs-toggle="modal"
                        data-bs-target="#modalGuardar" id="btnGuardar">
                        <i class="bi bi-save2 me-1"></i> Guardar configuración final
                      </button>
                    </div>
                  </form>

                </div>
              </div>
            </section>
          </div>
           <div class="text-center pt-2">
          <a href="<?= base_url('acceso/register-assistance') ?>" class="reference-login">¿Ya tienes tu configuración
            facial? ¡Toma tu asistencia ahora!</a>
        </div>
        </div>
        <div class="card-footer text-center small text-muted">
        <i class="bi bi-shield-lock"></i> Sus datos se protegen conforme a las políticas internas.
      </div>
      </div>
    </div>
  </div>

  <!-- TOAST (opcional, si lo usas con JS) -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="appToast" class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite"
      aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body" id="toastText">Acción ejecutada correctamente.</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <!-- MODAL: Reiniciar captura -->
  <div class="modal fade" id="modalReiniciar" tabindex="-1" aria-labelledby="modalReiniciarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-gradient-menta linea-contenedor">
          <h6 class="modal-title text-white" id="modalReiniciarLabel">
            <i class="bi bi-arrow-repeat me-1"></i> Reiniciar captura
          </h6>
          <button type="button" class="btn-close invert" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas reiniciar el proceso? Se perderán los descriptores capturados hasta ahora.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-warning" id="confirmReiniciar">Reiniciar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL: Guardar configuración -->
  <div class="modal fade" id="modalGuardar" tabindex="-1" aria-labelledby="modalGuardarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-gradient-menta linea-contenedor">
          <h6 class="modal-title text-white" id="modalGuardarLabel">
            <i class="bi bi-save2 me-1"></i> Guardar configuración
          </h6>
          <button type="button" class="btn-close invert" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-muted">
          Vas a guardar <strong><span id="modalCount">0</span>/5</strong> descriptores. ¿Deseas continuar?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <!-- Envío normal del formulario -->
          <button type="submit" form="formFacial" class="btn btn-success" id="btnGuardarModal">
            Guardar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL: Ayuda con la cámara -->
  <div class="modal fade" id="modalAyudaCamara" tabindex="-1" aria-labelledby="modalAyudaCamaraLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-gradient-menta linea-contenedor">
          <h6 class="modal-title text-white" id="modalAyudaCamaraLabel">
            <i class="bi bi-question-circle me-1"></i> Ayuda con la cámara
          </h6>
          <button type="button" class="btn-close invert" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Permite el acceso a la cámara en tu navegador.</li>
            <li class="list-group-item">Cierra otras apps que usen la cámara (Teams/Zoom/etc.).</li>
            <li class="list-group-item">Prueba otro navegador (Chrome/Edge/Firefox).</li>
            <li class="list-group-item">En móviles, usa el navegador del sistema.</li>
          </ul>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-dismiss="modal" id="reintentarCamara">
            <i class="bi bi-camera-video me-1"></i> Reintentar iniciar
          </button>
        </div>
      </div>
    </div>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?> <!-- ➍ scripts extra -->
<script src="<?= base_url('javascript/camera-manager.js') ?>"></script>
<script src="<?= base_url('javascript/facial-configuration.js') ?>"></script>
<!-- si luego usas asistencia facial por cámara, aquí podrías cargar facial-attendance.js -->
<?= $this->endSection() ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('css/asistencia-historial.styles.css') ?>">

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main id="content" class="container py-4">
  <?= $this->section('navbar') ?>
  <?= $this->include('layout/NavBar') ?>
  <?= $this->endSection() ?>
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

        <!-- Encabezado -->
        <div
          class="card-header bg-gradient-menta text-white linea-contenedor d-flex flex-wrap align-items-center gap-2 p-4">
             <img src="<?= base_url('images/icons/inventory.svg') ?>" class="white" alt="ir" width="30" height="30">
          <div>
            <h4 class="mb-0">Historial de asistencia</h4>
            <small>Consulta tu línea de tiempo del día y genera reportes en PDF.</small>
          </div>
        </div>
             
        <div class="card-body p-4">
          <div class="align-content-center mb-4">
            <form class="d-flex gap-2 align-items-center" method="get" action="<?= site_url('asistencia/historial') ?>">
              <div class="input-group">
                <input type="date" name="fecha" class="form-control" value="<?= esc($fechaSeleccionada) ?>">
              </div>
              <button type="submit" class="btn btn-primary">
                <img src="<?= base_url('images/icons/today.svg') ?>" class="white" alt="ir" width="30" height="30">
              </button>
              <a href="<?= site_url('asistencia/pdf-dia?fecha=' . urlencode($fechaSeleccionada)) ?>"
                class="btn btn-primary">
                <img src="<?= base_url('images/icons/article.svg') ?>" class="white" alt="ir" width="30" height="30">
              </a>
            </form>
          </div>
          <!-- Encabezado de día -->
          <div class="mb-4 p-3 rounded-3 text-white" style="background: linear-gradient(90deg,#16a34a,#22c55e);">
            <h6 class="mb-1">
              <i class="bi bi-clock-history me-1"></i>
              Línea de tiempo del día
              <strong><?= date('d/m/Y', strtotime($fechaSeleccionada)) ?></strong>
            </h6>

            <?php if (empty($eventos)): ?>
              <p class="mb-0 small">
                Aún no se ha registrado ningún pase de lista para este día. Utiliza el modo de
                <strong>pase de lista</strong> para registrar tu entrada, pausa de comida y salida.
              </p>
            <?php else: ?>
              <p class="mb-0 small">
                Se muestran todos los eventos de asistencia registrados en la fecha seleccionada.
              </p>
            <?php endif; ?>
          </div>

          <?php if (empty($eventos)): ?>
            <div class="alert alert-warning border-start border-4 border-warning">
              <div class="d-flex gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                  <strong>No hay registros de asistencia en la fecha seleccionada.</strong>
                  <p class="mb-0 small">
                    Aún no se ha registrado ningún pase de lista para este día.
                    <a href="<?= site_url('acceso/access-assistance') ?>">Ir al acceso de pase de lista</a>.
                  </p>
                </div>
              </div>
            </div>

          <?php else: ?>
            <div class="position-relative ps-4">
              <div class="timeline-line"></div>

              <div class="d-flex flex-column gap-3">
                <?php foreach ($eventos as $ev): ?>
                  <?php
                  $isBreak = ($ev['tipo'] === 'comida');
                  $cardClass = $isBreak ? 'break' : '';
                  $dotClass = $isBreak ? 'break' : '';
                  $badgeClass = $isBreak ? 'badge-break' : 'badge-work';
                  $textoTiempo = $isBreak ? 'Tiempo en pausa:' : 'Tiempo trabajado:';
                  $mins = (int) $ev['duracion_min'];
                  $rangoHora = $ev['fin']
                    ? date('H:i', strtotime($ev['inicio'])) . ' – ' . date('H:i', strtotime($ev['fin']))
                    : date('H:i', strtotime($ev['inicio'])) . ' – En curso';
                  ?>
                  <div class="timeline-card <?= $cardClass ?>">
                    <span class="timeline-dot <?= $dotClass ?>"></span>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge <?= $badgeClass ?>">
                          <?= esc($ev['estado_raw']) ?>
                        </span>
                        <strong><?= esc($ev['label']) ?></strong>
                      </div>
                      <span class="badge bg-light text-muted border">
                        <i class="bi bi-clock me-1"></i><?= esc($rangoHora) ?>
                      </span>
                    </div>

                    <div class="small text-muted mb-1">
                      <?= esc($textoTiempo) ?>
                      <strong><?= $mins ?> min</strong>
                    </div>

                    <?php if (!empty($ev['extra'])): ?>
                      <div class="small text-muted">
                        <?= esc($ev['extra']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <hr class="my-4">
            <div class="row g-3">
              <div class="col-md-4">
                <div class="border rounded-3 p-3 bg-light">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-work text-white">
                      <i class="bi bi-briefcase-fill"><img src="<?= base_url('images/icons/work_history.svg') ?>" class="white" alt="ir" width="30" height="30"></i>
                    </span>
                    <div>
                      <div class="small text-muted">Tiempo total trabajado</div>
                      <strong>
                        <?= $totalTrabajoMin ?> min
                        (<?= number_format($totalTrabajoMin / 60, 2) ?> h)
                      </strong>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="border rounded-3 p-3 bg-light">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-break text-white">
                      <i class="bi bi-cup-hot-fill"><img src="<?= base_url('images/icons/free_breakfast.svg') ?>" class="white" alt="ir" width="30" height="30"></i>
                    </span>
                    <div>
                      <div class="small text-muted">Tiempo en pausas de comida</div>
                      <strong>
                        <?= $totalComidaMin ?> min
                        (<?= number_format($totalComidaMin / 60, 2) ?> h)
                      </strong>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="border rounded-3 p-3 bg-light">
                  <div class="small text-muted mb-1">
                    Referencias de color
                  </div>
                  <div class="d-flex flex-column gap-1 small">
                    <div><span class="badge badge-work text-white me-1">Entrada/Salida</span> = eventos de trabajo.</div>
                    <div><span class="badge badge-break text-white me-1">Comida</span> = pausas de comida.</div>
                  </div>
                </div>
              </div>
            </div>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('css/inicio.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="content" class="container py-4 dashboard">
  <!-- HERO -->
  <section class="hero card border-0 shadow-sm overflow-hidden mb-4">
    <div class="hero-bg"></div>
    <div class="card-body text-center py-5 px-3 px-md-5 position-relative">
      <div class="display-4 mb-2" aria-hidden="true">📸</div>
      <h1 class="fw-bold mb-2 hero-title">Sistema de Asistencia Facial</h1>
      <p class="lead text-white-75 mb-4">
        Registra tu asistencia de forma rápida, segura y moderna mediante reconocimiento facial.
      </p>
      <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
        <a href="<?= site_url('asistencia/historial') ?>" class="btn btn-outline-light btn-lg px-4">
          Ver Reportes
        </a>
      </div>
    </div>
  </section>

  <section>
    <div class="text-center mb-4">
      <h2 class="fw-semibold m-0">¿Cómo funciona?</h2>
      <p class="text-white mt-1">Cuatro pasos sencillos para mantener el control</p>
    </div>

    <div class="row g-3 g-md-4">
      <!-- Paso 1 -->
      <div class="col-6 col-md-3">
        <div class="step-card h-100 text-center p-3 p-md-4 border rounded-4 shadow-sm">
          <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center mb-2">1</div>
          <h6 class="mb-1">Registro</h6>
          <p class="small text-muted mb-0">Crea tu cuenta y configura tu rostro</p>
        </div>
      </div>
      <!-- Paso 2 -->
      <div class="col-6 col-md-3">
        <div class="step-card h-100 text-center p-3 p-md-4 border rounded-4 shadow-sm">
          <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center mb-2">2</div>
          <h6 class="mb-1">Autenticación</h6>
          <p class="small text-muted mb-0">Registra entradas y salidas con tu rostro</p>
        </div>
      </div>
      <!-- Paso 3 -->
      <div class="col-6 col-md-3">
        <div class="step-card h-100 text-center p-3 p-md-4 border rounded-4 shadow-sm">
          <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center mb-2">3</div>
          <h6 class="mb-1">Reportes</h6>
          <p class="small text-muted mb-0">Consulta incidencias e historial</p>
        </div>
      </div>
      <!-- Paso 4 -->
      <div class="col-6 col-md-3">
        <div class="step-card h-100 text-center p-3 p-md-4 border rounded-4 shadow-sm">
          <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center mb-2">4</div>
          <h6 class="mb-1">Control</h6>
          <p class="small text-muted mb-0">Administración y permisos granulares</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES (opcional) -->
  <section class="mt-5">
    <div class="row g-3 g-md-4">
      <div class="col-md-4">
        <div class="feature-card card h-100 border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-semibold mb-1">Precisión</h5>
            <p class="text-muted mb-0 small">Modelos de reconocimiento facial con plantillas seguras (embeddings cifrados).</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card card h-100 border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-semibold mb-1">Velocidad</h5>
            <p class="text-muted mb-0 small">Registros en milisegundos, ideal para picos de entrada/salida.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card card h-100 border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-semibold mb-1">Auditoría</h5>
            <p class="text-muted mb-0 small">Logs de validación, estados de asistencia y trazabilidad completa.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts propios opcionales -->
<?= $this->endSection() ?>

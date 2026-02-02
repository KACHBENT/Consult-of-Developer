    <?= $this->extend('layout/main') ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5" style="max-width: 900px;">
  <div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
      <h3 class="fw-bold mb-2">¡Listo! ✅</h3>
      <p class="text-muted mb-3">
        Te enviamos un correo para <b>verificar tu email</b>.
        Revisa tu bandeja de entrada y spam.
      </p>
      <a href="<?= base_url('contacto') ?>" class="btn btn-outline-primary">Volver</a>
      <a href="<?= base_url('/') ?>" class="btn btn-primary ms-2">Ir al inicio</a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>

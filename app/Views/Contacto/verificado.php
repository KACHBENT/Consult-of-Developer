<?= $this->extend('layout/main') ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5" style="max-width: 900px;">
  <div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
      <?php $ok = $ok ?? false; ?>
      <div class="alert <?= $ok ? 'alert-success' : 'alert-danger' ?> mb-3">
        <?= esc($msg ?? 'Resultado') ?>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('/') ?>" class="btn btn-primary">Ir al inicio</a>
        <a href="<?= base_url('contacto') ?>" class="btn btn-outline-secondary">Volver a contacto</a>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>

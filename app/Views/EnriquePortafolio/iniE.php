<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>

<link rel="stylesheet" href="<?= base_url('css/inicio.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1>HOLAAA</h1>



<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts propios opcionales -->
<?= $this->endSection() ?>

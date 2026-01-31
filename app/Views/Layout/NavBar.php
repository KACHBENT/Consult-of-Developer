<?php
$session = session();
$user = $session->get('usuario') ?? [];
$rol = $user['rol'] ?? 'CLIENTE';
?>

<?= $this->section('navbar') ?>

<!-- Navbar superior -->
<nav class="navbar bg-custom px-3 sticky-top">
  <button class="btn btn-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
    <img src="<?= base_url('images/icons/menu.svg') ?>" width="20">
  </button>

  <a class="navbar-brand text-white fw-semibold text-decoration-none" href="<?= site_url('/') ?>">
    TechNova Consulting
  </a>
</nav>

<!-- Sidebar -->
<nav class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebar">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title">Menú</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column">

    <!-- Perfil -->
    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded bg-black bg-opacity-25">
      <i class="bi bi-person-circle fs-3"></i>
      <div>
        <div class="fw-semibold"><?= esc($user['nombre'] ?? 'Invitado') ?></div>
        <small class="text-white-50"><?= esc($user['email'] ?? '') ?></small>
      </div>
    </div>

    <nav class="nav flex-column gap-1 flex-grow-1">

      <!-- INICIO -->
      <a class="nav-link text-white d-flex align-items-center gap-2" href="<?= base_url('/') ?>">
        <i class="bi bi-house"></i> Inicio
      </a>

      <!-- CLIENTE -->
      <?php if ($rol === 'CLIENTE'): ?>
        <a class="nav-link text-white" href="<?= base_url('servicios') ?>">
          <i class="bi bi-briefcase"></i> Servicios
        </a>

        <a class="nav-link text-white" href="<?= base_url('equipo') ?>">
          <i class="bi bi-people"></i> Nuestro equipo
        </a>

        <a class="nav-link text-white" href="<?= base_url('portafolio') ?>">
          <i class="bi bi-folder"></i> Portafolio
        </a>

        <a class="nav-link text-white" href="<?= base_url('contacto') ?>">
          <i class="bi bi-envelope"></i> Contacto
        </a>
      <?php endif; ?>

      <!-- ADMIN -->
      <?php if ($rol === 'ADMIN'): ?>
        <hr class="border-secondary">

        <div>
          <button class="btn btn-toggle w-100 text-start text-white d-flex align-items-center gap-2"
            data-bs-toggle="collapse" data-bs-target="#adminMenu">
            <i class="bi bi-gear"></i> Administración
            <i class="bi bi-chevron-down ms-auto"></i>
          </button>

          <div class="collapse ps-3 mt-1" id="adminMenu">
            <a class="nav-link text-white-50" href="<?= base_url('admin/servicios') ?>">Servicios</a>
            <a class="nav-link text-white-50" href="<?= base_url('admin/miembros') ?>">Miembros</a>
            <a class="nav-link text-white-50" href="<?= base_url('admin/usuarios') ?>">Usuarios</a>
            <a class="nav-link text-white-50" href="<?= base_url('admin/contacto') ?>">Mensajes</a>
            <a class="nav-link text-white-50" href="<?= base_url('admin/movimientos') ?>">Movimientos</a>
          </div>
        </div>
      <?php endif; ?>

      <hr class="border-secondary">

      <a class="nav-link text-danger" href="<?= site_url('acceso/logout') ?>">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>

    </nav>
  </div>
</nav>

<?= $this->endSection() ?>

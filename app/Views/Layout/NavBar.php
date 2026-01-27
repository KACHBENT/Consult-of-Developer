<?php
$session = session();
$user = $session->get('usuario') ?? [];
?>
<?= $this->section('navbar') ?>

<!-- Navbar superior -->
<nav class="navbar bg-custom px-3 sticky-top">
  <button class="btn btn-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
    aria-controls="sidebar">
    <img src="<?= base_url('images/icons/menu.svg') ?>" class="darken" alt="menu" width="20" height="20">
  </button>
  <a class="navbar-brand d-flex align-items-center gap-2 text-white fw-semibold text-decoration-none"
    href="<?= site_url('/') ?>" aria-label="Ir al inicio de USMAKAPA">
    <picture class="brand-logo d-inline-block rounded-2 overflow-hidden">
      <source srcset="<?= base_url('images/logo_USMAKAPA.webp') ?>" type="image/webp">
      <img src="<?= base_url('images/logo_USMAKAPA.png') ?>" width="36" height="36" loading="lazy" alt="USMAKAPA">
    </picture>

    <span class="d-flex flex-column lh-1">
      <span class="brand-title">USMAKAPA</span>
    </span>
  </a>
</nav>

<!-- Sidebar lateral -->
<nav class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="sidebarLabel">Menú principal</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column">
    <!-- Perfil del usuario -->
    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded bg-black bg-opacity-25">
      <i class="bi bi-person-circle fs-3"></i>
      <div>
        <div class="fw-semibold"><?= esc($user['usuario_Nombre'] ?? 'Invitado') ?></div>
        <small class="text-white-50"><?= esc($user['persona_Correo'] ?? 'sin correo') ?></small>
      </div>
    </div>  

    <!-- Navegación -->
    <nav class="nav flex-column gap-1 flex-grow-1">
      <a class="nav-link text-white d-flex align-items-center gap-2 active" href="<?= base_url('/') ?>" >
        <img src="<?= base_url('images/icons/home.svg') ?>" class="white" alt="inicio" width="30" height="30"> Inicio
      </a>
    
      <div>
        <button class="btn btn-toggle align-items-center rounded text-start w-100 text-white d-flex gap-2"
          data-bs-toggle="collapse" data-bs-target="#submenu1" aria-expanded="false">
           <img src="<?= base_url('images/icons/supervised_user.svg') ?>" class="white" alt="inicio" width="30" height="30"> Módulos
          <i class="bi bi-chevron-down ms-auto"></i>
        </button>
        <div class="collapse ps-4 mt-1" id="submenu1">
          <a  class="nav-link text-white-50" href="<?= base_url('acceso/access-assistance') ?>"> Tomar asistencia</a>
          <a class="nav-link text-white-50" href=" <?= base_url('asistencia/historial') ?>">Reportes</a>
        </div>
      </div>

      <a class="nav-link text-white d-flex align-items-center gap-2" href="<?= base_url('settings-faces') ?>">
       <img src="<?= base_url('images/icons/settings.svg') ?>" class="white" alt="inicio" width="30" height="30"> Configuración facial
      </a>

      <hr class="border-secondary my-3">

      <a class="nav-link text-danger d-flex align-items-center gap-2" href="<?= site_url('acceso/logout') ?>">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>
    </nav>
  </div>
</nav>

<?= $this->endSection() ?>
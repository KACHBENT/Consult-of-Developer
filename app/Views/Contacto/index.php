<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('css/inicio.styles.css') ?>">
<style>
  :root{
    --radius: 22px;
    --soft: rgba(0,0,0,.08);
    --shadow: 0 18px 45px rgba(0,0,0,.16);
    --glass: rgba(255,255,255,.14);
    --glassLine: rgba(255,255,255,.20);
  }

  /* Contenedor tipo glass */
  .ct-wrap{
    max-width: 1050px;
    margin: 3rem auto;
  }

  .ct-card{
    border-radius: var(--radius);
    background: linear-gradient(180deg, rgba(255,255,255,.16), rgba(255,255,255,.10));
    border: 1px solid var(--glassLine);
    box-shadow: var(--shadow);
    overflow: hidden;
    backdrop-filter: blur(10px);
  }

  .ct-head{
    padding: 22px 22px 10px;
    color: rgba(255,255,255,.92);
    background: linear-gradient(135deg, rgba(0,0,0,.35), rgba(0,0,0,.08));
    border-bottom: 1px solid rgba(255,255,255,.18);
  }

  .ct-sub{
    color: rgba(255,255,255,.72);
  }

  .ct-body{
    padding: 18px 22px 22px;
    background: rgba(255,255,255,.92);
  }

  /* Secciones */
  .ct-section-title{
    font-weight: 900;
    margin: 0 0 .5rem;
  }

  /* Tarjetas de integrantes */
  .team-grid{ margin-top: .5rem; }

  .team-radio{
    position: absolute;
    opacity: 0;
    pointer-events: none;
  }

  .team-card{
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,.08);
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
    transition: .15s ease;
    cursor: pointer;
    background: #fff;
    overflow: hidden;
  }

  .team-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(0,0,0,.09);
  }

  .team-card .card-body{
    padding: 14px;
  }

  .team-avatar{
    width: 54px; height: 54px;
    object-fit: cover;
    border-radius: 999px;
    border: 2px solid rgba(0,0,0,.10);
    background: rgba(0,0,0,.03);
  }

  /* Estado seleccionado (sin JS) usando :has */
  label.team-card:has(input.team-radio:checked){
    border-color: rgba(13,110,253,.45);
    box-shadow: 0 18px 40px rgba(13,110,253,.18);
    outline: 3px solid rgba(13,110,253,.15);
  }

  .team-pill{
    display:inline-flex;
    align-items:center;
    gap:.35rem;
    padding:.25rem .6rem;
    border-radius: 999px;
    border: 1px solid rgba(0,0,0,.08);
    background: rgba(0,0,0,.03);
    font-weight: 800;
    font-size: .8rem;
    white-space: nowrap;
    color: black;
  }

  .ct-hint{
    border-radius: 14px;
    border: 1px dashed rgba(0,0,0,.18);
    background: rgba(0,0,0,.02);
    padding: 10px 12px;
    font-size: .92rem;
  }

  /* Inputs más bonitos */
  .form-control, .form-select{
    border-radius: 14px;
    border-color: rgba(0,0,0,.10);
  }
  .form-control:focus, .form-select:focus{
    border-color: rgba(13,110,253,.35);
    box-shadow: 0 0 0 .2rem rgba(13,110,253,.12);
  }

  /* Footer del form */
  .ct-actions{
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0,0,0,.06);
  }

  .btn-pill{
    border-radius: 999px;
    font-weight: 900;
    padding: .6rem 1rem;
  }

  .ct-note{
    color: rgba(0,0,0,.62);
    font-size: .92rem;
  }

  @media (max-width: 576px){
    .ct-body{ padding: 16px; }
    .ct-head{ padding: 18px 16px 10px; }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
  $errors = session('errors') ?? [];
  $sessionUser = session()->get('usuario') ?? [];
  $prefNombre = old('nombre') ?: ($sessionUser['nombre'] ?? '');
  $prefEmail  = old('email')  ?: ($sessionUser['email'] ?? '');
?>

<div class="container ct-wrap">
  <div class="ct-card">

    <div class="ct-head">
      <h2 class="fw-bold m-0">Contacto</h2>
      <div class="ct-sub">Elige un integrante y envía tu mensaje. Primero confirmaremos tu correo</div>
    </div>

    <div class="ct-body">

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger mb-3"><?= esc($errors['general']) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('contacto/enviar') ?>">
        <?= csrf_field() ?>

        <!-- Selección de miembro -->
        <div class="mb-4">
          <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <h5 class="ct-section-title text-black"  >¿A quién deseas contactar?</h5>
            <?php if (!empty($errors['miembroId'])): ?>
              <span class="text-danger fw-semibold"><?= esc($errors['miembroId']) ?></span>
            <?php else: ?>
              <span class="team-pill">👥 Equipo TechNova</span>
            <?php endif; ?>
          </div>

          <div class="row g-3 team-grid">
            <?php foreach (($miembros ?? []) as $m): ?>
              <?php $selected = old('miembroId') === $m['id']; ?>
              <div class="col-12 col-md-4">
                <label class="card team-card h-100 position-relative">
                  <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                      <img class="team-avatar"
                           src="<?= base_url($m['foto']) ?>"
                           alt="<?= esc($m['nombre']) ?>"
                           onerror="this.style.display='none'">

                      <div class="flex-grow-1">
                        <div class="fw-bold"><?= esc($m['nombre']) ?></div>
                        <div class="text-muted small"><?= esc($m['rol']) ?></div>
                        <div class="text-muted small"><?= esc($m['email']) ?></div>
                      </div>

                      <!-- radio oculto, el label completo es clickeable -->
                      <input class="team-radio"
                             type="radio"
                             name="miembroId"
                             value="<?= esc($m['id']) ?>"
                             <?= $selected ? 'checked' : '' ?>>

                      <span class="team-pill">
                        <?= $selected ? ' Seleccionado' : 'Elegir' ?>
                      </span>
                    </div>
                  </div>
                </label>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="ct-hint mt-3 text-black">
             Te enviaremos un correo de verificación. Una vez confirmado, tu mensaje se envía al integrante seleccionado.
          </div>
        </div>

        <!-- Datos del remitente -->
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label text-black">Tu nombre</label>
            <input class="form-control" name="nombre" value="<?= esc($prefNombre) ?>" placeholder="Ej. Juan Pérez">
            <?php if (!empty($errors['nombre'])): ?><small class="text-danger"><?= esc($errors['nombre']) ?></small><?php endif; ?>
          </div>

          <div class="col-md-6">
            <label class="form-label text-black ">Tu correo</label>
            <input class="form-control" name="email" value="<?= esc($prefEmail) ?>" placeholder="Ej. correo@dominio.com">
            <?php if (!empty($errors['email'])): ?><small class="text-danger"><?= esc($errors['email']) ?></small><?php endif; ?>
          </div>

          <div class="col-12">
            <label class="form-label text-black">Asunto</label>
            <input class="form-control" name="asunto" value="<?= old('asunto') ?>" placeholder="Ej. Cotización / Soporte / Consulta">
            <?php if (!empty($errors['asunto'])): ?><small class="text-danger"><?= esc($errors['asunto']) ?></small><?php endif; ?>
          </div>

          <div class="col-12">
            <label class="form-label text-black">Mensaje</label>
            <textarea class="form-control" rows="5" name="mensaje" placeholder="Escribe tu mensaje..."><?= old('mensaje') ?></textarea>
            <?php if (!empty($errors['mensaje'])): ?><small class="text-danger"><?= esc($errors['mensaje']) ?></small><?php endif; ?>
          </div>

          <div class="col-12 ct-actions d-flex align-items-center justify-content-between flex-wrap gap-2">
            <button class="btn btn-primary btn-pill">Enviar</button>
            <div class="ct-note">
               Tu correo será verificado antes de enviar el mensaje al integrante.
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>

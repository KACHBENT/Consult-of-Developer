<?= $this->extend('layout/main') ?> <!-- ➊ heredamos layout -->
<?= $this->section('css') ?> <!-- ➋ CSS sólo de esta página -->
<link rel="stylesheet" href="<?= base_url('css/login.styles.css') ?>">
<?= $this->endSection() ?>
<?php
// Obtener clave de pase de lista desde la sesión (flashdata)
$clavePaseLista = session('clave_pase_lista');
?>

<?= $this->section('content') ?>
<main id="content" class="container py-4">
    <section class="justify-content-center align-items-center mt-2 mb-2 d-flex flex-column">
        <div class="card mb-4 ">
            <div class="card-header linea-contenedor bg-white d-flex text-center gap-2">
                <img src="<?= base_url('images/icons/login.svg') ?>" class="icon-head darken" alt="Logo" class="logo"
                    loading="lazy">
                <h4 class="card-title title text-center"><span>Iniciar Sesión</span></h4>
            </div>
            <form id="formLogin" action="<?= site_url('acceso/login') ?>" method="POST" novalidate autocomplete="on">
                <?= csrf_field() ?>
                <div class="card-body bg-white">
                    <article class="align-items-center">
                        <div class="container-fluid mb-2">
                            <div class="container mb-lg-4">
                                <center>
                                    <img src="<?= base_url('images/icons/group-user.svg') ?>" alt="Logo"
                                        class="img-fluid darken" width="100">
                                </center>
                            </div>
                            <div class="input-group mb-2 mt-2">
                                <span class="input-group-text">
                                    <img src="<?= base_url('images/icons/account_box.svg') ?>" alt="username"
                                        class="icon-form darken" loading="lazy" />
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Ingresa el Usuario" value="<?= old('username') ?>" required />
                                    <label for="username">Usuario</label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><img src="<?= base_url('images/icons/password.svg') ?>"
                                        class="darken" alt="password" loading="lazy" /></span>
                                <input type="password" id="passInput" name="password" class="form-control"
                                    placeholder="Ingresa la Contraseña" required autocomplete="current-password" />

                                <button type="button" id="btnToggle"
                                    class="btn btn-outline-secondary custom-view border-lighter"
                                    aria-label="Mostrar u ocultar contraseña"
                                    data-icon-view="<?= base_url('images/icons/view.svg') ?>"
                                    data-icon-hide="<?= base_url('images/icons/visibility_off.svg') ?>">
                                    <img id="toggleIcon" src="<?= base_url('images/icons/visibility_off.svg') ?>"
                                        class="icon-form darken" alt="Ocultar" width="20" height="20">
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="card-footer bg-white gap-2 justify-content-center">
                    <button type="submit" class="btn btn-secondary w-100 p-sm-2">Iniciar Sesión</button>
                    <center class="bg-white">
                        <a class="reference-login" href="<?= base_url('acceso/register-person') ?>">¿Ya te registraste?
                            ¡Hazlo
                            ahora!</a>
                    </center>
                </div>
            </form>
        </div>
        <?php if (!empty($clavePaseLista)): ?>
            <div class="modal fade" id="modalClavePaseLista" tabindex="-1" aria-labelledby="modalClavePaseListaLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-gradient-menta linea-contenedor">
                    <h5 class="modal-title text-white" id="modalClavePaseListaLabel">
                      <i class="bi bi-key me-1"></i> Tu clave de pase de lista
                    </h5>
                    <button type="button" class="btn-close invert" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body">
                    <p class="mb-2 text-black">
                      Guarda esta clave, la necesitarás para usar el modo de <strong class="text-black">pase de lista por PIN</strong>.
                    </p>
                    <div class="input-group mb-2">
                      <span class="input-group-text">
                        <i class="bi bi-shield-lock"></i>
                      </span>
                      <input type="text" class="form-control fw-bold" id="clavePaseListaInput"
                        value="<?= esc($clavePaseLista) ?>" readonly>
                      <button type="button" class="btn btn-outline-secondary" id="btnCopiarClavePaseLista"
                        title="Copiar al portapapeles">
                        <img src="<?= base_url('images/icons/content_copy.svg') ?>" alt="username"
                                        class="icon-form darken" loading="lazy" />
                      </button>
                    </div>
                    <small class="text-muted">
                      Te recomendamos copiarla y guardarla en un lugar seguro.
                    </small>
                  </div>
                  </div>
                <?php endif; ?>
    </section>
</main>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<?php if (!empty($clavePaseLista)): ?>
<script src="<?= base_url('javascript/login.script.js') ?>"></script>
<?php endif; ?>
<script>
    (function () {
        const $ = (sel) => document.querySelector(sel);
        const pass = $("#passInput");
        const btn = $("#btnToggle");
        const icon = $("#toggleIcon");

        if (btn && pass && icon) {
            btn.addEventListener("click", () => {
                const showing = pass.type === "text";
                pass.type = showing ? "password" : "text";
                const viewIcon = btn.dataset.iconView;
                const hideIcon = btn.dataset.iconHide;
                icon.src = showing ? hideIcon : viewIcon;
                icon.alt = showing ? "Ocultar" : "Mostrar";
                btn.setAttribute("aria-pressed", String(!showing));
            });
        }
        const form = $("#formLogin");
        if (form) {
            form.addEventListener("submit", (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    form.classList.add("was-validated");
                }
            });
        }
    })();
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/main') ?> <!-- ➊ heredamos layout -->

<?= $this->section('css') ?> <!-- ➋ CSS sólo de esta página -->
<link rel="stylesheet" href="<?= base_url('css/registro_persona.styles.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?> <!-- ➌ HTML principal -->

<main id="content">
  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="card bg-white-custom p-4 shadow-sm rounded-4 border-transparent main-content"
            style="max-width:960px; margin:0 auto;   border-radius: calc(1.375rem - (1px)) calc(1.375rem - (1px)) 0 0 !important;">

            <div class="container mb-2 linea-contenedor">
              <div class="text-center">
                <h1 class="m-0">Registro de Usuario</h1>
              </div>
            </div>

            <form action="<?= site_url('acceso/register') ?>" method="post" novalidate autocomplete="on">
              <?= csrf_field() ?>

              <div class="row g-4 align-items-center">
                <!-- Columna izquierda -->
                <div class="col-md-6 col-12">

                  <!-- Usuario -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/account_circle.svg') ?>" alt="Usuario" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="usuario_Nombre" name="usuario_Nombre"
                        placeholder="Ingrese el nombre del usuario" value="<?= old('usuario_Nombre') ?>" required
                        autocomplete="username" />
                      <label for="usuario_Nombre">Usuario</label>
                    </div>
                  </div>

                  <!-- Contraseña -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/key.svg') ?>" alt="Contraseña" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="password" class="form-control" id="password" name="password"
                        placeholder="Ingrese su contraseña" required autocomplete="new-password" />
                      <label for="password">Contraseña</label>
                    </div>
                  </div>

                  <!-- Confirmar contraseña -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/key.svg') ?>" alt="Confirmación Contraseña"
                        class="content-image" loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                        placeholder="Confirma Contraseña" required autocomplete="new-password" />
                      <label for="password_confirm">Confirmar Contraseña</label>
                    </div>
                  </div>

                  <!-- Nombre -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/person.svg') ?>" alt="Nombre" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="persona_Nombre" name="persona_Nombre"
                        placeholder="Ingrese su nombre" value="<?= old('persona_Nombre') ?>" required
                        autocomplete="given-name" />
                      <label for="persona_Nombre">Nombre</label>
                    </div>
                  </div>

                </div>

                <!-- Columna derecha -->
                <div class="col-md-6 col-12">

                  <!-- Apellido Paterno -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/person.svg') ?>" alt="Apellido Paterno" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="persona_ApellidoPaterno"
                        name="persona_ApellidoPaterno" placeholder="Ingrese su Apellido Paterno"
                        value="<?= old('persona_ApellidoPaterno') ?>" required autocomplete="additional-name" />
                      <label for="persona_ApellidoPaterno">Apellido Paterno</label>
                    </div>
                  </div>

                  <!-- Apellido Materno (OPCIONAL) -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/person.svg') ?>" alt="Apellido Materno" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="persona_ApellidoMaterno"
                        name="persona_ApellidoMaterno" placeholder="Ingrese su Apellido Materno"
                        value="<?= old('persona_ApellidoMaterno') ?>" autocomplete="family-name" />
                      <label for="persona_ApellidoMaterno">Apellido Materno</label>
                    </div>
                  </div>

                  <!-- CURP -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/contact_page.svg') ?>" alt="CURP" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="persona_Curp" name="persona_Curp"
                        placeholder="Ingrese su CURP" value="<?= old('persona_Curp') ?>" required maxlength="18"
                        style="text-transform:uppercase" />
                      <label for="persona_Curp">CURP</label>
                    </div>
                  </div>

                  <!-- RFC -->
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/contact_page.svg') ?>" alt="RFC" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="persona_Rfc" name="persona_Rfc"
                        placeholder="Ingrese su RFC" value="<?= old('persona_Rfc') ?>" required maxlength="13"
                        style="text-transform:uppercase" />
                      <label for="persona_Rfc">RFC</label>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Fecha de nacimiento -->
              <div class="input-group mb-3 mt-2">
                <span class="input-group-text">
                  <img src="<?= base_url('images/icons/perm_contact_calendar.svg') ?>" alt="Fecha de nacimiento"
                    class="content-image" loading="lazy" />
                </span>
                <div class="form-floating flex-grow-1">
                  <input type="date" class="form-control" id="persona_FechaNacimiento" name="persona_FechaNacimiento"
                    placeholder="Fecha de nacimiento" value="<?= old('persona_FechaNacimiento') ?>" required />
                  <label for="persona_FechaNacimiento">Fecha de nacimiento</label>
                </div>
              </div>

              <!-- Correo y Teléfono -->
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/contact_mail.svg') ?>" alt="Correo" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="email" class="form-control" id="persona_Correo" name="persona_Correo"
                        placeholder="Ingrese su Correo" value="<?= old('persona_Correo') ?>" required
                        autocomplete="email" />
                      <label for="persona_Correo">Correo</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <img src="<?= base_url('images/icons/contact_phone.svg') ?>" alt="Teléfono" class="content-image"
                        loading="lazy" />
                    </span>
                    <div class="form-floating flex-grow-1">
                      <input type="text" class="form-control" id="persona_telefono" name="persona_telefono"
                        placeholder="Ingrese su Teléfono" value="<?= old('persona_telefono') ?>" required
                        inputmode="tel" autocomplete="tel" />
                      <label for="persona_telefono">Teléfono</label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Botón & link -->
              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">
                  Registrar Usuario
                </button>
              </div>
              <div class="text-center pt-2">
                <a href="<?= base_url('auth/register') ?>" class="reference-login">¿Ya estás registrado? Inicia
                  Sesión</a>
              </div>
            </form>
          </div>
          
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->endSection() ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('css/inicio.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="portfolio-container">

  <!-- COLUMNA IZQUIERDA -->
  <aside class="left-column">
    <img src="<?= base_url('img/abraham.jpg') ?>" class="profile-img">

    <p class="about">
      Soy una persona responsable y comprometida con el aprendizaje continuo y el trabajo en equipo.
      Busco oportunidades para desarrollar y aplicar mis conocimientos en el área de sistemas
      computacionales y tecnologías de la información.
    </p>

    <section class="section">
      <h3>Contacto</h3>
      <p><b>Celular:</b> -----------</p>
      <p><b>Correo:</b> -----------</p>
      <p><b>Dirección:</b> Estado de México</p>
    </section>

    <section class="section">
      <h3>Educación</h3>
      <p>
        <b>Ingeniería en Sistemas Computacionales</b><br>
        Universidad Bancaria de México<br>
        <span>En curso</span>
      </p>

      <p>
        <b>Bachillerato</b><br>
        Universidad Bancaria de México
      </p>
    </section>
  </aside>

  <!-- COLUMNA DERECHA -->
  <main class="right-column">

    <h1>Abraham Enrique Tetlatmatzi Pérez</h1>
    <h2>Ing. en Sistemas Computacionales</h2>

    <section class="section">
      <h3>Perfil Profesional</h3>
      <p>
        Estudiante de Ingeniería en Sistemas Computacionales con interés en el desarrollo de software,
        bases de datos y tecnologías web. Con disposición para aprender nuevas herramientas y
        adaptarse a diferentes entornos tecnológicos.
      </p>
    </section>

    <section class="section">
      <h3>Conocimientos</h3>

      <p><b>Programación</b></p>
      <ul>
        <li>Python – Básico</li>
        <li>Java – Básico</li>
        <li>JavaScript – Básico</li>
      </ul>

      <p><b>Bases de Datos</b></p>
      <ul>
        <li>MySQL – Básico</li>
      </ul>

      <p><b>Sistemas Operativos</b></p>
      <ul>
        <li>Windows / Linux – Básico</li>
      </ul>
    </section>

    <section class="section">
      <h3>Cursos</h3>
      <ul>
        <li>SCRUM – En proceso</li>
        <li>Fundamentos de Programación</li>
      </ul>
    </section>

  </main>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts propios opcionales -->
<?= $this->endSection() ?>

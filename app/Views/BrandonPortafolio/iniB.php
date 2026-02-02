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
    <img src="<?= base_url('images/team/brandon.jpg') ?>" class="profile-img">

    <p class="about">
      Soy una persona comprometida con el desarrollo y el desempeño en el entorno laboral.
      Estoy en búsqueda de oportunidades que me permitan aplicar y expandir mis conocimientos
      en ingeniería de sistemas computacionales.
    </p>

    <section class="section">
      <h3>Contacto</h3>
      <p><b>Celular:</b> 5538752646</p>
      <p><b>Correo:</b> brandonjaviervillegas@gmail.com</p>
      <p><b>Dirección:</b> Tepotzotlán, Edo. Méx.</p>
    </section>

    <section class="section">
      <h3>Educación</h3>
      <p><b>Ing. en Sistemas Computacionales</b><br>Universidad Bancaria de México (En curso)</p>
      <p><b>Técnico en Programación</b><br>2022 - Alumno Destacado</p>
    </section>
  </aside>

  <!-- COLUMNA DERECHA -->
  <main class="right-column">

    <h1>Brandon Javier Villegas Martínez</h1>
    <h2>Ing. en Sistemas Computacionales</h2>

    <section class="section">
      <h3>Experiencia Laboral</h3>
      <p><b>Asistente de Servicio al Cliente</b></p>
      <p>Centro Recreativo Los Arcos (2020 - Presente)</p>
      <ul>
        <li>Gestión del equipo de trabajo</li>
        <li>Control de inventarios</li>
        <li>Atención al cliente</li>
      </ul>
    </section>

    <section class="section">
      <h3>Conocimientos</h3>

      <p><b>Programación</b></p>
      <ul>
        <li>Python – Intermedio</li>
        <li>JavaScript – Básico</li>
        <li>Java / C++ / C# – Básico</li>
      </ul>

      <p><b>Bases de datos</b></p>
      <ul>
        <li>MySQL – Intermedio</li>
        <li>Oracle – Intermedio</li>
        <li>SQL Server – Básico</li>
      </ul>

      <p><b>Sistemas Operativos</b></p>
      <ul>
        <li>Windows / Linux – Intermedio</li>
      </ul>
    </section>

    <section class="section">
      <h3>Cursos</h3>
      <ul>
        <li>SCRUM – 2024</li>
        <li>Excel – En curso</li>
        <li>JavaScript – En curso</li>
      </ul>
    </section>

  </main>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>

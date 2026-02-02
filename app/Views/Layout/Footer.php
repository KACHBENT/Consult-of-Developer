<?= $this->section('footer') ?>

<footer class="footer bg-dark text-light mt-auto">
  <div class="container py-4">

    <div class="row g-3">
      
      <div class="col-md-4">
        <h5 class="fw-bold">TechNova Consulting</h5>
        <p class="small text-white-50">
          Consultora especializada en soluciones tecnológicas, desarrollo de software
          y asesoría empresarial.
        </p>
      </div>

      
      <div class="col-md-4">
        <h6 class="fw-semibold">Contacto</h6>
        <ul class="list-unstyled small text-white-50">
          <li><i class="bi bi-envelope"></i> consultingtechnova4@gmail.com</li>
          <li><i class="bi bi-telephone"></i> 55 1234 5678</li>
          <li><i class="bi bi-geo-alt"></i> Ciudad de México</li>
        </ul>
      </div>

    
      <div class="col-md-4">
        <h6 class="fw-semibold">Legal</h6>
        <a href="<?= base_url('aviso-privacidad') ?>" class="text-white-50 d-block small">Aviso de privacidad</a>
        <a href="<?= base_url('terminos') ?>" class="text-white-50 d-block small">Términos y condiciones</a>
      </div>
    </div>

    <hr class="border-secondary my-3">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
      <span>© <?= date('Y') ?> TechNova Consulting. Todos los derechos reservados.</span>
      <span>Desarrollado por el Departamento de Sistemas</span>
    </div>

  </div>
</footer>

<?= $this->endSection() ?>

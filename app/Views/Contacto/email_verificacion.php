<div style="font-family:Arial,sans-serif;line-height:1.6;color:#111">
  <h2 style="margin:0 0 10px;">Hola <?= esc($nombre) ?> 👋</h2>

  <p style="margin:0 0 12px;">
    Para poder enviar tu mensaje a <b><?= esc($miembro['nombre']) ?></b> (<?= esc($miembro['rol']) ?>),
    necesitamos confirmar que este correo es tuyo.
  </p>

  <p style="margin:18px 0;">
    <a href="<?= esc($verifyUrl) ?>"
       style="display:inline-block;background:#0d6efd;color:#fff;text-decoration:none;padding:12px 16px;border-radius:10px;font-weight:bold;">
      Confirmar correo
    </a>
  </p>

  <p style="color:#666;margin:18px 0 0;">
    Si tú no solicitaste esto, ignora este correo.
  </p>
</div>

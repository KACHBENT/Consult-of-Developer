<div style="font-family:Arial,sans-serif;line-height:1.6;color:#111">
  <h2 style="margin:0 0 10px;">Nuevo mensaje de contacto ✅</h2>

  <p style="margin:0 0 12px;">
    <b>Remitente:</b> <?= esc($contacto['nombre']) ?> (<?= esc($contacto['email']) ?>)
  </p>

  <p style="margin:0 0 12px;">
    <b>Asunto:</b> <?= esc($contacto['asunto']) ?>
  </p>

  <div style="border:1px solid #e9ecef;border-radius:12px;padding:12px;background:#fafafa;">
    <?= nl2br(esc($contacto['mensaje'])) ?>
  </div>

  <p style="color:#666;margin:16px 0 0;font-size:12px;">
    Nota: este correo incluye <b>Reply-To</b> al remitente, puedes responder directamente.
  </p>
</div>

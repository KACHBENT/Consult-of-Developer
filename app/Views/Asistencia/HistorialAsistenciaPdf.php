<?php
// Helper local para minutos -> HH:MM
if (!function_exists('formatMinutesHHMM')) {
    function formatMinutesHHMM(int $minutes): string {
        if ($minutes <= 0) return '00:00';
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }
}

$fechaLabel = date('d/m/Y', strtotime($fecha));

// --- LOGO EMBEBIDO EN BASE64 ---
// Ruta física: public/images/logo_USMAKAPA.png
$logoPath    = FCPATH . 'images/logo_USMAKAPA.png';
$logoDataUri = '';

if (is_file($logoPath)) {
    $type  = pathinfo($logoPath, PATHINFO_EXTENSION); // png / jpg
    $data  = @file_get_contents($logoPath);
    if ($data !== false) {
        $base64      = base64_encode($data);
        $mime        = ($type === 'jpg' || $type === 'jpeg') ? 'image/jpeg' : 'image/png';
        $logoDataUri = 'data:' . $mime . ';base64,' . $base64;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de asistencia <?= htmlspecialchars($fechaLabel) ?></title>
  <style>
    * { box-sizing:border-box; }
    body {
      font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
      font-size:11px;
      color:#111827;
      margin:20px;
    }
    h1,h2,h3,h4 { margin:0; padding:0; }

    .header {
      display:flex;
      align-items:center;
      justify-content:space-between;
      border-bottom:2px solid #16a34a;
      padding-bottom:8px;
      margin-bottom:12px;
    }
    .header-left {
      display:flex;
      align-items:center;
      gap:10px;
    }
    .logo-box {
      width:60px;
      height:60px;
      border-radius:12px;
      background:#ecfdf3;
      border:1px solid #bbf7d0;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .logo-box img {
      display:block;
      max-width:50px;
      max-height:50px;
    }
    .company-name {
      font-size:16px;
      font-weight:700;
      color:#065f46;
      line-height:1.2;
    }
    .company-sub {
      font-size:9px;
      color:#6b7280;
    }
    .header-right {
      text-align:right;
      font-size:9px;
      color:#6b7280;
    }
    .badge-date {
      display:inline-block;
      padding:3px 7px;
      border-radius:999px;
      background:#ecfdf3;
      border:1px solid #bbf7d0;
      color:#166534;
      font-size:9px;
      margin-top:2px;
    }

    .section { margin-bottom:12px; }
    .section-title {
      font-size:12px;
      font-weight:700;
      margin-bottom:4px;
      color:#111827;
    }

    .summary-table {
      width:100%;
      border-collapse:collapse;
      margin-top:2px;
      font-size:10px;
    }
    .summary-table th,
    .summary-table td {
      padding:5px 6px;
      border:1px solid #e5e7eb;
    }
    .summary-table th {
      background:#f3f4f6;
      text-align:left;
      font-weight:600;
    }

    .tag {
      display:inline-block;
      padding:2px 6px;
      border-radius:999px;
      font-size:9px;
      font-weight:600;
    }
    .tag-work {
      background:#ecfdf3;
      color:#166534;
      border:1px solid #bbf7d0;
    }
    .tag-break {
      background:#fff7ed;
      color:#9a3412;
      border:1px solid #fed7aa;
    }

    .events-table {
      width:100%;
      border-collapse:collapse;
      margin-top:4px;
      font-size:9.5px;
    }
    .events-table th,
    .events-table td {
      padding:5px 6px;
      border:1px solid #e5e7eb;
    }
    .events-table thead th {
      background:#e5f9f0;
      color:#065f46;
      text-align:left;
      font-size:9.5px;
    }
    .events-table tbody tr:nth-child(odd) td {
      background:#f9fafb;
    }
    .events-table tbody tr:nth-child(even) td {
      background:#ffffff;
    }

    .text-center { text-align:center; }
    .text-muted  { color:#6b7280; }

    .footnote {
      margin-top:12px;
      font-size:8.5px;
      color:#6b7280;
      border-top:1px dashed #e5e7eb;
      padding-top:6px;
    }
    .legend {
      margin-top:6px;
      font-size:8.5px;
      color:#6b7280;
    }
    .legend span { margin-right:10px; }
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <div class="header">
    <div class="header-left">
      <div class="logo-box">
        <?php if ($logoDataUri): ?>
          <img style="width:42px; height: 42px;" src="<?= $logoDataUri ?>" alt="Logo empresa">
        <?php else: ?>
          <span style="font-size:18px;color:#16a34a;">🏢</span>
        <?php endif; ?>
      </div>
      <div>
        <div class="company-name">
          <?= htmlspecialchars($nombreEmpresa ?? 'USMAKAPA S.A. de C.V.') ?>
        </div>
        <div class="company-sub">
          Reporte de asistencia con validación facial
        </div>
      </div>
    </div>

    <div class="header-right">
      <div>Fecha del reporte: <?= $fechaLabel ?></div>
      <?php if (!empty($nombrePersona)): ?>
        <div>Empleado: <strong><?= htmlspecialchars($nombrePersona) ?></strong></div>
      <?php endif; ?>
      <div class="badge-date">
        Día consultado: <?= $fechaLabel ?>
      </div>
    </div>
  </div>

  <!-- RESUMEN -->
  <div class="section">
    <div class="section-title">Resumen del día</div>
    <table class="summary-table">
      <tr>
        <th style="width:40%;">Empleado</th>
        <th style="width:20%;">Fecha</th>
        <th style="width:20%;">Tiempo trabajado</th>
        <th style="width:20%;">Tiempo en comida</th>
      </tr>
      <tr>
        <td><?= !empty($nombrePersona) ? htmlspecialchars($nombrePersona) : '—' ?></td>
        <td><?= $fechaLabel ?></td>
        <td><?= formatMinutesHHMM((int) ($totalTrabajoMin ?? 0)) ?> h</td>
        <td><?= formatMinutesHHMM((int) ($totalComidaMin ?? 0)) ?> h</td>
      </tr>
    </table>
  </div>

  <!-- DETALLE DE EVENTOS -->
  <div class="section">
    <div class="section-title">Detalle de eventos</div>

    <?php if (empty($eventos)): ?>
      <p class="text-muted" style="margin-top:4px;">
        No se encontraron registros de asistencia para la fecha seleccionada.
      </p>
    <?php else: ?>
      <table class="events-table">
        <thead>
          <tr>
            <th style="width:15%;">Estado</th>
            <th style="width:18%;">Tipo</th>
            <th style="width:14%;">Inicio</th>
            <th style="width:14%;">Fin</th>
            <th style="width:13%;" class="text-center">Duración (min)</th>
            <th style="width:13%;" class="text-center">Duración HH:MM</th>
            <th style="width:13%;" class="text-center">Validación facial</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($eventos as $ev): ?>
          <?php
            $isBreak   = ($ev['tipo'] === 'comida');
            $tagClass  = $isBreak ? 'tag-break' : 'tag-work';
            $tipoLabel = $isBreak ? 'Pausa de comida' : 'Jornada laboral';
            $horaIni   = $ev['inicio'] ? date('H:i', strtotime($ev['inicio'])) : '';
            $horaFin   = $ev['fin'] ? date('H:i', strtotime($ev['fin'])) : 'En curso';
          ?>
          <tr>
            <td><span class="<?= $tagClass ?>"><?= htmlspecialchars($ev['estado']) ?></span></td>
            <td><?= $tipoLabel ?></td>
            <td><?= $horaIni ?></td>
            <td><?= $horaFin ?></td>
            <td class="text-center"><?= (int) $ev['duracion_min'] ?></td>
            <td class="text-center"><?= formatMinutesHHMM((int) $ev['duracion_min']) ?></td>
            <td class="text-center"><?= htmlspecialchars($ev['validacion']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <div class="legend">
        <span><span class="tag tag-work">Entrada / Salida</span> = eventos de trabajo</span>
        <span><span class="tag tag-break">Comida</span> = pausas de comida</span>
      </div>
    <?php endif; ?>
  </div>

  <div class="footnote">
    Reporte generado automáticamente por el sistema de control de asistencia.
    Este documento es sólo de carácter informativo.
  </div>

</body>
</html>

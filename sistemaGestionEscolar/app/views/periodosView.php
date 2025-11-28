<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/periodoModel.php';

$model = new PeriodoModel($connection);

// Capturar mensajes
$mensaje = $_GET['msg'] ?? '';
$tipo = $_GET['type'] ?? '';

// Editar
$modo = isset($_GET['edit']) ? 'editar' : 'insertar';
$periodoEditar = null;

if ($modo === 'editar') {
  $periodoEditar = $model->obtenerPorId($_GET['edit']);
}

$periodos = $model->obtenerPeriodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Periodos Escolares</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* COLORES INSTITUCIONALES */
    .navbar, footer {
      background-color: #0A2A43 !important;
    }

    footer {
      color: white;
      padding: 12px 0;
      margin-top: auto;
      text-align: center;
      font-weight: 500;
      font-size: 15px;
    }

    .btn-institucional {
      background-color: #0A2A43 !important;
      color: white !important;
      border: 1px solid #0A2A43 !important;
      border-radius: 8px;
    }

    .btn-institucional:hover {
      background-color: #09324f !important;
      color: white !important;
    }

    .card-header {
      background-color: #5a5a5a;
      color: white;
    }

    .badge-activo { background-color: #28a745; }
    .badge-cerrado { background-color: #6c757d; }
    .badge-proximo { background-color: #ffc107; color: #000; }

    .titulo-pagina {
        color: #0A2A43;
        font-weight: 700;
    }

    .btn-regresar {
        background: #0A2A43;
        color: #fff;
        font-weight: 500;
        border-radius: 6px;
    }
    .btn-regresar:hover {
        background: #09324f;
        color: #fff;
    }

  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>
  </div>
</nav>

<!-- CONTENIDO -->
<div class="container mt-4 mb-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="titulo-pagina m-0">
        <i class="bi bi-calendar-week-fill me-2"></i> Gestión de Periodos Escolares
    </h3>

    <a href="<?= BASE_URL ?>index.php?action=dashboard"
       class="btn btn-regresar px-4">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


  <!-- FORMULARIO -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold"><?= $modo == 'editar' ? 'Editar Periodo' : 'Registrar Nuevo Periodo' ?></div>
    <div class="card-body">

      <form method="POST" action="../controllers/periodoController.php" class="row g-3">

        <?php if ($modo === 'editar' && $periodoEditar): ?>
          <input type="hidden" name="idPeriodo" value="<?= $periodoEditar['idPeriodo'] ?>">
        <?php endif; ?>

        <div class="col-md-4">
          <label class="form-label">Nombre del Periodo</label>
          <input type="text" name="nombrePeriodo" class="form-control"
                 value="<?= $periodoEditar['nombrePeriodo'] ?? '' ?>"
                 placeholder="Ej. Septiembre–Diciembre" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Fecha de Inicio</label>
          <input type="date" name="fechaInicio" class="form-control"
                 value="<?= $periodoEditar['fechaInicio'] ?? '' ?>" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Fecha de Fin</label>
          <input type="date" name="fechaFin" class="form-control"
                 value="<?= $periodoEditar['fechaFin'] ?? '' ?>" required>
        </div>

        <div class="col-md-2 text-end">
          <?php if ($modo === 'editar'): ?>
            <button type="submit" name="actualizar" class="btn btn-warning mt-4 px-4">Actualizar</button>
            <a href="periodosView.php" class="btn btn-secondary mt-4 px-4">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="insertar" class="btn btn-success mt-4 px-4">Guardar</button>
          <?php endif; ?>
        </div>
      </form>

    </div>
  </div>

  <!-- TABLA -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Periodos Registrados</div>

    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
        <?php while ($p = $periodos->fetch_assoc()): ?>

          <?php
            $hoy = date('Y-m-d');
            if ($hoy < $p['fechaInicio']) {
              $estado = 'Próximo';
              $badge = 'badge-proximo';
            } elseif ($hoy > $p['fechaFin']) {
              $estado = 'Cerrado';
              $badge = 'badge-cerrado';
            } else {
              $estado = 'Activo';
              $badge = 'badge-activo';
            }
          ?>

          <tr>
            <td><?= $p['idPeriodo'] ?></td>
            <td><?= htmlspecialchars($p['nombrePeriodo']) ?></td>
            <td><?= $p['fechaInicio'] ?></td>
            <td><?= $p['fechaFin'] ?></td>
            <td><span class="badge <?= $badge ?>"><?= $estado ?></span></td>

            <td>
              <a href="periodosView.php?edit=<?= $p['idPeriodo'] ?>" class="btn btn-warning btn-sm">Editar</a>

              <a href="../controllers/periodoController.php?delete=<?= $p['idPeriodo'] ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('¿Seguro que deseas eliminar el periodo <?= htmlspecialchars($p['nombrePeriodo']) ?>?');">
                 Eliminar
              </a>
            </td>
          </tr>

        <?php endwhile; ?>
        </tbody>

      </table>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer>
  © 2025 Sistema de Gestión Escolar
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

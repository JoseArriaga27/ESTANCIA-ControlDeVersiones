<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/materiaModel.php';

session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'Sin rol';

$model = new MateriaModel($connection);

$mensaje = $_GET['msg'] ?? '';
$tipo = $_GET['type'] ?? '';
$modo = isset($_GET['edit']) ? 'editar' : 'insertar';
$materiaEditar = ($modo == 'editar') ? $model->obtenerPorId($_GET['edit']) : null;

$materias = $model->obtenerMaterias();
$periodos = $model->obtenerPeriodosActivos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Materias</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* NAVBAR Y FOOTER */
    .navbar, footer {
      background-color: #0A2A43 !important;
    }

    footer {
      color: white;
      padding: 12px 0;
      text-align: center;
      margin-top: auto;
      font-size: 15px;
      font-weight: 500;
    }

    /* BOTÓN INSTITUCIONAL */
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

    /* CARD HEADER */
    .card-header {
      background-color: #5a5a5a;
      color: white;
    }

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

    <div class="d-flex align-items-center">
      <div class="text-white fw-semibold me-3">
        <i class="bi bi-person-circle"></i>
        <?= htmlspecialchars($nombreUsuario) ?>
        <span class="text-white-50">(<?= htmlspecialchars($rolUsuario) ?>)</span>
      </div>

      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<!-- CONTENIDO -->
<div class="container mt-4 mb-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="titulo-pagina m-0">
        <i class="bi bi-journal-bookmark-fill me-2"></i> Gestión de Materias
    </h3>

    <a href="<?= BASE_URL ?>index.php?action=dashboard"
       class="btn btn-regresar px-4">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


  <!-- FORMULARIO -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold">
      <?= $modo == 'editar' ? 'Editar Materia' : 'Registrar Materia' ?>
    </div>

    <div class="card-body">
      <form method="POST" action="../controllers/materiaController.php" class="row g-3">

        <?php if ($modo == 'editar'): ?>
          <input type="hidden" name="idMateria" value="<?= $materiaEditar['idMateria'] ?>">
        <?php endif; ?>

        <div class="col-md-3">
          <label class="form-label">Nombre de la Materia</label>
          <input type="text" name="nombreMateria" class="form-control"
                 placeholder="Ej. Programación Web"
                 value="<?= $materiaEditar['nombreMateria'] ?? '' ?>" required>
        </div>

        <div class="col-md-2">
          <label class="form-label">Clave</label>
          <input type="text" name="claveMateria" class="form-control"
                 placeholder="Ej. TI401"
                 value="<?= $materiaEditar['claveMateria'] ?? '' ?>" required>
        </div>

        <div class="col-md-2">
          <label class="form-label">Horas por semana</label>
          <input type="number" name="horasSemana" class="form-control"
                 value="<?= $materiaEditar['horasSemana'] ?? '' ?>" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Periodo</label>
          <select name="idPeriodo" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php while ($p = $periodos->fetch_assoc()): ?>
              <option value="<?= $p['idPeriodo'] ?>"
                <?= ($modo == 'editar' && $materiaEditar['idPeriodo'] == $p['idPeriodo']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nombrePeriodo']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-2 text-end">
          <?php if ($modo == 'editar'): ?>
            <button type="submit" name="actualizar" class="btn btn-warning mt-4 px-4">Actualizar</button>
            <a href="materiasView.php" class="btn btn-secondary mt-4 px-4">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="insertar" class="btn btn-success mt-4 px-4">Guardar</button>
          <?php endif; ?>
        </div>

      </form>
    </div>
  </div>

  <!-- TABLA -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Materias Registradas</div>

    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Clave</th>
            <th>Horas/Semana</th>
            <th>Periodo</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($m = $materias->fetch_assoc()): ?>
          <tr>
            <td><?= $m['idMateria'] ?></td>
            <td><?= htmlspecialchars($m['nombreMateria']) ?></td>
            <td><?= htmlspecialchars($m['claveMateria']) ?></td>
            <td><?= htmlspecialchars($m['horasSemana']) ?></td>
            <td><?= htmlspecialchars($m['nombrePeriodo'] ?? 'Sin asignar') ?></td>

            <td>
              <a href="materiasView.php?edit=<?= $m['idMateria'] ?>" class="btn btn-warning btn-sm">Editar</a>
              <a href="../controllers/materiaController.php?delete=<?= $m['idMateria'] ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('¿Seguro que deseas eliminar la materia <?= htmlspecialchars($m['nombreMateria']) ?>?');">
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

<!-- MENSAJE -->
<?php if (!empty($mensaje)): ?>
<div class="modal fade show" style="display:block;">
  <div class="modal-dialog">
    <div class="modal-content border-<?= $tipo ?>">
      <div class="modal-header bg-<?= $tipo ?> text-white">
        <h5 class="modal-title"><?= $tipo == 'success' ? 'Éxito' : 'Error' ?></h5>
      </div>
      <div class="modal-body"><p><?= htmlspecialchars($mensaje) ?></p></div>
      <div class="modal-footer">
        <a href="materiasView.php" class="btn btn-<?= $tipo ?>">Cerrar</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- FOOTER -->
<footer>
  © 2025 Sistema de Gestión Escolar
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

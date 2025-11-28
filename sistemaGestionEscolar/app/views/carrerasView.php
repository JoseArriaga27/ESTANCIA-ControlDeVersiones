<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/carreraModel.php';

session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'Sin rol';

$model = new CarreraModel($connection);
$carreras = $model->obtenerCarreras();

$mensaje = $_GET['msg'] ?? '';
$tipo = $_GET['type'] ?? '';

$modo = isset($_GET['edit']) ? 'editar' : 'insertar';
$carreraEditar = null;
if ($modo === 'editar') {
    $carreraEditar = $model->obtenerPorId(intval($_GET['edit']));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Carreras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background-color: #f8f9fa; }
    .navbar { background-color: #007bff; }
    .card-header { background-color: #5a5a5a; color: #fff; }
    footer { background-color: #007bff; color: white; padding: 10px 0; text-align: center; margin-top: 40px; }
  </style>
</head>
<body>

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

<div class="container mt-4 mb-5">
  <h3 class="text-center mb-4 fw-semibold">Gestión de Carreras</h3>

  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold"><?= $modo == 'editar' ? 'Editar Carrera' : 'Registrar Nueva Carrera' ?></div>
    <div class="card-body">
      <form method="POST" action="../controllers/carreraController.php" class="row g-3">
        <?php if ($modo === 'editar' && $carreraEditar): ?>
          <input type="hidden" name="idCarrera" value="<?= $carreraEditar['idCarrera'] ?>">
        <?php endif; ?>

        <div class="col-md-5">
          <label class="form-label">Nombre de la Carrera</label>
          <input type="text" name="nombreCarrera" class="form-control"
                 placeholder="Ej. Ingeniería en Tecnologías de la Información"
                 value="<?= htmlspecialchars($carreraEditar['nombreCarrera'] ?? '') ?>" required>
        </div>

        <div class="col-md-5">
          <label class="form-label">Descripción</label>
          <input type="text" name="descripcion" class="form-control"
                 placeholder="Ej. Carrera enfocada al desarrollo de software"
                 value="<?= htmlspecialchars($carreraEditar['descripcion'] ?? '') ?>">
        </div>

        <div class="col-md-2 text-end">
          <?php if ($modo === 'editar' && $carreraEditar): ?>
            <button type="submit" name="actualizar" class="btn btn-warning mt-4 px-4">Actualizar</button>
            <a href="carrerasView.php" class="btn btn-secondary mt-4 px-4">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="insertar" class="btn btn-success mt-4 px-4">Guardar</button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Carreras Registradas</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($c = $carreras->fetch_assoc()): ?>
            <tr>
              <td><?= $c['idCarrera'] ?></td>
              <td><?= htmlspecialchars($c['nombreCarrera']) ?></td>
              <td><?= htmlspecialchars($c['descripcion'] ?? '—') ?></td>
              <td>
                <a href="carrerasView.php?edit=<?= $c['idCarrera'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="../controllers/carreraController.php?delete=<?= $c['idCarrera'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Seguro que deseas eliminar la carrera <?= htmlspecialchars($c['nombreCarrera']) ?>?');">
                   Eliminar
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if (!empty($mensaje)): ?>
  <div class="modal fade show" id="modalMensaje" tabindex="-1" style="display:block;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content border-<?= $tipo == 'success' ? 'success' : 'danger' ?>">
        <div class="modal-header bg-<?= $tipo ?> text-white">
          <h5 class="modal-title"><?= $tipo == 'success' ? 'Éxito' : 'Error' ?></h5>
        </div>
        <div class="modal-body"><p><?= htmlspecialchars($mensaje) ?></p></div>
        <div class="modal-footer">
          <a href="carrerasView.php" class="btn btn-<?= $tipo ?>">Cerrar</a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="text-center my-4">
    <a href="../views/Dashboard/dashboard_admin.php" class="btn btn-primary btn-lg">
      <i class="bi bi-arrow-left-circle"></i> Regresar al inicio
    </a>
  </div>
</div>

<footer>© 2025 Sistema de Gestión Escolar</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

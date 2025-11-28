<?php
session_start();

// ===========================================
// 🔐 PROTECCIÓN DE ACCESO (SOLO ADMINISTRADOR)
// ===========================================

// Si no hay sesión → fuera
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php?action=login");
    exit;
}

// Si el rol NO es Administrador → fuera
if ($_SESSION['usuario']['rol'] !== 'Administrador') {
    header("Location: ../../index.php?action=login");
    exit;
}

// Variables del usuario logueado
$nombreUsuario = $_SESSION['usuario']['nombre'];
$rolUsuario = $_SESSION['usuario']['rol'];

// ===========================================
// CARGA DE ARCHIVOS DESPUÉS DE VALIDAR SESIÓN
// ===========================================
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/grupoModel.php';
require_once __DIR__ . '/../../app/models/periodoModel.php';

// ===========================================
// LÓGICA DE LA VISTA
// ===========================================
$model = new GrupoModel($connection);
$periodoModel = new PeriodoModel($connection);

$grupos = $model->obtenerGrupos();
$periodos = $periodoModel->obtenerPeriodos();
$carreras = $model->obtenerCarreras();

$mensaje = $_GET['msg'] ?? '';
$tipo = $_GET['type'] ?? '';

$modo = isset($_GET['edit']) ? 'editar' : 'insertar';
$grupoEditar = ($modo === 'editar') ? $model->obtenerPorId(intval($_GET['edit'])) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Grupos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* NAVBAR Y FOOTER INSTITUCIONALES */
    .navbar, footer {
      background-color: #0A2A43 !important;
    }

    footer {
      color: white;
      padding: 12px 0;
      margin-top: auto;
      text-align: center;
      font-size: 15px;
      font-weight: 500;
    }

    /* BOTÓN REGRESAR */
    .btn-institucional {
      background-color: #0A2A43 !important;
      color: white !important;
      border: 1px solid #0A2A43 !important;
      border-radius: 8px;
    }

    .btn-institucional:hover {
      background-color: #09324f !important;
      color: #fff !important;
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
        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($nombreUsuario) ?>
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
        <i class="bi bi-people-fill me-2"></i> Gestión de Grupos
    </h3>

    <a href="<?= BASE_URL ?>index.php?action=dashboard"
       class="btn btn-regresar px-4">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


  <!-- FORMULARIO -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold"><?= $modo == 'editar' ? 'Editar Grupo' : 'Registrar Grupo' ?></div>

    <div class="card-body">
      <form method="POST" action="../controllers/grupoController.php" class="row g-3">

        <?php if ($modo == 'editar'): ?>
          <input type="hidden" name="idGrupo" value="<?= $grupoEditar['idGrupo'] ?>">
        <?php endif; ?>

        <div class="col-md-3">
          <label class="form-label">Nombre del Grupo</label>
          <input type="text" name="nombreGrupo" class="form-control"
                 placeholder="Ej. TI7A"
                 value="<?= htmlspecialchars($grupoEditar['nombreGrupo'] ?? '') ?>" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Periodo Escolar</label>
          <select name="idPeriodo" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php while ($p = $periodos->fetch_assoc()): ?>
              <option value="<?= $p['idPeriodo'] ?>" <?= ($grupoEditar && $grupoEditar['idPeriodo'] == $p['idPeriodo']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nombrePeriodo']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Carrera</label>
          <select name="idCarrera" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php while ($c = $carreras->fetch_assoc()): ?>
              <option value="<?= $c['idCarrera'] ?>" <?= ($grupoEditar && $grupoEditar['idCarrera'] == $c['idCarrera']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombreCarrera']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-3 text-end">
          <?php if ($modo == 'editar'): ?>
            <button type="submit" name="actualizar" class="btn btn-warning mt-4 px-4">Actualizar</button>
            <a href="gruposView.php" class="btn btn-secondary mt-4 px-4">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="insertar" class="btn btn-success mt-4 px-4">Guardar</button>
          <?php endif; ?>
        </div>

      </form>
    </div>
  </div>
  <!-- BUSCADOR EN VIVO -->
  <div class="card mb-3 shadow-sm">
    <div class="card-body">

      <div class="row align-items-center">

        <!-- Campo de búsqueda -->
        <div class="col-md-4">
          <input 
            type="text" 
            id="buscadorGrupos" 
            class="form-control"
            placeholder="Buscar grupo..."
            style="border: 2px solid #0A2A43;"
          >
        </div>

        <!-- Botón limpiar alineado a la derecha -->
        <div class="col-md-8 d-flex justify-content-end">
          <a 
            href="gruposView.php" 
            class="btn"
            style="background: #0A2A43; color: white; font-weight: 600;"
          >
            Limpiar
          </a>
        </div>

      </div>

    </div>
  </div>

  <!-- TABLA DE GRUPOS -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Grupos Registrados</div>

    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Periodo</th>
            <th>Carrera</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
        <?php while ($g = $grupos->fetch_assoc()): ?>
          <tr>
            <td><?= $g['idGrupo'] ?></td>
            <td><?= htmlspecialchars($g['nombreGrupo']) ?></td>
            <td><?= htmlspecialchars($g['nombrePeriodo'] ?? '—') ?></td>
            <td><?= htmlspecialchars($g['nombreCarrera'] ?? '—') ?></td>

            <td>
              <a href="gruposView.php?edit=<?= $g['idGrupo'] ?>" class="btn btn-warning btn-sm">Editar</a>

              <a href="../controllers/grupoController.php?delete=<?= $g['idGrupo'] ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('¿Seguro que deseas eliminar el grupo <?= htmlspecialchars($g['nombreGrupo']) ?>?');">
                 Eliminar
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>

      </table>
    </div>
  </div>

  <!-- MENSAJE -->
  <?php if (!empty($mensaje)): ?>
    <div class="modal fade show" id="modalMensaje" tabindex="-1"
         style="display:block;" aria-modal="true" role="dialog">

      <div class="modal-dialog">
        <div class="modal-content border-<?= $tipo == 'success' ? 'success' : 'danger' ?>">

          <div class="modal-header bg-<?= $tipo ?> text-white">
            <h5 class="modal-title"><?= $tipo == 'success' ? 'Éxito' : 'Error' ?></h5>
          </div>

          <div class="modal-body">
            <p><?= htmlspecialchars($mensaje) ?></p>
          </div>

          <div class="modal-footer">
            <a href="gruposView.php" class="btn btn-<?= $tipo ?>">Cerrar</a>
          </div>

        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- FOOTER -->
<footer>
  © 2025 Sistema de Gestión Escolar
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.getElementById('buscadorGrupos');
    const filas = document.querySelectorAll("table tbody tr");

    buscador.addEventListener("input", () => {
        const texto = buscador.value.toLowerCase();

        filas.forEach(fila => {
            const contenido = fila.innerText.toLowerCase();
            fila.style.display = contenido.includes(texto) ? "" : "none";
        });
    });
});
</script>

</body>
</html>

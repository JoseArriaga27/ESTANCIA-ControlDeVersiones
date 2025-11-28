<?php
session_start();

// =======================================
// 🔐 VALIDACIÓN DE ACCESO
// =======================================

// Si no hay sesión → redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php?action=login");
    exit;
}

// Obtener rol
$rolUsuario = $_SESSION['usuario']['rol'];

// Solo Admin y Administrativo pueden entrar
if ($rolUsuario !== 'Administrador' && $rolUsuario !== 'Administrativo') {
    header("Location: ../../index.php?action=login");
    exit;
}

// Usuario válido
$nombreUsuario = $_SESSION['usuario']['nombre'];

// =======================================
// Cargar configuración y modelos
// =======================================
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/asignacionModel.php';

$model = new AsignacionModel($connection);
$model->syncDocentes();

$docentes      = $model->obtenerDocentes();
$materias      = $model->obtenerMaterias();
$grupos        = $model->obtenerGrupos();
$periodos      = $model->obtenerPeriodos();
$asignaciones  = $model->obtenerAsignaciones();

$mensaje = $_GET['msg']  ?? '';
$tipo    = $_GET['type'] ?? '';

$modo = isset($_GET['edit']) ? 'editar' : 'insertar';
$asigEditar = ($modo === 'editar') 
    ? $model->obtenerAsignacionPorId(intval($_GET['edit'])) 
    : null;

// COLORES DEL TEMA SEGÚN TIPO DE USUARIO
$colorPrimario = "#0A2A43"; // Azul Admin
$colorHover    = "#093455";

if ($rolUsuario === "Administrativo") {
    $colorPrimario = "#320B86"; // Morado
    $colorHover    = "#250A60";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignación de Materias a Docentes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body{
      background:#f8f9fa;
    }

    .navbar{
      background: <?= $colorPrimario ?> !important;
    }

    .card-header{
      background:#5a5a5a;
      color:#fff;
    }

    footer{
      background: <?= $colorPrimario ?>;
      color:#fff;
      text-align:center;
      padding:10px 0;
      margin-top:40px;
    }

    .btn-institucional {
      background-color: <?= $colorPrimario ?> !important;
      color: white !important;
      border: 1px solid <?= $colorPrimario ?> !important;
      border-radius: 8px;
    }

    .btn-institucional:hover {
      background-color: <?= $colorHover ?> !important;
      color: #fff !important;
    }

    .titulo-pagina {
        color: <?= $colorPrimario ?>;
        font-weight: 700;
    }

    .btn-regresar {
        background: <?= $colorPrimario ?>;
        color: #fff;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-regresar:hover {
        background: <?= $colorHover ?>;
        color: #fff;
    }
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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="titulo-pagina m-0">
        <i class="bi bi-person-video3 me-2"></i> Asignación de Materias a Docentes
    </h3>

    <a href="<?= BASE_URL ?>index.php?action=<?= $rolUsuario === 'Administrativo' ? 'dashboard_administrativo' : 'dashboard' ?>" 
      class="btn btn-regresar px-4">

        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold">Nueva Asignación</div>
    <div class="card-body">
      <form method="POST" action="../controllers/asignacionController.php" class="row g-3">
        <?php if ($modo === 'editar' && $asigEditar): ?>
          <input type="hidden" name="idAsignacion" value="<?= $asigEditar['idAsignacion'] ?>">
        <?php endif; ?>

        <div class="col-md-4">
          <label class="form-label">Docente</label>
          <select name="idDocente" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              $docentes->data_seek(0);
              while($d = $docentes->fetch_assoc()):
                $sel = ($modo==='editar' && $asigEditar && $asigEditar['idDocente']==$d['idDocente']) ? 'selected' : '';
            ?>
              <option value="<?= $d['idDocente'] ?>" <?= $sel ?>><?= htmlspecialchars($d['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Materia</label>
          <select name="idMateria" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              $materias->data_seek(0);
              while($m = $materias->fetch_assoc()):
                $sel = ($modo==='editar' && $asigEditar && $asigEditar['idMateria']==$m['idMateria']) ? 'selected' : '';
            ?>
              <option value="<?= $m['idMateria'] ?>" <?= $sel ?>><?= htmlspecialchars($m['nombreMateria']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Periodo Escolar</label>
          <select name="idPeriodo" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              $periodos->data_seek(0);
              while($p = $periodos->fetch_assoc()):
                $sel = ($modo==='editar' && $asigEditar && $asigEditar['idPeriodo']==$p['idPeriodo']) ? 'selected' : '';
            ?>
              <option value="<?= $p['idPeriodo'] ?>" <?= $sel ?>><?= htmlspecialchars($p['nombrePeriodo']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-12">
          <label class="form-label">Grupo</label>
          <select name="idGrupo" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              $grupos->data_seek(0);
              while($g = $grupos->fetch_assoc()):
                $sel = ($modo==='editar' && $asigEditar && $asigEditar['idGrupo']==$g['idGrupo']) ? 'selected' : '';
            ?>
              <option value="<?= $g['idGrupo'] ?>" <?= $sel ?>><?= htmlspecialchars($g['nombreGrupo']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-12 text-end">
          <?php if ($modo === 'editar' && $asigEditar): ?>
            <button type="submit" name="actualizar" class="btn btn-warning px-4">Actualizar</button>
            <a href="asignacionesView.php" class="btn btn-secondary px-4">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="insertar" class="btn btn-success px-4">Asignar</button>
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
            placeholder="Buscar asignaciones..."
            style="border: 2px solid #0A2A43;"
          >
        </div>

        <!-- Botón limpiar alineado a la derecha -->
        <div class="col-md-8 d-flex justify-content-end">
          <a 
            href="asignacionesView.php" 
            class="btn"
            style="background: #0A2A43; color: white; font-weight: 600;"
          >
            Limpiar
          </a>
        </div>

      </div>

    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Materias Asignadas</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Docente</th>
            <th>Materia</th>
            <th>Grupo</th>
            <th>Periodo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($a = $asignaciones->fetch_assoc()): ?>
            <tr>
              <td><?= $a['idAsignacion'] ?></td>
              <td><?= htmlspecialchars($a['docente']) ?></td>
              <td><?= htmlspecialchars($a['nombreMateria']) ?></td>
              <td><?= htmlspecialchars($a['nombreGrupo']) ?></td>
              <td><?= htmlspecialchars($a['nombrePeriodo']) ?></td>
              <td class="d-flex gap-2 justify-content-center">
                <a href="asignacionesView.php?edit=<?= $a['idAsignacion'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="../controllers/asignacionController.php?delete=<?= $a['idAsignacion'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Seguro que deseas eliminar esta asignación?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if(!empty($mensaje)): ?>
  <div class="modal fade show" id="modalMensaje" tabindex="-1" style="display:block;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content border-<?= $tipo=='success' ? 'success' : 'danger' ?>">
        <div class="modal-header bg-<?= $tipo ?> text-white">
          <h5 class="modal-title"><?= $tipo=='success' ? 'Éxito' : 'Error' ?></h5>
        </div>
        <div class="modal-body"><p><?= htmlspecialchars($mensaje) ?></p></div>
        <div class="modal-footer">
          <a href="asignacionesView.php" class="btn btn-<?= $tipo ?>">Cerrar</a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<footer>© 2025 Sistema de Gestión Escolar</footer>
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

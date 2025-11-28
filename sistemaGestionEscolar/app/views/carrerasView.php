<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php?action=login");
    exit;
}

// Si NO es administrador → fuera
if ($_SESSION['usuario']['rol'] !== 'Administrador') {
    header("Location: ../../index.php?action=login");
    exit;
}

// Si llega aquí → el usuario ES administrador
$nombreUsuario = $_SESSION['usuario']['nombre'];
$rolUsuario = $_SESSION['usuario']['rol'];

// ==============================
// CARGAR ARCHIVOS LUEGO DE PROTEGER
// ==============================
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/carreraModel.php';

// ==============================
// CONSULTAS Y LÓGICA
// ==============================
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
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* COLORES INSTITUCIONALES */
    .navbar, footer {
      background-color: #0A2A43;
    }
    .btn-institucional {
      background-color: #0A2A43 !important;
      color: white !important;
      border: 1px solid #0A2A43 !important;
    }

    .btn-institucional:hover {
      background-color: #09324f !important;
      color: #fff !important;
    }

    .card-header {
      background-color: #5a5a5a;
      color: white;
    }
    .boton-regresar {
        background-color: #0A2A43 !important;
        color: white !important;
        border: 1px solid #0A2A43 !important;
        border-radius: 8px;
    }
    .boton-regresar:hover {
        background-color: #09324f !important;
        color: white !important;
    }
    footer {
        background-color: #0A2A43;
        color: white;
        padding: 10px 0;
        text-align: center;
        margin-top: auto;
    }
    /* Título superior */
    .titulo-pagina {
        color: #0A2A43;
        font-weight: 700;
    }

    /* Botón regresar */
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
        <i class="bi bi-mortarboard-fill me-2"></i> Gestión de Carreras
    </h3>

    <a href="<?= BASE_URL ?>index.php?action=dashboard" 
       class="btn btn-regresar px-4">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


  <!-- FORMULARIO -->
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
                 value="<?= htmlspecialchars($carreraEditar['nombreCarrera'] ?? '') ?>"
                 required>
        </div>

        <div class="col-md-5">
          <label class="form-label">Descripción</label>
          <input type="text" name="descripcion" class="form-control"
                 value="<?= htmlspecialchars($carreraEditar['descripcion'] ?? '') ?>">
        </div>

        <div class="col-md-2 text-end">
          <?php if ($modo === 'editar'): ?>
            <button type="submit" name="actualizar" class="btn btn-warning mt-4 px-4">Actualizar</button>
            <a href="carrerasView.php" class="btn btn-secondary mt-4 px-4">Cancelar</a>

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
          id="buscadorCarreras" 
          class="form-control"
          placeholder="Buscar carrera..."
          style="border: 2px solid #0A2A43;"
        >
      </div>

      <!-- Botón limpiar alineado a la derecha -->
      <div class="col-md-8 d-flex justify-content-end">
        <a 
          href="carrerasView.php" 
          class="btn"
          style="background: #0A2A43; color: white; font-weight: 600;"
        >
          Limpiar
        </a>
      </div>

    </div>

  </div>
</div>

  <!-- TABLA DE CARRERAS -->
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
</div>

<!-- FOOTER -->
<footer>
  © 2025 Sistema de Gestión Escolar
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.getElementById('buscadorCarreras');
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

<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/db_connection.php';
require_once __DIR__ . '/../../../app/models/alumnoModel.php';

session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario    = $_SESSION['usuario']['rol'] ?? 'Sin rol';

$model = new AlumnoModel($connection);
$alumnos       = $model->obtenerAlumnos();
$carreras      = $model->obtenerCarreras();
$usuarios      = $model->obtenerUsuariosDisponibles();
$grupos        = $model->obtenerGrupos();
$inscripciones = $model->obtenerInscripciones();

$mensaje = $_GET['msg']  ?? '';
$tipo    = $_GET['type'] ?? '';

/* =============================
   COLOR POR ROL
============================= */
$colorPrimario = "#0A2A43"; // azul admin
$colorHover    = "#093455";

if ($rolUsuario === "Administrativo") {
    $colorPrimario = "#320B86"; // morado
    $colorHover    = "#320B86"; // morado más oscuro
}

// Dashboard destino
$dashboardDestino = ($rolUsuario === "Administrativo")
                    ? "dashboard_administrativo"
                    : "dashboard";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Alumnos del Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body{
      background:#f8f9fa;
      min-height:100vh;
      display:flex;
      flex-direction:column;
    }

    .navbar, footer{
      background: <?= $colorPrimario ?> !important;
    }

    footer{
      color:white;
      text-align:center;
      padding:12px 0;
      margin-top:auto;
      font-size:15px;
      font-weight:500;
    }

    .card-header{
      background:#5a5a5a;
      color:#fff;
    }

    .btn-institucional{
      background: <?= $colorPrimario ?> !important;
      color:white !important;
      border:1px solid <?= $colorPrimario ?> !important;
      border-radius:8px;
    }
    .btn-institucional:hover{
      background: <?= $colorHover ?> !important;
      color:white !important;
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

  <!-- ENCABEZADO + BOTONES PDF / EXCEL + REGRESAR -->
  <div class="d-flex justify-content-between align-items-center mb-4">

      <!-- TÍTULO -->
      <h3 class="titulo-pagina m-0">
          <i class="bi bi-people-fill me-2"></i> Reporte de Alumnos
      </h3>

      <!-- BOTONES: PDF - EXCEL - REGRESAR -->
      <div class="d-flex gap-3">

          <!-- BOTÓN PDF -->
          <form action="<?= BASE_URL ?>index.php?action=generarPDF_AlumnosGeneral" method="POST" target="_blank">
            <button type="submit" class="btn btn-institucional px-3">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </button>
          </form>

          <!-- BOTÓN EXCEL -->
          <form action="<?= BASE_URL ?>index.php?action=generarExcel_AlumnosGeneral" method="POST">
            <button type="submit" class="btn btn-success px-3">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </button>
          </form>

          <!-- BOTÓN REGRESAR (DINÁMICO) -->
          <a href="<?= BASE_URL ?>index.php?action=<?= $dashboardDestino ?>"
             class="btn btn-regresar px-4">
              <i class="bi bi-arrow-left"></i> Regresar
          </a>

      </div>
  </div>

  <!-- TABLA ALUMNOS -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Alumnos Registrados</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Matrícula</th>
            <th>Carrera</th>
            <th>Correo</th>
          </tr>
        </thead>
        <tbody>
          <?php $alumnos->data_seek(0); while ($a = $alumnos->fetch_assoc()): ?>
          <tr>
            <td><?= $a['idAlumno'] ?></td>
            <td><?= htmlspecialchars($a['nombreCompleto']) ?></td>
            <td><?= htmlspecialchars($a['matricula']) ?></td>
            <td><?= htmlspecialchars($a['nombreCarrera']) ?></td>
            <td><?= htmlspecialchars($a['correo']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<footer>
  © 2025 Sistema de Gestión Escolar
</footer>

</body>
</html>

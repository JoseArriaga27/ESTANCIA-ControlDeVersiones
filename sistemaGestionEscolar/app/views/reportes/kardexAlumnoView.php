<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/db_connection.php';

if (!isset($_SESSION)) session_start();
if (!in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])) {
    header("Location: index.php?action=login");
    exit;
}

require_once __DIR__ . '/../../models/reporteModel.php';

$model = new ReporteModel($connection); 
$alumnos = $model->obtenerAlumnosConCarrera();

$buscar = $_GET['buscar'] ?? '';

// Determinar dashboard dinámico
$dashboard = match($_SESSION['usuario']['rol']) {
    'Administrador'  => 'dashboard',
    'Administrativo' => 'dashboard_administrativo',
    default => 'dashboard'
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar Boleta | Administración</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body { background: #f4f4f4; }
    .navbar { background: #0A2A43; }
    .card-header { background: #0A2A43; color: white; font-weight: 600; }

    .btn-institucional {
      background: #0A2A43 !important;
      color: #fff !important;
    }
    .btn-institucional:hover {
      background: #0c355e !important;
      color: #fff !important;
    }

    /* Botón regresar estilo consultaCalif */
    .btn-regresar {
        background: #0A2A43;
        color: white;
        font-weight: 500;
    }
    .btn-regresar:hover {
        background: #0A2A43;
        color:white;
    }

    h3.titulo {
        font-weight:bold;
        color:#0A2A43;
    }
    .subtitulo {
        margin-top:-6px;
        color:#6c757d;
    }
  </style>
</head>

<body>

<nav class="navbar navbar-dark px-3">
  <span class="navbar-brand">Sistema de Gestión Escolar</span>
  <span class="text-white">
      <?= $_SESSION['usuario']['nombre'] ?> (<?= $_SESSION['usuario']['rol'] ?>)
  </span>
</nav>

<div class="container mt-4">

    <!-- ============================
         TÍTULO + BOTÓN REGRESAR
    ============================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="mb-3">
            <h3 class="titulo mb-2">
                <i class="bi bi-file-earmark-bar-graph-fill me-2"></i> 
                Generar Boleta de Calificaciones
            </h3>

            <p class="subtitulo text-muted" style="font-size: 0.95rem;">
                Seleccione un alumno para generar su boleta.
            </p>
        </div>

        <a href="<?= BASE_URL ?>index.php?action=<?= $dashboard ?>" class="btn btn-regresar px-4">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>


  <!-- ============================
        BUSCADOR
  ============================= -->
  <div class="card shadow-sm mb-4">
    <div class="card-header">Buscar Alumno</div>
    <div class="card-body">

      <div class="row g-3">
        <div class="col-md-12">
            <input type="text" id="filtroAlumnos" class="form-control" 
                   placeholder="Buscar por nombre o matrícula...">
        </div>
      </div>

    </div>
  </div>

  <!-- ============================
        TABLA
  ============================= -->
  <div class="card shadow-sm">
    <div class="card-header">Alumnos registrados</div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle text-center mb-0" id="tablaAlumnos">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Matrícula</th>
            <th>Nombre</th>
            <th>Carrera</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($alumnos as $a): ?>
          <tr>
            <td><?= $a['idAlumno'] ?></td>
            <td><?= $a['matricula'] ?></td>
            <td><?= htmlspecialchars($a['nombreCompleto']) ?></td>
            <td><?= htmlspecialchars($a['carrera']) ?></td>
            <td>
              <a href="<?= BASE_URL ?>index.php?action=generarKardex&id=<?= $a['idAlumno'] ?>" 
                class="btn btn-institucional btn-sm"
                target="_blank">
                  <i class="bi bi-file-earmark-pdf"></i> Generar PDF
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("filtroAlumnos");
    const tabla = document.getElementById("tablaAlumnos");
    const filas = tabla.getElementsByTagName("tr");

    input.addEventListener("keyup", () => {
        let filtro = input.value.toLowerCase();

        for (let i = 1; i < filas.length; i++) {
            let celdas = filas[i].getElementsByTagName("td");

            if (celdas.length > 0) {
                let matricula  = celdas[1].textContent.toLowerCase();
                let nombre     = celdas[2].textContent.toLowerCase();

                filas[i].style.display =
                    (matricula.includes(filtro) || nombre.includes(filtro))
                    ? "" : "none";
            }
        }
    });

});
</script>

</body>
</html>

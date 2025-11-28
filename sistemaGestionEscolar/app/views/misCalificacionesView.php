<?php
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: " . BASE_URL . "index.php?action=login");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Calificaciones — Alumno</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    :root {
      --primary: #6f42c1;  /* mismo color institucional para alumnos */
      --navbar-height: 48px;
    }

    body {
      background: #f5f7fb;
      padding-top: var(--navbar-height);
      margin: 0;
    }

    /* NAVBAR */
    .navbar {
      background: var(--primary) !important;
      height: var(--navbar-height);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      display: flex;
      align-items: center;
    }

    /* TITULO */
    .titulo-pagina {
        color: var(--primary);
    }

    /* HEADER DE LA TABLA */
    .card-header-custom {
        background: var(--primary);
        color: white;
        font-weight: 600;
    }

    th {
        background: #ececec !important;
    }

    td.fw-bold {
        color: var(--primary);
        font-weight: 700;
    }

    .btn-regresar {
        background: var(--primary);
        color: #fff;
        font-weight: 500;
    }
    .btn-regresar:hover {
        background: #5a32a0;
        color: #fff;
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-3">
  <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>

  <div class="ms-auto text-white">
    <i class="bi bi-person-circle me-1"></i>
    <?= $usuario['nombre'] ?> (Alumno)

    <a href="<?= BASE_URL ?>index.php?action=logout" 
       class="btn btn-outline-light btn-sm ms-3">
      <i class="bi bi-box-arrow-right"></i> Salir
    </a>
  </div>
</nav>


<main class="main p-4">
  <div class="container">

    <!-- TÍTULO -->
    <h3 class="fw-bold titulo-pagina mb-4">
        <i class="bi bi-journal-check me-2"></i> Mis Calificaciones
    </h3>

    <!-- BOTÓN REGRESAR ALINEADO A LA DERECHA (arriba de la tabla) -->
    <div class="d-flex justify-content-end mb-2">
        <a href="<?= BASE_URL ?>index.php?action=dashboard_alumno" 
           class="btn btn-regresar px-4">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <!-- TARJETA -->
    <div class="card shadow-sm">
      <div class="card-header card-header-custom">
        <i class="bi bi-list-check me-1"></i> Calificaciones actuales
      </div>

      <div class="card-body">

        <table class="table table-hover align-middle text-center">
          <thead>
            <tr>
              <th>Clave</th>
              <th>Materia</th>
              <th>Grupo</th>
              <th>Periodo</th>
              <th>P1</th>
              <th>P2</th>
              <th>P3</th>
              <th>Final</th>
            </tr>
          </thead>

          <tbody>
            <?php while ($row = $calificaciones->fetch_assoc()) { ?>
              <tr>
                <td><?= $row['claveMateria'] ?></td>
                <td><?= $row['nombreMateria'] ?></td>
                <td><?= $row['nombreGrupo'] ?></td>
                <td><?= $row['nombrePeriodo'] ?></td>

                <td><?= $row['calificacionParcial1'] ?? '-' ?></td>
                <td><?= $row['calificacionParcial2'] ?? '-' ?></td>
                <td><?= $row['calificacionParcial3'] ?? '-' ?></td>

                <td class="fw-bold"><?= $row['calificacionFinal'] ?? '-' ?></td>
              </tr>
            <?php } ?>
          </tbody>

        </table>

      </div>
    </div>

  </div>
</main>

</body>
</html>

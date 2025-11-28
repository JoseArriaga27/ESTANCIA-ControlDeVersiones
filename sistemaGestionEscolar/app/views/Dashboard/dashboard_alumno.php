<?php
  require_once __DIR__ . '/../../../config/config.php'; 
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

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
  <title>Alumno — Sistema de Gestión Escolar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    :root {
      --primary: #6f42c1; /* Color morado del alumno */
      --sidebar-width: 260px;
      --navbar-height: 48px;
    }

    body {
      background: #f5f7fb;
      margin: 0;
      padding-top: var(--navbar-height);
    }

    .navbar {
      background: var(--primary) !important;
      height: var(--navbar-height);
      display: flex;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    .sidebar {
      width: var(--sidebar-width);
      background: #fff;
      border-right: 1px solid #e5e7eb;
      position: fixed;
      top: var(--navbar-height);
      left: 0;
      height: calc(100vh - var(--navbar-height));
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      padding-top: 12px;
    }

    .menu-title {
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      color: #6b7280;
      padding: 12px 18px 4px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 18px;
      color: #374151;
      text-decoration: none;
      border-radius: 6px;
      transition: 0.2s;
    }
    .nav-link:hover {
      background: rgba(111, 66, 193, .15);
      color: var(--primary);
    }

    .logout-item {
      margin-top: auto;
      margin-bottom: 10px;
    }
    .logout-item .nav-link {
      color: #dc3545;
    }
    .logout-item .nav-link:hover {
      background: rgba(220,53,69,.12);
    }

    .sidebar-footer {
      padding: 12px;
      font-size: 0.85rem;
      text-align: center;
      color: #6b7280;
      border-top: 1px solid #e5e7eb;
    }

    .main {
      margin-left: var(--sidebar-width);
      padding: 1.2rem;
    }
    /* Asegura que la sidebar use flex vertical */
    .sidebar {
      display: flex;
      flex-direction: column;
    }

    /* El UL toma todo el espacio disponible */
    .sidebar ul {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* El logout siempre al final del UL */
    .logout-item {
      margin-top: auto !important;
    }

    /* El footer SIEMPRE pegado abajo */
    .sidebar-footer {
      margin-top: auto;
    }

  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>

    <div class="ms-auto">
      <span class="text-white me-3">
        <i class="bi bi-person-circle me-1"></i>
        <?= htmlspecialchars($usuario['nombre']) ?> (Alumno)
      </span>

      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<!-- SIDEBAR -->
<aside class="sidebar">
  <ul class="nav flex-column">

    <!-- ACADÉMICO -->
    <div class="menu-group">
      <li class="menu-title">Mi Información</li>
      <div class="submenu">
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=misMaterias">
          <i class="bi bi-journal-bookmark"></i> Mis Materias
        </a>

        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=misCalificaciones">

          <i class="bi bi-clipboard-check"></i> Mis Calificaciones
        </a>

        <a class="nav-link" 
          href="<?= BASE_URL ?>index.php?action=generarKardexA" 
          target="_blank">
          <i class="bi bi-file-earmark-person"></i> Mi Boleta / Kárdex
        </a>

      </div>
    </div>
    
    <!-- CERRAR SESIÓN -->
    <li class="logout-item">
      <a class="nav-link text-danger" href="<?= BASE_URL ?>index.php?action=logout">
        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
      </a>
    </li>

  </ul>

  <footer class="sidebar-footer">© 2025 Sistema de Gestión Escolar</footer>
</aside>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">

    <h3 class="mb-4">Dashboard del Alumno</h3>

    <div class="row">

        <!-- Gráfica de Barras -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-semibold">
                    Calificaciones por Materia
                </div>
                <div class="card-body">
                    <canvas id="chartMaterias" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Donut -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-semibold">
                    Promedio General
                </div>
                <div class="card-body text-center">
                    <canvas id="chartPromedio" height="150"></canvas>
                    <h4 class="mt-3 fw-bold">
                        <?= round($promedio, 1) ?> / 10
                    </h4>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const materias = <?= json_encode($materias) ?>;
const calificaciones = <?= json_encode($calificaciones) ?>;
const promedio = <?= json_encode(round($promedio, 1)) ?>;

// ----------- Gráfica de Barras -----------
new Chart(document.getElementById('chartMaterias'), {
    type: 'bar',
    data: {
        labels: materias,
        datasets: [{
            label: "Calificación Final",
            data: calificaciones,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, max: 10 }
        }
    }
});

// ----------- Donut Promedio -----------
const restante = 10 - promedio;

new Chart(document.getElementById('chartPromedio'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [promedio, restante],
            backgroundColor: ["#198754", "#e9ecef"],
            cutout: "70%"
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
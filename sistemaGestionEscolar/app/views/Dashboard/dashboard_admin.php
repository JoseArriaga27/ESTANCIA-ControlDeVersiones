<?php
if (!isset($_SESSION['usuario'])) { 
  header("Location: ../../../index.php?action=login"); 
  exit; 
}
$usuario = $_SESSION['usuario'];
require_once __DIR__ . '/../../../config/config.php'; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador General — Sistema de Gestión Escolar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>

    :root {
      --primary: #0A2A43;
      --sidebar-width: 260px;
      --navbar-height: 48px; 
    }

    /* =============================
      GENERAL
    ============================= */
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
      z-index: 1000;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
    }

    /* =============================
      SIDEBAR
    ============================= */
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

    /* =============================
      TITULOS
    ============================= */
    .menu-title {
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      color: #6b7280;
      padding: 12px 18px 4px;
      cursor: default;
    }

    /* =============================
      LINKS (SIEMPRE VISIBLES)
    ============================= */
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
      background: rgba(13,110,253,.12);
      color: var(--primary);
    }

    .nav-link i {
      font-size: 1.1rem;
    }
    /* =============================
      LOGOUT SIEMPRE ABAJO
    ============================= */
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

    /* =============================
      FOOTER sidebar
    ============================= */
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

    .sidebar ul {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Tamaños de gráficas */
    .chart-box {
      height: 260px;
      position: relative;
    }

    .chart-box-sm {
      height: 200px;
      position: relative;
    }

    .chart-box-lg {
      height: 320px;
      position: relative;
    }
    .titulo-estadisticas {
      color: #0A2A43 !important;
    }
    /* ALERTA FLOTANTE */
    .alert-floating {
        position: fixed;
        top: 65px;
        left: 50%;
        transform: translateX(-50%);
        padding: 14px 26px;
        border-radius: 8px;
        font-size: 16px;
        color: white;
        z-index: 2000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: fadeIn 0.4s ease-out;
    }

    .alert-success {
        background: #198754; /* Verde */
    }

    .alert-danger {
        background: #dc3545; /* Rojo */
    }

    /* Animación al aparecer */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilo general de alertas */
    .alerta-auto {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        min-width: 320px;
        animation: fadeInUp 0.45s ease-out;
    }

  </style>
</head>
<body>

<?php if(isset($_SESSION['flash_msg'])): ?>
  <div id="alertaRestauracion" 
      class="alert-floating alert-<?= $_SESSION['flash_type'] ?? 'success' ?>">
    <?= $_SESSION['flash_msg']; ?>
  </div>
 
  <script>
  setTimeout(() => {
      document.getElementById('alertaRestauracion').style.display = 'none';
  }, 3000);
  </script>

  <?php 
  unset($_SESSION['flash_msg']); 
  unset($_SESSION['flash_type']);
  endif; 
?>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>
    <div class="ms-auto">
      <span class="text-white me-3">
        <i class="bi bi-person-circle me-1"></i>
        <?= htmlspecialchars($usuario['nombre']) ?> (<?= htmlspecialchars($usuario['rol']) ?>)
      </span>
      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<aside class="sidebar">
  <ul class="nav flex-column">

    <!-- INICIO -->
    <div class="menu-group">
      <li class="menu-title">Inicio</li>
      <div class="submenu">
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=dashboard">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </div>
    </div>

    <!-- ADMINISTRACIÓN -->
    <div class="menu-group">
      <li class="menu-title">Administración Académica</li>
      <div class="submenu">
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=usuarios"><i class="bi bi-people-fill"></i> Usuarios</a>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/carrerasView.php"><i class="bi bi-mortarboard"></i> Carreras</a>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/periodosView.php"><i class="bi bi-calendar3"></i> Periodos</a>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/gruposView.php"><i class="bi bi-collection"></i> Grupos</a>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/materiasView.php"><i class="bi bi-journal-text"></i> Materias</a>
      </div>
    </div>

    <!-- PROCESOS -->
    <div class="menu-group">
      <li class="menu-title">Procesos Académicos</li>
      <div class="submenu">
        <a class="nav-link" href="<?= BASE_URL ?>app/views/asignacionesView.php"><i class="bi bi-diagram-3"></i> Asignaciones</a>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/alumnosView.php"><i class="bi bi-person-lines-fill"></i> Asignar alumnos</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=capturaCalificaciones"><i class="bi bi-pencil-square"></i> Capturar calificaciones</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=consultaCalificaciones"><i class="bi bi-clipboard-data"></i> Consulta</a>
      </div>
    </div>

  <!-- REPORTES -->
  <div class="menu-group">
    <li class="menu-title">Reportes</li>

    <div class="submenu">

      <!-- Reporte: Kardex / Boleta por Alumno -->
      <a class="nav-link" href="<?= BASE_URL ?>app/views/reportes/kardexAlumnoView.php">
        <i class="bi bi-file-earmark-person"></i> Boleta / Kardex Alumno
      </a>

      <!-- Reporte: Listado general de alumnos -->
      <a class="nav-link" href="<?= BASE_URL ?>app/views/reportes/alumnosGeneralView.php">
        <i class="bi bi-people"></i> Alumnos (General)
      </a>

      <!-- Reporte: Calificaciones por grupo -->
      <a class="nav-link" href="<?= BASE_URL ?>index.php?action=calificacionesGrupo">
          <i class="bi bi-clipboard-data"></i> Calificaciones por Grupo
      </a>


      <!-- Reporte: Estadísticas del sistema -->
      <a class="nav-link" href="<?= BASE_URL ?>index.php?action=estadisticasPDF">
        <i class="bi bi-graph-up"></i> Estadísticas
      </a>

      <li>
    <a class="nav-link" href="<?= BASE_URL ?>index.php?action=reporteAlumnosCarrera">
        <i class="bi bi-people-fill"></i> Alumnos por Carrera
    </a>
</li>
    </div>
  </div>


    <!-- SISTEMA -->
    <div class="menu-group">
      <li class="menu-title">Sistema</li>
      <div class="submenu">
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=backup"><i class="bi bi-cloud-arrow-down"></i> Respaldar BD</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=restore"><i class="bi bi-arrow-counterclockwise"></i> Restaurar BD</a>
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



<!-- ===================================== -->
<!-- Contenido principal -->
<!-- ===================================== -->
<main class="main">
  <div class="container-fluid">

    <!-- Tarjetas superiores -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Usuarios</div>
              <div class="fs-4"><?= $usuarios ?></div>
            </div>
            <div class="icon-wrap"><i class="bi bi-people-fill"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Materias</div>
              <div class="fs-4"><?= $materias ?></div>
            </div>
            <div class="icon-wrap"><i class="bi bi-journal"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Grupos</div>
              <div class="fs-4"><?= $grupos ?></div>
            </div>
            <div class="icon-wrap"><i class="bi bi-collection-fill"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Periodo</div>
              <div class="fs-5">Sep–Dic 2025</div>
            </div>
            <div class="icon-wrap"><i class="bi bi-calendar"></i></div>
          </div>
        </div>
      </div>
    </div>
    <!-- =============================== -->
<!-- SECCIÓN DE GRÁFICAS -->
<!-- =============================== -->

<h4 class="mt-4 mb-3 fw-bold titulo-estadisticas">Estadísticas del Sistema</h4>

<div class="row g-4">

  <!-- 1. Alumnos por Carrera -->
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <h6 class="fw-semibold text-secondary">Alumnos por Carrera</h6>
      <div class="chart-box">
        <canvas id="chartCarreras"></canvas>
      </div>
    </div>
  </div>


  <!-- 2. Alumnos por Grupo -->
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <h6 class="fw-semibold text-secondary">Alumnos por Grupo</h6>
      <div class="chart-box">
        <canvas id="chartGrupos"></canvas>
      </div>
    </div>
  </div>


  <!-- 3. Materias más asignadas -->
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <h6 class="fw-semibold text-secondary">Materias más asignadas a grupos</h6>
      <div class="chart-box">
        <canvas id="chartMaterias"></canvas>
      </div>
    </div>
  </div>


  <!-- 4. Docentes con más materias -->
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <h6 class="fw-semibold text-secondary">Docentes con más asignaciones de materias</h6>
      <div class="chart-box">
        <canvas id="chartDocentes"></canvas>
      </div>
    </div>
  </div>


  <!-- 5. % Inscritos vs Totales -->
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <h6 class="fw-semibold text-secondary">% Alumnos inscritos</h6>
      <div class="chart-box-sm">
        <canvas id="chartInscritos"></canvas>
      </div>
    </div>
  </div>


  <!-- 6. Género del alumnado -->
  <div class="col-md-6">
    <div class="card shadow-sm p-3">
      <h6 class="fw-semibold text-secondary">Distribución de Género</h6>
      <div class="chart-box-sm">
        <canvas id="chartGenero"></canvas>
      </div>
    </div>
  </div>


</div>

<!-- =============================== -->
<!-- COLORES Y SCRIPTS DE CHARTJS -->
<!-- =============================== -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ===============================
    PALETA EXACTA DEL DASHBOARD
=============================== */
const colors = {
  navy: "#0A2A43",
  orange: "#FFA726",
  yellow: "#FFCC80",
  gray: "#9E9E9E",
  white: "#FFFFFF"
};

/* =========================================
   1. Alumnos por Carrera (Bar Chart)
========================================= */
new Chart(document.getElementById('chartCarreras'), {
  type: 'bar',
  data: {
    labels: [
      <?php while($row = $alumnosCarrera->fetch_assoc()) { echo "'" . $row['nombreCarrera'] . "',"; } ?>
    ],
    datasets: [{
      label: 'Alumnos',
      data: [
        <?php 
          $alumnosCarrera->data_seek(0);
          while($row = $alumnosCarrera->fetch_assoc()) { echo $row['total'] . ","; }
        ?>
      ],
      backgroundColor: colors.navy
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }

});

/* =========================================
   2. Alumnos por Grupo (Bar Horizontal)
========================================= */
new Chart(document.getElementById('chartGrupos'), {
  type: 'bar',
  data: {
    labels: [
      <?php while($row = $alumnosGrupo->fetch_assoc()) { echo "'" . $row['nombreGrupo'] . "',"; } ?>
    ],
    datasets: [{
      label: 'Alumnos',
      data: [
        <?php 
          $alumnosGrupo->data_seek(0);
          while($row = $alumnosGrupo->fetch_assoc()) { echo $row['total'] . ","; }
        ?>
      ],
      backgroundColor: colors.orange
    }]
  },
  options: { 
    indexAxis: 'y', 
    responsive: true, 
    maintainAspectRatio: false

   }
});

/* =========================================
   3. Materias más asignadas
========================================= */
new Chart(document.getElementById('chartMaterias'), {
  type: 'bar',
  data: {
    labels: [
      <?php while($row = $materiasRanking->fetch_assoc()) { echo "'" . $row['nombreMateria'] . "',"; } ?>
    ],
    datasets: [{
      label: 'Asignaciones',
      data: [
        <?php 
          $materiasRanking->data_seek(0);
          while($row = $materiasRanking->fetch_assoc()) { echo $row['total'] . ","; }
        ?>
      ],
      backgroundColor: colors.navy
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

/* =========================================
   4. Docentes con más asignaciones
========================================= */
new Chart(document.getElementById('chartDocentes'), {
  type: 'bar',
  data: {
    labels: [
      <?php while($row = $docentesRanking->fetch_assoc()) { echo "'" . $row['docente'] . "',"; } ?>
    ],
    datasets: [{
      label: 'Asignaciones',
      data: [
        <?php 
          $docentesRanking->data_seek(0);
          while($row = $docentesRanking->fetch_assoc()) { echo $row['total'] . ","; }
        ?>
      ],
      backgroundColor: colors.yellow
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

/* =========================================
   5. % Inscritos vs Totales
========================================= */
new Chart(document.getElementById('chartInscritos'), {
  type: 'doughnut',
  data: {
    labels: ['Inscritos', 'No inscritos'],
    datasets: [{
      data: [
        <?= $inscritosVsTotales['inscritos'] ?>,
        <?= $inscritosVsTotales['totalAlumnos'] - $inscritosVsTotales['inscritos'] ?>
      ],
      backgroundColor: [colors.orange, colors.gray]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

/* =========================================
   6. Género del alumnado
========================================= */
new Chart(document.getElementById('chartGenero'), {
  type: 'pie',
  data: {
    labels: [
      <?php while($row = $generoAlumnos->fetch_assoc()) { echo "'" . $row['sexo'] . "',"; } ?>
    ],
    datasets: [{
      data: [
        <?php 
          $generoAlumnos->data_seek(0);
          while($row = $generoAlumnos->fetch_assoc()) { echo $row['total'] . ","; }
        ?>
      ],
      backgroundColor: [colors.navy, colors.yellow]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

</script>
<script>
    setTimeout(() => {
        document.querySelectorAll('.alerta-auto').forEach(alerta => {
            let bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        });
    }, 3000); // 3 segundos
</script>



  </div> 
</main>

</body>
</html>

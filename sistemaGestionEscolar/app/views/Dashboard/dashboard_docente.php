<?php
    if (!isset($_SESSION)) session_start();
    $nombreUsuario = $_SESSION['usuario']['nombre'];
    $rolUsuario    = $_SESSION['usuario']['rol'];

    if ($rolUsuario !== "Docente") {
        header("Location: ../../../index.php?action=login");
        exit;
    }

    /* ============================================================
    RECIBIR VARIABLES QUE TE MANDA EL CONTROLADOR
    (DashboardController->dashboardDocente)
    ============================================================ */
    $distCalificaciones = isset($distCalificaciones) ? $distCalificaciones : [];
    $avanceCaptura     = isset($avanceCaptura) ? $avanceCaptura : [
        'p1' => 0,
        'p2' => 0,
        'finalCal' => 0,
        'totalRegistros' => 1  // para evitar división entre 0
    ];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Docente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* =============================
   COLORES DEL DOCENTE
============================= */
:root {
    --color-principal: #06402B;
    --color-principal-hover: #075238;
    --fondo: #f4f6f9;
}

/* =============================
   ESTILOS DEL LAYOUT
============================= */
body {
    background: var(--fondo);
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    background: var(--color-principal);
    color: white;
    min-height: 100vh;
    position: fixed;
}

.sidebar .brand {
    font-size: 1.3rem;
    font-weight: bold;
    padding: 20px;
    text-align: center;
    background: var(--color-principal);
    border-bottom: 2px solid rgba(255,255,255,0.15);
}

.sidebar a {
    color: white;
    text-decoration: none;
    padding: 14px 20px;
    display: block;
    font-size: 0.95rem;
    transition: 0.2s;
}

.sidebar a:hover {
    background: var(--color-principal-hover);
}

.sidebar .menu-title {
    padding: 15px 20px 5px 20px;
    font-size: 0.78rem;
    text-transform: uppercase;
    opacity: 0.7;
}

/* CONTENT */
.content {
    margin-left: 250px;
    width: calc(100% - 250px);
}

.navbar {
    background: var(--color-principal);
}

/* TARJETAS */
.card-mini {
    border-left: 4px solid var(--color-principal);
}
/* FORZAR FOOTER Y LOGOUT HASTA ABAJO DEL SIDEBAR */
.sidebar {
    display: flex;
    flex-direction: column;
}

.sidebar-footer {
    font-size: 0.85rem;
    color: #cfcfcf;
    padding: 12px;
    border-top: 1px solid rgba(255,255,255,0.2);
}
.nav-link.text-danger:hover {
    background: rgba(255, 0, 0, 0.15);
}

</style>

</head>
<body>

<div class="sidebar d-flex flex-column">

    <div class="brand">
        <i class="bi bi-mortarboard-fill me-2"></i> Docente
    </div>

    <p class="menu-title">Navegación</p>

    <a href="<?= BASE_URL ?>index.php?action=dashboard_docente">
        <i class="bi bi-house-door-fill me-2"></i> Inicio
    </a>

    <p class="menu-title">Mis actividades</p>

    <a href="<?= BASE_URL ?>index.php?action=misGrupos">
        <i class="bi bi-people-fill me-2"></i> Mis grupos
    </a>

    <a href="<?= BASE_URL ?>index.php?action=misMateriasDocente">
        <i class="bi bi-journal-bookmark-fill me-2"></i> Mis materias
    </a>

    <a href="<?= BASE_URL ?>index.php?action=capturaCalificaciones">
        <i class="bi bi-pencil-square me-2"></i> Capturar calificaciones
    </a>

    <a href="<?= BASE_URL ?>index.php?action=calificacionesGrupo">
        <i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Reporte de calificaciones
    </a>

    <!-- CERRAR SESIÓN ABAJO -->
    <div class="mt-auto">
        <a href="<?= BASE_URL ?>index.php?action=logout" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
        </a>

        <!-- FOOTER DEL SIDEBAR -->
        <div class="sidebar-footer text-center mt-3">
            © 2025 Sistema de Gestión Escolar
        </div>
    </div>

</div>


<!-- ========================================================= -->
<!-- CONTENIDO PRINCIPAL -->
<!-- ========================================================= -->
<div class="content">

    <nav class="navbar navbar-dark px-4">
        <span class="navbar-brand mb-0 h5 text-white">
            Sistema de Gestión Escolar
        </span>

        <div class="text-white d-flex align-items-center">
            <i class="bi bi-person-circle me-2"></i>
            <?= $nombreUsuario ?>
            <span class="text-white-50 ms-1">(Docente)</span>
        </div>
    </nav>

    <div class="container mt-4">

        <h3 class="fw-bold mb-4" style="color: var(--color-principal);">
            <i class="bi bi-house-door-fill me-2"></i> Panel del Docente
        </h3>

        <div class="container mt-4">
            <div class="row">
                <!-- Gráfica de calificaciones -->
                <div class="col-md-6">
                    <div class="card p-3 shadow">
                        <h6 class="fw-bold">Distribución de calificaciones</h6>
                        <canvas id="graficaCalificaciones"></canvas>
                    </div>
                </div>

                <!-- Gráfica de avance de captura -->
                <div class="col-md-6">
                    <div class="card p-3 shadow">
                        <h6 class="fw-bold">Avance de captura de calificaciones</h6>
                        <canvas id="graficaAvance"></canvas>
                    </div>
                </div>
            </div>

        </div>


        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ===== Datos de distribución de calificaciones =====
const distData = {
    labels: [
        <?php 
        foreach ($distCalificaciones as $row) {
            echo "'" . $row['nombreMateria'] . "',";
        }
        ?>
    ],
    datasets: [{
        label: 'Reprobados (0-6.9)',
        data: [
            <?php foreach ($distCalificaciones as $row) echo $row['reprobados'] . ","; ?>
        ],
        backgroundColor: 'rgba(255, 99, 132, 0.6)'
    },{
        label: '7.0-7.9',
        data: [
            <?php foreach ($distCalificaciones as $row) echo $row['setentas'] . ","; ?>
        ],
        backgroundColor: 'rgba(255, 205, 86, 0.6)'
    },{
        label: '8.0-8.9',
        data: [
            <?php foreach ($distCalificaciones as $row) echo $row['ochentas'] . ","; ?>
        ],
        backgroundColor: 'rgba(54, 162, 235, 0.6)'
    },{
        label: '9.0-10',
        data: [
            <?php foreach ($distCalificaciones as $row) echo $row['noventas'] . ","; ?>
        ],
        backgroundColor: 'rgba(75, 192, 192, 0.6)'
    }]
};

new Chart(document.getElementById('graficaCalificaciones'), {
    type: 'bar',
    data: distData,
    options: { responsive: true }
});


// ===== Datos avance captura =====
const avance = {
    p1: <?= $avanceCaptura['p1'] ?>,
    p2: <?= $avanceCaptura['p2'] ?>,
    p3: <?= $avanceCaptura['p3'] ?>,
    total: <?= $avanceCaptura['totalRegistros'] ?>,
};

new Chart(document.getElementById('graficaAvance'), {
    type: 'bar',
    data: {
        labels: ['Parcial 1', 'Parcial 2', 'Parcial 3'],
        datasets: [{
            label: 'Porcentaje capturado',
            data: [
                (avance.p1 / avance.total) * 100,
                (avance.p2 / avance.total) * 100,
                (avance.p3 / avance.total) * 100
            ],
            backgroundColor: 'rgba(153,102,255,0.6)'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                min: 0,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value;
                    }
                }
            }
        }
    }
});

</script>

</body>
</html>

<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['usuario'])) { header("Location: ../../../index.php?action=login"); exit; }

$usuario = $_SESSION['usuario'];
require_once __DIR__ . '/../../../config/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrativo — Sistema de Gestión Escolar</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* ============================================
   PALETA Y VARIABLES
============================================ */
:root {
    --morado: #320B86;
    --morado-oscuro: #25076B;
    --sidebar-width: 250px;
    --navbar-height: 50px;
}

/* ============================================
   GENERAL
============================================ */
body {
    background: #f5f7fb;
    margin: 0;
    font-family: "Segoe UI", sans-serif;
}

/* ============================================
   NAVBAR SUPERIOR
============================================ */
.navbar {
    background: var(--morado) !important;
    height: var(--navbar-height);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    padding: 0 15px;
    z-index: 1000;
    display: flex;
    align-items: center;
}

/* ============================================
   SIDEBAR LATERAL
============================================ */
.sidebar {
    width: var(--sidebar-width);
    background: var(--morado);
    color: white;
    position: fixed;
    top: var(--navbar-height);
    left: 0;
    height: calc(100vh - var(--navbar-height));
    display: flex;
    flex-direction: column;
    padding-top: 15px;
}

/* Contenedor de enlaces */
.sidebar nav {
    flex: 1; /* Ocupa todo el espacio disponible para empujar logout y footer abajo */
}

/* Títulos de secciones */
.menu-title {
    padding: 0 20px;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
    font-size: 0.75rem;
    margin-top: 10px;
}

/* Links del sidebar */
.sidebar .nav-link {
    color: white;
    padding: 10px 20px;
    display: block;
    border-radius: 6px;
    font-size: 0.92rem;
    transition: 0.2s;
}

.sidebar .nav-link i {
    margin-right: 8px;
}

.sidebar .nav-link:hover {
    background: var(--morado-oscuro);
}

/* ============================================
   BOTÓN DE CERRAR SESIÓN (ABAJO)
============================================ */
.logout-item {
    margin-bottom: 10px;
}

.logout-item .nav-link {
    color: #ffb3b3 !important;
    font-weight: 600;
}

.logout-item .nav-link:hover {
    background: rgba(255,255,255,0.15);
    color: white !important;
}

/* ============================================
   FOOTER DEL SIDEBAR (SIEMPRE ABAJO)
============================================ */
.sidebar-footer {
    padding: 12px;
    font-size: 0.85rem;
    text-align: center;
    color: rgba(255,255,255,0.8);
    background: var(--morado-oscuro);
    border-top: 1px solid rgba(255,255,255,0.1);
}

/* ============================================
   CONTENIDO PRINCIPAL
============================================ */
.main {
    margin-left: var(--sidebar-width);
    padding: calc(var(--navbar-height) + 20px) 25px 30px;
}

/* ============================================
   CARDS
============================================ */
.card-stat {
    border-left: 5px solid var(--morado);
    background: white;
    padding: 15px;
    border-radius: 8px;
}

.card h6 {
    font-weight: 600;
    margin-bottom: 10px;
}

/* ============================================
   TÍTULOS
============================================ */
.titulo-seccion {
    font-weight: 700;
    color: var(--morado);
}

/* ============================================
   GRÁFICAS
============================================ */
.chart-box {
    height: 260px;
}
.chart-box-sm {
    height: 200px;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-3">
    <span class="navbar-brand mb-0 text-white fw-semibold">Sistema de Gestión Escolar</span>

    <div class="text-white ms-auto">
        <i class="bi bi-person-circle"></i>
        <?= $usuario['nombre'] ?> (<?= $usuario['rol'] ?>)
        <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm ms-3">
            <i class="bi bi-box-arrow-right"></i> Salir
        </a>
    </div>
</nav>

<div class="sidebar">

    <nav>
        <div class="menu-title">Navegación</div>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=dashboard_administrativo"><i class="bi bi-house"></i> Inicio</a>

        <div class="menu-title">Gestión</div>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=usuarios"><i class="bi bi-people-fill"></i> Gestión de Usuarios</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=consultaDocentes"><i class="bi bi-person-vcard"></i> Consulta de Docentes</a>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/asignacionesView.php"><i class="bi bi-diagram-3"></i> Asignaciones</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=consultaCalificaciones"><i class="bi bi-clipboard-data"></i> Consulta de Calificaciones</a>

        <div class="menu-title">Reportes</div>
        <a class="nav-link" href="<?= BASE_URL ?>app/views/reportes/alumnosGeneralView.php"><i class="bi bi-people"></i> Alumnos (General)</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=reporteAlumnosCarrera"><i class="bi bi-mortarboard"></i> Alumnos por Carrera</a>
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=calificacionesGrupo"><i class="bi bi-clipboard-check"></i> Calificaciones por Grupo</a>
    </nav>

    <div class="logout-item">
        <a class="nav-link" href="<?= BASE_URL ?>index.php?action=logout">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </a>
    </div>

    <footer class="sidebar-footer">
        © 2025 Sistema de Gestión Escolar
    </footer>

</div>


<!-- CONTENIDO -->
<div class="main">

    <h4 class="mt-4 mb-3 titulo-seccion">Estadísticas del Sistema</h4>

    <div class="row g-4">

        <!-- CHART 1 Alumnos Carrera -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary">Alumnos por Carrera</h6>
                <div class="chart-box"><canvas id="chartCarreras"></canvas></div>
            </div>
        </div>

        <!-- CHART 2 Grupos -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary">Alumnos por Grupo</h6>
                <div class="chart-box"><canvas id="chartGrupos"></canvas></div>
            </div>
        </div>

        <!-- CHART 3 Materias -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary">Materias más asignadas</h6>
                <div class="chart-box"><canvas id="chartMaterias"></canvas></div>
            </div>
        </div>

        <!-- CHART 4 Docentes -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary">Docentes con más asignaciones</h6>
                <div class="chart-box"><canvas id="chartDocentes"></canvas></div>
            </div>
        </div>

        <!-- CHART 5 Inscritos -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary">% Inscritos</h6>
                <div class="chart-box-sm"><canvas id="chartInscritos"></canvas></div>
            </div>
        </div>

        <!-- CHART 6 Género -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary">Género del alumnado</h6>
                <div class="chart-box-sm"><canvas id="chartGenero"></canvas></div>
            </div>
        </div>

    </div>

</div>

<!-- SCRIPTS -->
<script>
const colors = {
    morado: "#4C0ECC",
    naranja: "#FFA726",
    amarillo: "#FFEB3B",
    gris: "#9E9E9E"
};

/* ===== 1. Alumnos por Carrera ===== */
new Chart(document.getElementById("chartCarreras"), {
    type: "bar",
    data: {
        labels: [
            <?php while($row = $alumnosCarrera->fetch_assoc()) echo "'".$row['nombreCarrera']."'," ?>
        ],
        datasets: [{
            label: "Alumnos",
            data: [
                <?php $alumnosCarrera->data_seek(0);
                while($row = $alumnosCarrera->fetch_assoc()) echo $row['total'].","; ?>
            ],
            backgroundColor: colors.morado
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
/* ===== 2. Alumnos por Grupo ===== */
new Chart(document.getElementById("chartGrupos"), {
    type: "bar",
    data: {
        labels: [
            <?php while($row = $alumnosGrupo->fetch_assoc()) echo "'".$row['nombreGrupo']."'," ?>
        ],
        datasets: [{
            label: "Alumnos",
            data: [
                <?php $alumnosGrupo->data_seek(0);
                while($row = $alumnosGrupo->fetch_assoc()) echo $row['total'].","; ?>
            ],
            backgroundColor: colors.naranja
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
/* ===== 3. Materias más asignadas ===== */
new Chart(document.getElementById("chartMaterias"), {
    type: "bar",
    data: {
        labels: [
            <?php while($row = $materiasRanking->fetch_assoc()) echo "'".$row['nombreMateria']."'," ?>
        ],
        datasets: [{
            label: "Asignaciones",
            data: [
                <?php $materiasRanking->data_seek(0);
                while($row = $materiasRanking->fetch_assoc()) echo $row['total'].","; ?>
            ],
            backgroundColor: colors.morado
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
/* ===== 4. Docentes con más asignaciones ===== */
new Chart(document.getElementById("chartDocentes"), {
    type: "bar",
    data: {
        labels: [
            <?php while($row = $docentesRanking->fetch_assoc()) echo "'".$row['docente']."'," ?>
        ],
        datasets: [{
            label: "Asignaciones",
            data: [
                <?php $docentesRanking->data_seek(0);
                while($row = $docentesRanking->fetch_assoc()) echo $row['total'].","; ?>
            ],
            backgroundColor: colors.amarillo
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
/* ===== 5. Inscritos ===== */
new Chart(document.getElementById("chartInscritos"), {
    type: "doughnut",
    data: {
        labels: ['Inscritos','No inscritos'],
        datasets: [{
            label: "% del total",
            data: [
                <?= $inscritosVsTotales['inscritos'] ?>,
                <?= $inscritosVsTotales['totalAlumnos'] - $inscritosVsTotales['inscritos'] ?>
            ],
            backgroundColor: [colors.naranja, colors.gris]
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
/* ===== 6. Género ===== */
new Chart(document.getElementById("chartGenero"), {
    type: "pie",
    data: {
        labels: [
            <?php while($row = $generoAlumnos->fetch_assoc()) echo "'".$row['sexo']."'," ?>
        ],
        datasets: [{
            label: "Alumnos",
            data: [
                <?php $generoAlumnos->data_seek(0);
                while($row = $generoAlumnos->fetch_assoc()) echo $row['total'].","; ?>
            ],
            backgroundColor: [colors.morado, colors.amarillo]
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

</script>

</body>
</html>

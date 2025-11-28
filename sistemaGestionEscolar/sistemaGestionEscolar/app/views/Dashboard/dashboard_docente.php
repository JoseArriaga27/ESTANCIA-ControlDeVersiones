<?php
if (!isset($_SESSION)) session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'];
$rolUsuario    = $_SESSION['usuario']['rol'];

if ($rolUsuario !== "Docente") {
    header("Location: ../../../index.php?action=login");
    exit;
}
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

</style>

</head>
<body>

<!-- ========================================================= -->
<!-- SIDEBAR DEL DOCENTE -->
<!-- ========================================================= -->
<div class="sidebar">

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

    <p class="menu-title">Cuenta</p>

    <a href="<?= BASE_URL ?>index.php?action=logout">
        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
    </a>

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

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow card-mini p-3">
                    <h5 class="fw-bold">Mis grupos</h5>
                    <p class="text-muted">Consultar los grupos asignados</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow card-mini p-3">
                    <h5 class="fw-bold">Mis materias</h5>
                    <p class="text-muted">Ver materias asignadas</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow card-mini p-3">
                    <h5 class="fw-bold">Captura de calificaciones</h5>
                    <p class="text-muted">Registrar calificaciones de tus alumnos</p>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>

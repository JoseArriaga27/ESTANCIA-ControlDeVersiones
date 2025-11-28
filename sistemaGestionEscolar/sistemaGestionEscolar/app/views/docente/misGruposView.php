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
<title>Mis Grupos | Docente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --color-principal: #06402B;
    --color-hover: #075238;
    --fondo: #f4f6f9;
}
body { background: var(--fondo); display:flex; }

/* SIDEBAR */
.sidebar {
    width: 250px;
    background: var(--color-principal);
    color: white;
    min-height: 100vh;
    position: fixed;
}
.sidebar .brand {
    font-size: 1.3rem; font-weight: bold;
    padding:20px; text-align:center;
    border-bottom:2px solid rgba(255,255,255,0.15);
}
.sidebar a {
    color:white; text-decoration:none;
    padding:14px 20px; display:block;
    font-size:0.95rem; transition:0.2s;
}
.sidebar a:hover { background: var(--color-hover); }
.sidebar .menu-title {
    padding:15px 20px 5px; font-size:0.78rem;
    opacity:0.7; text-transform:uppercase;
}

/* CONTENIDO */
.content {
    margin-left:250px;
    width:calc(100% - 250px);
}
.navbar { background: var(--color-principal); }

/* TABLAS */
.card-header { background: var(--color-principal); color:white; }
.btn-refresh {
    background: var(--color-principal);
    color:white;
}
.btn-refresh:hover { background: var(--color-hover); }

</style>
</head>

<body>

<!-- SIDEBAR DEL DOCENTE -->
<div class="sidebar">
    <div class="brand"><i class="bi bi-mortarboard-fill me-2"></i> Docente</div>

    <p class="menu-title">Navegación</p>
    <a href="<?= BASE_URL ?>index.php?action=dashboard_docente"><i class="bi bi-house-door-fill me-2"></i> Inicio</a>

    <p class="menu-title">Mis actividades</p>
    <a href="<?= BASE_URL ?>index.php?action=misGrupos"><i class="bi bi-people-fill me-2"></i> Mis grupos</a>
    <a href="<?= BASE_URL ?>index.php?action=misMateriasDocente"><i class="bi bi-journal-text me-2"></i> Mis materias</a>
    <a href="<?= BASE_URL ?>index.php?action=capturaCalificaciones"><i class="bi bi-pencil-square me-2"></i> Captura calificaciones</a>

    <p class="menu-title">Cuenta</p>
    <a href="<?= BASE_URL ?>index.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a>
</div>

<!-- CONTENIDO -->
<div class="content">

<nav class="navbar navbar-dark px-4">
    <span class="navbar-brand mb-0 h5 text-white">Sistema de Gestión Escolar</span>

    <div class="text-white d-flex align-items-center">
        <i class="bi bi-person-circle me-2"></i>
        <?= $nombreUsuario ?>
        <span class="text-white-50 ms-1">(Docente)</span>
    </div>
</nav>

<div class="container mt-4">

    <!-- TITULO Y REFRESCAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold" style="color:var(--color-principal);">
            <i class="bi bi-people-fill me-2"></i> Mis Grupos
        </h3>
    </div>

    <!-- LISTA DE GRUPOS -->
    <?php if ($grupos->num_rows == 0): ?>
        <div class="alert alert-warning text-center">No tienes grupos asignados.</div>

    <?php else: ?>
        <?php while ($g = $grupos->fetch_assoc()): ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong><?= $g['nombreGrupo'] ?></strong>
                    — <?= $g['nombreCarrera'] ?> 
                    (<?= $g['nombrePeriodo'] ?>)
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Matrícula</th>
                                <th>Alumno</th>
                            </tr>
                        </thead>

                        <tbody id="alumnosGrupo<?= $g['idGrupo'] ?>">
                            <tr><td colspan="3">Cargando alumnos...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
            fetch("<?= BASE_URL ?>index.php?action=ajaxAlumnosGrupo&idGrupo=<?= $g['idGrupo'] ?>")
                .then(res => res.json())
                .then(data => {
                    let tbody = document.getElementById("alumnosGrupo<?= $g['idGrupo'] ?>");
                    tbody.innerHTML = "";
                    if (data.length === 0) {
                        tbody.innerHTML = "<tr><td colspan='3'>Sin alumnos</td></tr>";
                    }
                    data.forEach(a => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${a.matricula}</td>
                                <td>${a.nombreCompleto}</td>
                            </tr>`;
                    });
                });
            </script>

        <?php endwhile; ?>
    <?php endif; ?>

</div>

</div>

</body>
</html>

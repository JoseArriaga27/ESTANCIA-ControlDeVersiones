<?php
if (!isset($_SESSION)) session_start();

$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario    = $_SESSION['usuario']['rol'] ?? '';

if ($rolUsuario !== 'Administrativo' && $rolUsuario !== 'Administrador') {
    header("Location: ../../index.php?action=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Consulta de Docentes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* COLOR INSTITUCIONAL DEL ADMINISTRATIVO */
:root {
    --primary: #320B86;  /* Morado */
    --primary-dark: #320B86;
}

body {
    background: #f4f5fa;
}

/* NAVBAR */
.navbar {
    background: var(--primary);
}

/* BOTÓN REGRESAR */
.btn-regresar {
    background: var(--primary);
    color: white;
    border-radius: 6px;
}
.btn-regresar:hover {
    background: var(--primary-dark);
    color: #fff;
}

/* CARD HEADER */
.card-header {
    background: var(--primary);
    color: white;
    font-weight: 600;
}

.table thead {
    background: var(--primary);
    color: white;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-4">
  <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>

  <div class="text-white d-flex align-items-center">
      <i class="bi bi-person-circle me-2"></i>
      <?= $nombreUsuario ?>
      <span class="text-white-50 ms-1">(<?= $rolUsuario ?>)</span>

      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm ms-3">
        <i class="bi bi-box-arrow-right"></i> Salir
      </a>
  </div>
</nav>

<div class="container mt-4">

    <!-- TÍTULO + BOTÓN REGRESAR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: var(--primary);">
            <i class="bi bi-person-workspace me-2"></i> Consulta de Docentes
        </h3>

        <a href="<?= BASE_URL ?>index.php?action=dashboard_administrativo" class="btn btn-regresar px-4">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <!-- BUSCADOR -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">Buscar docente</div>
        <div class="card-body">
            <input type="text" id="buscarDocente" class="form-control" placeholder="Buscar por nombre o correo...">
        </div>
    </div>

    <!-- TABLA -->
    <div class="card shadow-sm">
        <div class="card-header">Listado de docentes</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="tablaDocentes">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php while($d = $docentes->fetch_assoc()): ?>
                        <tr>
                            <td><?= $d['idDocente'] ?></td>
                            <td><?= htmlspecialchars($d['nombreCompleto']) ?></td>
                            <td><?= htmlspecialchars($d['correo']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("buscarDocente");
    const tabla = document.getElementById("tablaDocentes").getElementsByTagName("tr");

    input.addEventListener("keyup", () => {
        const filtro = input.value.toLowerCase();

        for (let i = 1; i < tabla.length; i++) {
            let columnas = tabla[i].getElementsByTagName("td");
            let nombre = columnas[1].textContent.toLowerCase();
            let correo = columnas[2].textContent.toLowerCase();

            tabla[i].style.display = 
                (nombre.includes(filtro) || correo.includes(filtro)) ? "" : "none";
        }
    });
});
</script>

</body>
</html>

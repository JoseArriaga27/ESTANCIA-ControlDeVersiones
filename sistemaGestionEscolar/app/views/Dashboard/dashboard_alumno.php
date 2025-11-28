<?php
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: ../../../index.php?action=login"); exit; }
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
    :root { --primary: #6f42c1; --sidebar-width: 260px; }
    body { background: #f5f7fb; }
    .navbar { background-color: var(--primary) !important; }
    .main { padding: 1.25rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <div class="ms-auto">
      <span class="text-white me-3"><i class="bi bi-person-circle me-1"></i><?= $usuario['nombre'] ?> (<?= $usuario['rol'] ?>)</span>
      <a href="../../../index.php?action=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión</a>
    </div>
  </div>
</nav>

<main class="main">
  <div class="container">
    <h4 class="text-purple fw-semibold mb-4">Bienvenido, <?= $usuario['nombre'] ?></h4>
    <div class="card mb-3"><div class="card-header bg-light fw-semibold">Mis Materias</div>
      <div class="card-body"><p class="text-muted">Aquí se mostrarán tus materias inscritas.</p></div></div>
    <div class="card"><div class="card-header bg-light fw-semibold">Mis Calificaciones</div>
      <div class="card-body"><p class="text-muted">Aquí podrás consultar tus calificaciones.</p></div></div>
  </div>
</main>
</body>
</html>

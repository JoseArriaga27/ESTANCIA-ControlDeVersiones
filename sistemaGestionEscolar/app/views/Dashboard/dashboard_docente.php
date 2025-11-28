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
  <title>Docente — Sistema de Gestión Escolar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    :root { --primary: #198754; --sidebar-width: 260px; }
    body { background: #f5f7fb; }
    .navbar { background-color: var(--primary) !important; }
    .sidebar { position: fixed; top: 0; bottom: 0; left: 0; width: var(--sidebar-width); background: #fff; border-right: 1px solid #e5e7eb; padding-top: 64px; }
    .brand { position: fixed; top: 0; left: 0; height: 64px; width: var(--sidebar-width); display:flex; align-items:center; justify-content:center; gap:.5rem; background: #fff; border-right: 1px solid #e5e7eb; }
    .brand .dot { width: 10px; height: 10px; background: var(--primary); border-radius: 50%; display:inline-block; }
    .main { margin-left: var(--sidebar-width); padding: 1.25rem; }
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

<div class="brand"><span class="dot"></span><strong>Profesor Asignado</strong></div>
<aside class="sidebar p-3">
  <ul class="nav flex-column gap-1">
    <li class="nav-item"><a class="nav-link" href="#" data-target="materias"><i class="bi bi-journal-text"></i> Materias Asignadas</a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-target="alumnos"><i class="bi bi-people"></i> Lista de Alumnos</a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-target="calificaciones"><i class="bi bi-pencil-square"></i> Captura de Calificaciones</a></li>
  </ul>
  <footer class="sidebar-footer">© 2025 Sistema de Gestión Escolar</footer>
</aside>

<main class="main">
  <div class="container-fluid">
    <h4 class="fw-semibold mb-3 text-success">Bienvenido, <?= $usuario['nombre'] ?></h4>
    <p class="text-muted">Aquí puedes gestionar tus grupos, materias y calificaciones.</p>
  </div>
</main>
</body>
</html>

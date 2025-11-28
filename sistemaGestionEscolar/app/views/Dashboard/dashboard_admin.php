<?php
session_start();
if (!isset($_SESSION['usuario'])) { 
  header("Location: ../../../index.php?action=login"); 
  exit; 
}
$usuario = $_SESSION['usuario'];
require_once __DIR__ . '/../../../config/config.php'; // para usar BASE_URL
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
    :root { --primary: #0d6efd; --sidebar-width: 260px; }
    body { background: #f5f7fb; }
    .navbar { background-color: var(--primary) !important; }
    .sidebar {
      position: fixed; top: 0; bottom: 0; left: 0; width: var(--sidebar-width);
      background: #fff; border-right: 1px solid #e5e7eb; padding-top: 64px;
    }
    .brand { position: fixed; top: 0; left: 0; height: 64px; width: var(--sidebar-width);
      display:flex; align-items:center; justify-content:center; gap:.5rem;
      background: #fff; border-right: 1px solid #e5e7eb; }
    .brand .dot { width: 10px; height: 10px; background: var(--primary); border-radius: 50%; display:inline-block; }
    .main { margin-left: var(--sidebar-width); padding: 1.25rem; }
    .nav-link { color: #374151; border-radius: .5rem; }
    .nav-link:hover, .nav-link.active { background: rgba(13,110,253,.08); color: var(--primary); }
    footer.sidebar-footer { position:absolute; bottom: 0; left: 0; right: 0; padding: .75rem; font-size:.85rem; color:#6b7280; text-align:center; border-top:1px solid #e5e7eb; background:#fff; }
    .collapink { padding-left: 2rem; }
  </style>
</head>
<body>

<!-- ===================================== -->
<!-- Barra superior -->
<!-- ===================================== -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <div class="ms-auto">
      <span class="text-white me-3">
        <i class="bi bi-person-circle me-1"></i>
        <?= htmlspecialchars($usuario['nombre']) ?> (<?= htmlspecialchars($usuario['rol']) ?>)
      </span>
      <a href="../../../index.php?action=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<!-- ===================================== -->
<!-- Sidebar -->
<!-- ===================================== -->
<div class="brand"><span class="dot"></span><strong>Administrador</strong></div>
<aside class="sidebar p-3">
  <ul class="nav flex-column gap-1">
    <li class="nav-item">
      <a class="nav-link active d-flex align-items-center gap-2" href="#" data-target="usuarios">
        <i class="bi bi-people"></i><span>Gestiones Generales</span>
      </a>
    </li>

    <!-- Menú desplegable de Materias -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#materiasMenu" role="button" aria-expanded="false" aria-controls="materiasMenu">
        <i class="bi bi-journal-bookmark"></i><span>Materias</span>
      </a>
      <div class="collapse ps-2" id="materiasMenu">
        <ul class="list-unstyled">
          <li>
            <a href="<?= BASE_URL ?>app/views/materiasView.php" class="nav-link d-flex align-items-center gap-2">
              <i class="bi bi-journal-text"></i> Gestión de Materias
            </a>
          </li>
          <li>
            <a href="<?= BASE_URL ?>app/views/periodosView.php" class="nav-link d-flex align-items-center gap-2">
              <i class="bi bi-calendar3"></i> Gestión de Periodos Escolares
            </a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2" href="#" data-target="reportes">
        <i class="bi bi-graph-up"></i><span>Reportes</span>
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
              <div class="fs-4">128</div>
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
              <div class="fs-4">32</div>
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
              <div class="fs-4">18</div>
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

    <!-- Bloque: Gestión de Usuarios -->
    <section id="usuarios" class="mt-3">
      <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
          <span>Gestión de Usuarios</span>
          <a href="../../../index.php?action=usuarios" class="btn btn-primary btn-sm">
            <i class="bi bi-people-fill me-1"></i> Ir al módulo
          </a>
        </div>
        <div class="card-body">
          <p class="text-muted mb-0">
            Desde aquí puedes acceder al módulo completo de administración de usuarios del sistema.
          </p>
        </div>
      </div>
    </section>

    <!-- Bloque: Gestión de Materias -->
    <section id="materias" class="mt-3">
      <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
          <span>Gestión de Materias</span>
          <a href="<?= BASE_URL ?>app/views/materiasView.php" class="btn btn-primary btn-sm">
            <i class="bi bi-journal-text me-1"></i> Ir al módulo
          </a>
        </div>
        <div class="card-body">
          <p class="text-muted mb-0">
            Desde aquí puedes acceder al módulo completo de administración de materias del sistema.
          </p>
        </div>
      </div>
    </section>

    <!-- Bloque: Gestión de Periodos Escolares -->
    <section id="periodos" class="mt-3">
      <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
          <span>Gestión de Periodos Escolares</span>
          <a href="<?= BASE_URL ?>app/views/periodosView.php" class="btn btn-primary btn-sm">
            <i class="bi bi-calendar3 me-1"></i> Ir al módulo
          </a>
        </div>
        <div class="card-body">
          <p class="text-muted mb-0">
            Desde aquí puedes acceder al módulo completo de administración de periodos escolares del sistema, 
            donde podrás registrar, actualizar o eliminar periodos académicos.
          </p>
        </div>
      </div>
    </section>
    <!-- ===================================== -->
<!-- Bloque: Gestión de Grupos -->
<!-- ===================================== -->
<section id="grupos" class="mt-3">
  <div class="card shadow-sm">
    <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
      <span>Gestión de Grupos</span>
      <a href="<?= BASE_URL ?>app/views/gruposView.php" class="btn btn-primary btn-sm">
        <i class="bi bi-collection me-1"></i> Ir al módulo
      </a>
    </div>
    <div class="card-body">
      <p class="text-muted mb-0">
        Desde aquí puedes acceder al módulo completo de administración de grupos del sistema,
        donde podrás registrar, actualizar o eliminar grupos académicos.
      </p>
    </div>
  </div>
</section>

<!-- ===================================== -->
<!-- Bloque: Gestión de Asignaciones -->
<!-- ===================================== -->
<section id="asignaciones" class="mt-3">
  <div class="card shadow-sm">
    <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
      <span>Asignación de Materias a Docentes</span>
      <a href="<?= BASE_URL ?>app/views/asignacionesView.php" class="btn btn-primary btn-sm">
        <i class="bi bi-diagram-3 me-1"></i> Ir al módulo
      </a>
    </div>
    <div class="card-body">
      <p class="text-muted mb-0">
        Desde aquí puedes acceder al módulo completo de administración de asignaciones del sistema,
        donde podrás vincular docentes con materias y grupos según el periodo correspondiente.
      </p>
    </div>
  </div>
</section>
 <!-- Bloque: Gestión de Carreras -->
    <!-- ===================================== -->
    <section id="carreras" class="mt-3">
      <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
          <span>Gestión de Carreras</span>
          <a href="<?= BASE_URL ?>app/views/carrerasView.php" class="btn btn-primary btn-sm">
            <i class="bi bi-mortarboard-fill me-1"></i> Ir al módulo
          </a>
        </div>
        <div class="card-body">
          <p class="text-muted mb-0">
            Desde aquí puedes acceder al módulo completo de administración de carreras, donde podrás registrar,
            editar o eliminar carreras universitarias disponibles en el sistema.
          </p>
        </div>
      </div>
    </section>

    <!-- ===================================== -->
<!-- Bloque: Gestión de Alumnos e Inscripciones -->
<!-- ===================================== -->
<section id="alumnos" class="mt-3">
  <div class="card shadow-sm">
    <div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
      <span>Asignación de alumnos a un grupo</span>
      <a href="<?= BASE_URL ?>app/views/alumnosView.php" class="btn btn-primary btn-sm">
        <i class="bi bi-person-lines-fill me-1"></i> Ir al módulo
      </a>
    </div>
    <div class="card-body">
      <p class="text-muted mb-0">
        Desde aquí puedes registrar nuevos alumnos, vincularlos a carreras e inscribirlos a grupos del sistema académico.
      </p>
    </div>
  </div>
</section>


  </div> <!-- Cierra container-fluid -->
</main>

</body>
</html>

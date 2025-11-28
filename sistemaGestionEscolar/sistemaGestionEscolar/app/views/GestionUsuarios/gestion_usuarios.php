<?php
require_once __DIR__ . '/../../../config/config.php';

if (!isset($_SESSION)) session_start();

// 🔒 PERMISOS SOLO PARA ADMINISTRADOR Y ADMINISTRATIVO
if (!in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])) {
    header("Location: ../../../index.php?action=login");
    exit;
}

$nombreUsuario = $_SESSION['usuario']['nombre'];
$rolUsuario    = $_SESSION['usuario']['rol'];
/* ============================================================
   COLORES SEGÚN EL ROL
   Administrador → Azul
   Administrativo → Morado
============================================================ */

$estilos = [];

if ($rolUsuario === 'Administrador') {
    // AZUL INSTITUCIONAL
    $estilos['principal']        = "#0A2A43";
    $estilos['principal_hover']  = "#071D30";
}
else {
    // MORADO ADMINISTRATIVO
    $estilos['principal']        = "#320B86";
    $estilos['principal_hover']  = "#250769";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Usuarios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ==============================
   COLORES INSTITUCIONALES MORADO ADMINISTRATIVO
================================= */

:root {
    --color-principal: <?= $estilos['principal'] ?>;
    --color-principal-hover: <?= $estilos['principal_hover'] ?>;
    --fondo: #f4f4f9;
}


body { background: var(--fondo); }

/* NAVBAR */
.navbar {
    background: var(--color-principal) !important;
}

/* FOOTER */
footer {
    background: var(--color-principal);
    color:white;
    padding:10px 0;
    text-align:center;
    font-weight:500;
    margin-top:40px;
}

/* CARD HEADERS */
.card-header {
    background: var(--color-principal);
    color:white;
    font-weight:600;
}

/* TÍTULO */
.titulo-pagina {
    color: var(--color-principal);
    font-weight:700;
}

/* BOTÓN REGRESAR */
.btn-regresar {
    background: var(--color-principal);
    color:white;
    border-radius:6px;
}
.btn-regresar:hover {
    background: var(--color-principal-hover);
    color:white;
}

/* BOTONES editar/eliminar */
.btn-warning { background:#E3A008; border:none; }
.btn-danger { background:#B91C1C; border:none; }

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>

    <div class="text-white fw-semibold">
      <i class="bi bi-person-circle"></i>
      <?= $nombreUsuario ?>
      <span class="text-white-50">(<?= $rolUsuario ?>)</span>

      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm ms-3">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4 mb-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="titulo-pagina m-0">
        <i class="bi bi-people-fill me-2"></i> Gestión de Usuarios
    </h3>

    <a href="<?= BASE_URL ?>index.php?action=dashboard<?= $rolUsuario === 'Administrador' ? '' : '_administrativo' ?>" 
   class="btn btn-regresar px-4">
    <i class="bi bi-arrow-left"></i> Regresar
</a>

  </div>


  <?php
  $modo = isset($_GET['edit']) ? 'editar' : 'insertar';
  $usuarioEditar = null;

  if (isset($_GET['edit'])) {
      $idEditar = $_GET['edit'];
      $res = $connection->query("
        SELECT u.*, a.idCarrera 
        FROM usuarios u 
        LEFT JOIN alumnos a ON u.idUsuario = a.idUsuario 
        WHERE u.idUsuario = $idEditar
      ");
      $usuarioEditar = $res->fetch_assoc();
  }
  ?>

  <!-- FORMULARIO -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold"><?= $modo == 'editar' ? 'Editar Usuario' : 'Registrar Usuario' ?></div>
    <div class="card-body">
      <form method="POST" class="row g-3">
        <?php if ($modo == 'editar'): ?>
          <input type="hidden" name="idUsuario" value="<?= $usuarioEditar['idUsuario'] ?>">
        <?php endif; ?>

        <div class="col-md-3">
          <label class="form-label">Nombre(s)</label>
          <input type="text" name="nombres" class="form-control" required
                 value="<?= $usuarioEditar['nombres'] ?? '' ?>" placeholder="Ej. José Manuel">
        </div>
        <div class="col-md-3">
          <label class="form-label">Apellido Paterno</label>
          <input type="text" name="apePaterno" class="form-control" required
                 value="<?= $usuarioEditar['apePaterno'] ?? '' ?>" placeholder="Ej. Arriaga">
        </div>
        <div class="col-md-3">
          <label class="form-label">Apellido Materno</label>
          <input type="text" name="apeMaterno" class="form-control"
                 value="<?= $usuarioEditar['apeMaterno'] ?? '' ?>" placeholder="Ej. Monroy">
        </div>
        <div class="col-md-3">
          <label class="form-label">Sexo</label>
          <select name="sexo" class="form-select" required>
            <option value="" disabled <?= !isset($usuarioEditar) ? 'selected' : '' ?>>Seleccionar...</option>
            <option <?= (isset($usuarioEditar) && $usuarioEditar['sexo']=='Masculino')?'selected':'' ?>>Masculino</option>
            <option <?= (isset($usuarioEditar) && $usuarioEditar['sexo']=='Femenino')?'selected':'' ?>>Femenino</option>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Fecha de nacimiento</label>
          <input type="date" name="fechaNacimiento" class="form-control"
                 value="<?= $usuarioEditar['fechaNacimiento'] ?? '' ?>" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Matrícula</label>
          <input type="text" name="matricula" class="form-control" required
                 value="<?= $usuarioEditar['matricula'] ?? '' ?>" placeholder="Ej. MCEO230034">
        </div>
        <div class="col-md-3">
          <label class="form-label">Correo institucional</label>
          <input type="email" name="correo" class="form-control" required
                 value="<?= $usuarioEditar['correo'] ?? '' ?>" placeholder="correo@escuela.edu.mx">
        </div>
        <div class="col-md-2">
          <label class="form-label">Rol</label>
          <select name="rol" class="form-select" required>
            <option value="" disabled <?= !isset($usuarioEditar) ? 'selected' : '' ?>>Seleccionar...</option>
            <option <?= (isset($usuarioEditar) && $usuarioEditar['rol']=='Administrador')?'selected':'' ?>>Administrador</option>
            <option <?= (isset($usuarioEditar) && $usuarioEditar['rol']=='Docente')?'selected':'' ?>>Docente</option>
            <option <?= (isset($usuarioEditar) && $usuarioEditar['rol']=='Alumno')?'selected':'' ?>>Alumno</option>
            <option <?= (isset($usuarioEditar) && $usuarioEditar['rol']=='Administrativo')?'selected':'' ?>>Administrativo</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Contraseña <?= $modo=='editar'?'(opcional)':'' ?></label>
          <input type="password" name="contrasena" class="form-control"
                 placeholder="<?= $modo=='editar'?'Nueva contraseña':'' ?>">
        </div>

        <!-- Campo Carrera (solo si es Alumno) -->
        <div class="col-md-3" id="carreraContainer" style="display: none;">
          <label class="form-label">Carrera</label>
          <select name="idCarrera" class="form-select">
            <option value="">Seleccionar carrera...</option>
            <?php
              $resCarreras = $connection->query("SELECT * FROM carreras");
              while ($c = $resCarreras->fetch_assoc()):
            ?>
              <option value="<?= $c['idCarrera'] ?>"
                <?= (isset($usuarioEditar) && isset($usuarioEditar['idCarrera']) && $usuarioEditar['idCarrera'] == $c['idCarrera']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombreCarrera']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-12 text-end">
          <?php if ($modo == 'editar'): ?>
            <button type="submit" name="actualizar" class="btn btn-warning px-4">Actualizar</button>
            <a href="<?= BASE_URL ?>index.php?action=usuarios" class="btn btn-secondary px-4">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="insertar" class="btn btn-success px-4">Guardar</button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- TABLA DE USUARIOS -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Usuarios Registrados</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Nombre completo</th>
            <th>Sexo</th>
            <th>Fecha Nac.</th>
            <th>Matrícula</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php while ($u = $usuarios->fetch_assoc()): ?>
            <tr>
              <td><?= $u['idUsuario'] ?></td>
              <td><?= htmlspecialchars($u['nombres'].' '.$u['apePaterno'].' '.$u['apeMaterno']) ?></td>
              <td><?= htmlspecialchars($u['sexo']) ?></td>
              <td><?= htmlspecialchars($u['fechaNacimiento']) ?></td>
              <td><?= htmlspecialchars($u['matricula']) ?></td>
              <td><?= htmlspecialchars($u['correo']) ?></td>
              <td><?= htmlspecialchars($u['rol']) ?></td>
              <td>
                <a href="<?= BASE_URL ?>index.php?action=usuarios&edit=<?= $u['idUsuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="<?= BASE_URL ?>index.php?action=usuarios&delete=<?= $u['idUsuario'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Seguro que deseas eliminar al usuario <?= htmlspecialchars($u['nombres'].' '.$u['apePaterno']) ?>?');">
                   Eliminar
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal de mensajes -->
<?php if (!empty($mensaje)): ?>
<div class="modal fade show" id="modalMensaje" tabindex="-1" style="display:block;" aria-modal="true" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content border-<?= $tipo=='success'?'success':'danger' ?>">
      <div class="modal-header bg-<?= $tipo ?> text-white">
        <h5 class="modal-title"><?= $tipo=='success'?'Éxito':'Error' ?></h5>
      </div>
      <div class="modal-body"><p><?= htmlspecialchars($mensaje) ?></p></div>
      <div class="modal-footer">
        <a href="<?= BASE_URL ?>index.php?action=usuarios" class="btn btn-<?= $tipo ?>">Cerrar</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>


<footer>© 2025 Sistema de Gestión Escolar</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const rolSelect = document.querySelector('select[name="rol"]');
  const carreraContainer = document.getElementById('carreraContainer');
  function toggleCarreraField() {
    carreraContainer.style.display = (rolSelect.value === 'Alumno') ? 'block' : 'none';
  }
  rolSelect.addEventListener('change', toggleCarreraField);
  toggleCarreraField();
});
</script>

</body>
</html>

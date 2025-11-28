<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../app/models/alumnoModel.php';

session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'Sin rol';

$model = new AlumnoModel($connection);
$alumnos = $model->obtenerAlumnos(); // todos los alumnos
$carreras = $model->obtenerCarreras();
$usuarios = $model->obtenerUsuariosDisponibles(); // alumnos no inscritos
$grupos = $model->obtenerGrupos();
$inscripciones = $model->obtenerInscripciones();

$mensaje = $_GET['msg'] ?? '';
$tipo = $_GET['type'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Alumnos e Inscripciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background-color: #f8f9fa; }
    .navbar { background-color: #007bff; }
    .card-header { background-color: #5a5a5a; color: #fff; }
    footer { background-color: #007bff; color: white; text-align: center; padding: 10px 0; margin-top: 40px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <span class="navbar-brand h5 mb-0 text-white">Sistema de Gestión Escolar</span>
    <div class="d-flex align-items-center">
      <div class="text-white fw-semibold me-3">
        <i class="bi bi-person-circle"></i>
        <?= htmlspecialchars($nombreUsuario) ?>
        <span class="text-white-50">(<?= htmlspecialchars($rolUsuario) ?>)</span>
      </div>
      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4 mb-5">
  <h3 class="text-center mb-4 fw-semibold">Asignación de alumnos a un grupo</h3>

  <!-- Asignación -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold">Asignación de alumnos a un grupo</div>
    <div class="card-body">
      <form method="POST" action="../controllers/alumnoController.php" class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Alumno</label>
          <select name="idAlumno" id="idAlumno" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php $usuarios->data_seek(0); while ($a = $usuarios->fetch_assoc()): ?>
              <option value="<?= $a['idAlumno'] ?>" 
                      data-carrera="<?= htmlspecialchars($a['nombreCarrera']) ?>" 
                      data-idcarrera="<?= htmlspecialchars($a['idCarrera'] ?? '') ?>">
                <?= htmlspecialchars($a['nombreCompleto']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Carrera</label>
          <input type="text" id="carreraAlumno" class="form-control" readonly placeholder="Selecciona un alumno">
        </div>

        <div class="col-md-4">
          <label class="form-label">Grupo</label>
          <select name="idGrupo" id="idGrupo" class="form-select" required>
            <option value="">Seleccionar...</option>
          </select>
        </div>

        <div class="col-md-2 text-end">
          <button type="submit" name="inscribir" class="btn btn-primary mt-4 px-4">Inscribir</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Lista de alumnos -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Alumnos Registrados</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Matrícula</th>
            <th>Carrera</th>
            <th>Correo</th>
          </tr>
        </thead>
        <tbody>
          <?php $alumnos->data_seek(0); while ($a = $alumnos->fetch_assoc()): ?>
          <tr>
            <td><?= $a['idAlumno'] ?></td>
            <td><?= htmlspecialchars($a['nombreCompleto']) ?></td>
            <td><?= htmlspecialchars($a['matricula']) ?></td>
            <td><?= htmlspecialchars($a['nombreCarrera']) ?></td>
            <td><?= htmlspecialchars($a['correo']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Inscripciones -->
  <div class="card shadow-sm mt-4">
    <div class="card-header fw-semibold">Inscripciones Realizadas</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0 text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Alumno</th>
            <th>Grupo</th>
            <th>Carrera</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $currentGroup = null;
            while ($i = $inscripciones->fetch_assoc()):
              if ($currentGroup !== $i['nombreGrupo']):
                if ($currentGroup !== null) echo '</tbody>';
                echo "<thead class='table-secondary text-center'><tr><th colspan='5'>".$i['nombreGrupo']." — ".$i['nombreCarrera']."</th></tr></thead><tbody>";
                $currentGroup = $i['nombreGrupo'];
              endif;
          ?>
          <tr>
            <td><?= $i['idInscripcion'] ?></td>
            <td><?= htmlspecialchars($i['alumno']) ?></td>
            <td><?= htmlspecialchars($i['nombreGrupo']) ?></td>
            <td><?= htmlspecialchars($i['nombreCarrera']) ?></td>
            <td><?= htmlspecialchars($i['fechaInscripcion']) ?></td>
          </tr>
          <?php endwhile; ?>
          <?php if ($currentGroup !== null) echo '</tbody>'; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center my-4">
    <a href="../views/Dashboard/dashboard_admin.php" class="btn btn-primary btn-lg">
      <i class="bi bi-arrow-left-circle"></i> Regresar al inicio
    </a>
  </div>
</div>

<footer>© 2025 Sistema de Gestión Escolar</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const alumnoSelect = document.getElementById('idAlumno');
  const carreraInput = document.getElementById('carreraAlumno');
  const grupoSelect = document.getElementById('idGrupo');

  alumnoSelect.addEventListener('change', () => {
    const selectedOption = alumnoSelect.options[alumnoSelect.selectedIndex];
    const carrera = selectedOption.getAttribute('data-carrera');
    const idCarrera = selectedOption.getAttribute('data-idcarrera');
    carreraInput.value = carrera || '';
    grupoSelect.innerHTML = '<option value="">Cargando grupos...</option>';

    if (!idCarrera) {
      grupoSelect.innerHTML = '<option value="">Selecciona un alumno válido</option>';
      return;
    }

    fetch(`../controllers/alumnoController.php?ajax_grupos=1&idCarrera=${idCarrera}`)
      .then(res => {
        if (!res.ok) throw new Error("Error HTTP");
        return res.json();
      })
      .then(data => {
        grupoSelect.innerHTML = '<option value="">Seleccionar...</option>';
        if (data.length === 0) {
          grupoSelect.innerHTML = '<option value="">No hay grupos disponibles para esta carrera</option>';
        } else {
          data.forEach(g => {
            grupoSelect.innerHTML += `<option value="${g.idGrupo}">${g.nombreGrupo} — ${g.nombreCarrera}</option>`;
          });
        }
      })
      .catch(() => {
        grupoSelect.innerHTML = '<option value="">Error al cargar grupos</option>';
      });
  });
});
</script>
</body>
</html>

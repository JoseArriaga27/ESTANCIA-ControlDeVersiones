<?php
    require_once __DIR__ . '/../../../config/config.php';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #007bff; }
        footer { background-color: #007bff; color: white; padding: 10px 0; text-align: center; }
        .card-header { background-color: #5a5a5a; color: white; }
        .perfil-icon { color: white; text-decoration: none; font-size: 0.9rem; }
        .perfil-icon:hover { text-decoration: underline; }
    </style>
</head>
<body>

<!-- Barra superior -->
<?php
session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'Sin rol';
?>
<nav class="navbar navbar-expand-lg navbar-dark px-3" style="background-color: #007bff;">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    
    <span class="navbar-brand h5 mb-0 text-white">
      Sistema de Gestión Escolar
    </span>

    <div class="d-flex align-items-center">
      <!-- Nombre del usuario -->
      <div class="text-white fw-semibold me-3">
        <i class="bi bi-person-circle"></i>
        <?= htmlspecialchars($nombreUsuario) ?> 
        <span class="text-white-50">(<?= htmlspecialchars($rolUsuario) ?>)</span>
      </div>

      <!-- Botón cerrar sesión -->
      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4 mb-5">

    <h3 class="text-center mb-4 fw-semibold">Gestión de Usuarios</h3>

    <!-- REGISTRAR / EDITAR USUARIO -->
    <?php 
        $modo = isset($_GET['edit']) ? 'editar' : 'insertar';
        $usuarioEditar = null;

        if (isset($_GET['edit'])) {
            $idEditar = $_GET['edit'];
            $res = $connection->query("SELECT * FROM usuarios WHERE idUsuario = $idEditar");
            $usuarioEditar = $res->fetch_assoc();
        }
    ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-semibold"><?= $modo == 'editar' ? 'Editar Usuario' : 'Registrar Usuario' ?></div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <?php if ($modo == 'editar'): ?>
                    <input type="hidden" name="idUsuario" value="<?= $usuarioEditar['idUsuario'] ?>">
                <?php endif; ?>

                <div class="col-md-3">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" name="nombres" class="form-control" placeholder="Ej. José Manuel"
                        value="<?= $usuarioEditar['nombres'] ?? '' ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apePaterno" class="form-control" placeholder="Ej. Arriaga"
                        value="<?= $usuarioEditar['apePaterno'] ?? '' ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apeMaterno" class="form-control" placeholder="Ej. Monroy"
                        value="<?= $usuarioEditar['apeMaterno'] ?? '' ?>">
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
                    <input type="text" name="matricula" class="form-control" placeholder="Ej. MCEO230034"
                        value="<?= $usuarioEditar['matricula'] ?? '' ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Correo institucional</label>
                    <input type="email" name="correo" class="form-control" placeholder="correo@escuela.edu.mx"
                        value="<?= $usuarioEditar['correo'] ?? '' ?>" required>
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

                <div class="col-12 text-end">
                    <?php if ($modo == 'editar'): ?>
                        <button type="submit" name="actualizar" class="btn btn-warning px-4">Actualizar</button>
                        <a href="?" class="btn btn-secondary px-4">Cancelar</a>
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
                                class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar al usuario <?= htmlspecialchars($u['nombres'].' '.$u['apePaterno']) ?>?');">
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

<!-- MODAL DE MENSAJES -->
<?php if (!empty($mensaje)): ?>
<div class="modal fade show" id="modalMensaje" tabindex="-1" style="display:block;" aria-modal="true" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content border-<?= $tipo=='success'?'success':'danger' ?>">
      <div class="modal-header bg-<?= $tipo ?> text-white">
        <h5 class="modal-title"><?= $tipo=='success'?'Éxito':'Error' ?></h5>
      </div>
      <div class="modal-body">
        <p><?= htmlspecialchars($mensaje) ?></p>
      </div>
      <div class="modal-footer">
        <a href="<?= BASE_URL ?>index.php?action=usuarios" class="btn btn-<?= $tipo ?>">Cerrar</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- FOOTER -->
<footer>
    © 2025 Sistema de Gestión Escolar
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalConfirmarLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar a <strong id="nombreUsuario"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a id="btnEliminarConfirmado" href="#" class="btn btn-danger">Sí, eliminar</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalConfirmar = document.getElementById('modalConfirmar');
  modalConfirmar.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const idUsuario = button.getAttribute('data-id');
    const nombre = button.getAttribute('data-nombre');
    const nombreUsuario = modalConfirmar.querySelector('#nombreUsuario');
    const btnEliminar = modalConfirmar.querySelector('#btnEliminarConfirmado');
    nombreUsuario.textContent = nombre;
    btnEliminar.href = "<?= BASE_URL ?>index.php?action=usuarios&delete=" + idUsuario;
  });
});
</script>

</body>
</html>

<?php
include(__DIR__ . '/../../../config/db_connection.php');

// ====================================
// ELIMINAR USUARIO
// ====================================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $connection->query("DELETE FROM usuarios WHERE idUsuario = $id");
    echo "<script>alert('Usuario eliminado correctamente'); window.location='gestion_usuarios.php';</script>";
    exit;
}

// ====================================
// ACTUALIZAR USUARIO
// ====================================
if (isset($_POST['update'])) {
    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $matricula = $_POST['matricula'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $contrasena = $_POST['contrasena'];

    if (!empty($contrasena)) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE usuarios 
                      SET nombre='$nombre', matricula='$matricula', correo='$correo', rol='$rol', contrasena='$hash'
                      WHERE idUsuario=$idUsuario";
    } else {
        $sqlUpdate = "UPDATE usuarios 
                      SET nombre='$nombre', matricula='$matricula', correo='$correo', rol='$rol'
                      WHERE idUsuario=$idUsuario";
    }

    if ($connection->query($sqlUpdate)) {
        echo "<script>alert('Usuario actualizado correctamente'); window.location='gestion_usuarios.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error al actualizar');</script>";
    }
}

// ====================================
// INSERTAR NUEVO USUARIO
// ====================================
if (isset($_POST['insert'])) {
    $nombre = $_POST['nombre'];
    $matricula = $_POST['matricula'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $contrasena = $_POST['contrasena'];

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $sqlInsert = "INSERT INTO usuarios (nombre, matricula, correo, contrasena, rol)
                  VALUES ('$nombre', '$matricula', '$correo', '$hash', '$rol')";
    if ($connection->query($sqlInsert)) {
        echo "<script>alert('Usuario registrado correctamente'); window.location='gestion_usuarios.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error al registrar usuario');</script>";
    }
}

// ====================================
// FILTROS DE BÚSQUEDA
// ====================================
$where = "WHERE 1=1";
$rolFiltro = $_GET['rol'] ?? '';
$buscarNombre = $_GET['buscar'] ?? '';

if (!empty($rolFiltro)) {
    $where .= " AND rol = '$rolFiltro'";
}
if (!empty($buscarNombre)) {
    $where .= " AND nombre LIKE '%$buscarNombre%'";
}

// ====================================
// CONSULTA DE USUARIOS
// ====================================
$sql = "SELECT * FROM usuarios $where ORDER BY idUsuario DESC";
$result = $connection->query($sql);

// Si se presionó editar
$usuarioEditar = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $usuarioEditar = $connection->query("SELECT * FROM usuarios WHERE idUsuario=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Sistema de Gestión Escolar</a>

    <!-- Icono de perfil (lado derecho) -->
    <div class="ms-auto d-flex align-items-center">
      <i class="bi bi-person-circle fs-3 text-white me-2"></i>
      <span class="text-white fw-semibold">Perfil</span>
    </div>
  </div>
</nav>


  <div class="container my-4">
    <h2 class="mb-3 text-center">Gestión de Usuarios</h2>

    <!-- Formulario de registro / edición -->
    <div class="card mb-4">
      <div class="card-header bg-secondary text-white">
        <?= $usuarioEditar ? "Editar Usuario" : "Registrar Usuario" ?>
      </div>
      <div class="card-body">
        <form method="POST" action="">
          <?php if ($usuarioEditar): ?>
            <input type="hidden" name="idUsuario" value="<?= $usuarioEditar['idUsuario'] ?>">
          <?php endif; ?>

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Nombre completo</label>
              <input type="text" name="nombre" class="form-control"
                value="<?= $usuarioEditar['nombre'] ?? '' ?>" placeholder="Ej. José Arriaga" required>
            </div>

            <div class="col-md-2">
              <label class="form-label">Matrícula</label>
              <input type="text" name="matricula" class="form-control"
                value="<?= $usuarioEditar['matricula'] ?? '' ?>" placeholder="Ej. MCEO230034" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Correo institucional</label>
              <input type="email" name="correo" class="form-control"
                value="<?= $usuarioEditar['correo'] ?? '' ?>" placeholder="correo@escuela.edu.mx" required>
            </div>

            <div class="col-md-2">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select" required>
                <option disabled <?= !$usuarioEditar ? 'selected' : '' ?>>Seleccionar...</option>
                <?php
                $roles = ['Administrador','Docente','Alumno','Administrativo'];
                foreach ($roles as $r) {
                  $selected = ($usuarioEditar && $usuarioEditar['rol'] === $r) ? 'selected' : '';
                  echo "<option $selected>$r</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">Contraseña <?= $usuarioEditar ? '(dejar vacío para no cambiar)' : '' ?></label>
              <input type="password" name="contrasena" class="form-control"
                     placeholder="<?= $usuarioEditar ? 'Nueva contraseña (opcional)' : 'Ej. password123' ?>"
                     <?= $usuarioEditar ? '' : 'required' ?>>
            </div>
          </div>

          <div class="text-end mt-3">
            <?php if ($usuarioEditar): ?>
              <button type="submit" name="update" class="btn btn-warning">Actualizar</button>
              <a href="gestion_usuarios.php" class="btn btn-secondary">Cancelar</a>
            <?php else: ?>
              <button type="submit" name="insert" class="btn btn-success">Guardar</button>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- 🔍 Filtros de búsqueda -->
    <div class="card mb-3">
      <div class="card-header bg-secondary text-white">Filtrar Usuarios</div>
      <div class="card-body">
        <form method="GET" action="" class="row g-3 align-items-center">
          <div class="col-md-4">
            <label class="form-label">Buscar por nombre</label>
            <input type="text" name="buscar" class="form-control" placeholder="Ej. José"
                   value="<?= htmlspecialchars($buscarNombre) ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Filtrar por rol</label>
            <select name="rol" class="form-select">
              <option value="">Todos</option>
              <?php
              foreach ($roles as $r) {
                $sel = ($rolFiltro == $r) ? 'selected' : '';
                echo "<option value='$r' $sel>$r</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
          </div>
          <div class="col-md-2 align-self-end">
            <a href="gestion_usuarios.php" class="btn btn-outline-secondary w-100">Limpiar</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabla de usuarios registrados -->
    <div class="card">
      <div class="card-header bg-secondary text-white">Usuarios Registrados</div>
      <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Matrícula</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['idUsuario'] ?></td>
                  <td><?= $row['nombre'] ?></td>
                  <td><?= $row['matricula'] ?></td>
                  <td><?= $row['correo'] ?></td>
                  <td><?= $row['rol'] ?></td>
                  <td>
                    <a href="gestion_usuarios.php?edit=<?= $row['idUsuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="gestion_usuarios.php?delete=<?= $row['idUsuario'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <footer class="text-center py-3 bg-primary text-white mt-4">
    &copy; 2025 Sistema de Gestión Escolar
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión - Gestión Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4 rounded-4" style="width: 350px;">
    <h4 class="text-center mb-4">Iniciar sesión</h4>

    <!-- FORMULARIO 100% CORRECTO -->
    <form method="POST" action="<?= BASE_URL ?>index.php?action=login">

        <div class="mb-3">
            <label class="form-label fw-semibold">Correo electrónico</label>
            <input type="email" name="correo" class="form-control" required placeholder="ejemplo@escuela.edu.mx">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center py-2"><?= $error ?></div>
        <?php endif; ?>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-semibold">Iniciar sesión</button>
        </div>

    </form>

    <div class="text-center mt-3">
        <small class="text-muted">Sistema de Gestión Escolar © 2025</small>
    </div>
</div>

</body>
</html>

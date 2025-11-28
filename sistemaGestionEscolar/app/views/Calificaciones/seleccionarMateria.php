<?php 
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['usuario']) || 
        !in_array($_SESSION['usuario']['rol'], ['Docente','Administrador','Administrativo'])) {

        header("Location: index.php?action=login");
        exit;
    }
    // DEFINIR COLOR POR ROL
    $rol = $_SESSION['usuario']['rol'];

    $colorRol = match($rol) {
        'Administrador'  => '#0A2A43',   // Azul
        'Docente'        => '#06402B',   // Verde
        'Administrativo' => '#6D28D9',   // Morado
        default          => '#6D28D9'
    };

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root { --morado: #6D28D9; }
    .barra-superior { background: var(--morado); color:white; padding:10px 20px; }
    .thead-morado { background: var(--morado); color:white; }
    .btn-morado { background: var(--morado); color:white; }
    .btn-morado:hover { background:#5b21b6; }
    .footer { text-align:center; margin-top:30px; color:#777; }
    body { background: #eef0f5; }

    .navbar-dinamica {
        background: <?= $colorRol ?> !important;
        color: white;
    }

    .thead-dinamico {
        background: <?= $colorRol ?> !important;
        color:white;
    }

    .btn-dinamico {
        background: <?= $colorRol ?> !important;
        color:white !important;
    }

    .btn-dinamico:hover {
        background: <?= $colorRol ?>CC !important;
        color:white !important;
    }

    .footer { 
        text-align:center; 
        margin-top:30px; 
        color:#777; 
    }
</style>

<?php if (!isset($_SESSION)) session_start(); ?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-dinamica px-3">
  <div class="container-fluid">

    <span class="navbar-brand fw-semibold">
      <i class="bi bi-mortarboard-fill me-2"></i>Sistema de Gestión Escolar
    </span>

    <div class="ms-auto d-flex align-items-center">
      <span class="text-white me-3">
        <i class="bi bi-person-circle me-1"></i>
        <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
        (<?= htmlspecialchars($_SESSION['usuario']['rol']) ?>)
      </span>

      <a href="<?= BASE_URL ?>index.php?action=logout" 
         class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>

  </div>
</nav>



<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">
            <i class="bi bi-journal-bookmark me-2"></i>Materias
        </h3>

        <?php
            $dashboard = match($_SESSION['usuario']['rol']) {
                'Administrador'  => 'dashboard',
                'Administrativo' => 'dashboard_administrativo',
                'Docente'        => 'dashboard_docente',
                default          => 'dashboard'
            };
        ?>
        <a href="index.php?action=<?= $dashboard ?>" class="btn btn-dinamico">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <!-- BUSCADOR EN VIVO -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <div class="row align-items-center">

            <!-- Campo de búsqueda -->
            <div class="col-md-4">
                <input 
                type="text" 
                id="buscadorMaterias" 
                class="form-control"
                placeholder="Buscar materia o grupo"
                style="border: 2px solid #0A2A43;"
                >
            </div>

            <!-- Botón limpiar alineado a la derecha -->
            <div class="col-md-8 d-flex justify-content-end">
                <a 
                href="<?= BASE_URL ?>index.php?action=capturaCalificaciones" 
                class="btn"
                style="background: #0A2A43; color: white; font-weight: 600;"
                >
                Limpiar
                </a>
            </div>

            </div>

        </div>
    </div>
       
    <p class="text-muted">Selecciona la materia para capturar calificaciones.</p>

    <div class="card shadow border-0">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="thead-dinamico">
                    <tr>
                        <th>#</th>
                        <th>Materia</th>
                        <th>Grupo</th>
                        <th>Periodo</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i=1; while($row = $materias->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['nombreMateria']) ?></td>
                        <td><?= htmlspecialchars($row['nombreGrupo']) ?></td>
                        <td><?= htmlspecialchars($row['nombrePeriodo'] ?? 'Sin periodo') ?></td>

                        <td class="text-center">
                            <a href="index.php?action=calif_alumnos&materia=<?= intval($row['idMateria']) ?>&grupo=<?= intval($row['idGrupo']) ?>"
                            class="btn btn-dinamico btn-sm">
                                Capturar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>


        </div>
    </div>
</div>

<div class="footer">
    © 2025 Sistema de Gestión Escolar
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.getElementById('buscadorMaterias');
    const filas = document.querySelectorAll("table tbody tr");

    buscador.addEventListener("input", () => {
        const texto = buscador.value.toLowerCase();

        filas.forEach(fila => {
            const contenido = fila.innerText.toLowerCase();
            fila.style.display = contenido.includes(texto) ? "" : "none";
        });
    });
});
</script>

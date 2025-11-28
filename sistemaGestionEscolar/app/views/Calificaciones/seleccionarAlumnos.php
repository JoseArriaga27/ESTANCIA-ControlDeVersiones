<?php 
// Protección
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
        'Administrativo' => '#6D28D9',   // Morado
        'Docente'        => '#06402B',   // Verde
        default          => '#6D28D9'
    };

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: #eef0f5;
    }

    .navbar-dinamica {
        background: <?= $colorRol ?> !important;
        color: white;
    }

    .btn-dinamico {
        background: <?= $colorRol ?> !important;
        color:white !important;
        border:1px solid <?= $colorRol ?> !important;
    }

    .btn-dinamico:hover {
        background: <?= $colorRol ?>CC !important;
        color:white !important;
    }

    .thead-dinamico {
        background: <?= $colorRol ?> !important;
        color:white !important;
    }

    .footer {
        text-align: center;
        padding: 15px;
        color: #6b7280;
        font-size: 14px;
        margin-top: 40px;
    }
</style>


<!-- ========================================================= -->
<!-- BARRA SUPERIOR -->
<!-- ========================================================= -->
<?php if (!isset($_SESSION)) session_start(); ?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-dinamica">
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



<div class="container mt-4 mb-5">

    <!-- ========================================================= -->
    <!-- ENCABEZADO Y BOTON REGRESAR -->
    <!-- ========================================================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">
            <i class="bi bi-journal-check me-2 text-primary"></i>Captura de Calificaciones
        </h3>

        <a href="index.php?action=capturaCalificaciones" class="btn btn-dinamico">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>

    </div>

    <p class="text-muted">
        Ingresa las calificaciones (1–10) para cada alumno. Una vez guardadas <b>ya no se podrán editar</b>.
    </p>

    <!-- ========================================================= -->
    <!-- FORMULARIO -->
    <!-- ========================================================= -->
    <form method="POST" action="index.php?action=guardarCalificaciones">

        <input type="hidden" name="idMateria" value="<?= $idMateria ?>">
        <input type="hidden" name="idGrupo" value="<?= $idGrupo ?>">

        <div class="card shadow border-0">
            <div class="card-body">

                <table class="table table-bordered align-middle text-center">
                    <thead class="thead-dinamico">

                        <tr>
                            <th>#</th>
                            <th>Matrícula</th>
                            <th>Alumno</th>
                            <th>P1</th>
                            <th>P2</th>
                            <th>P3</th>
                            <th>Final</th>
                        </tr>
                    </thead>

<tbody>
<?php 
$i = 1;
while ($row = $alumnos->fetch_assoc()):

    // SI NO EXISTE CALIFICACIÓN, $cal SE VUELVE ARRAY VACÍO (no NULL)
    $cal = $modelCalif->obtenerCalificacion($row['idInscripcion'], $idMateria) ?? [];

    // VALORES SEGUROS: si no existe en BD → vacío
    $p1    = isset($cal['calificacionParcial1']) ? $cal['calificacionParcial1'] : '';
    $p2    = isset($cal['calificacionParcial2']) ? $cal['calificacionParcial2'] : '';
    $p3    = isset($cal['calificacionParcial3']) ? $cal['calificacionParcial3'] : '';
    $final = isset($cal['calificacionFinal'])     ? $cal['calificacionFinal']     : '';

    // BLOQUEOS SOLO SI EXISTE EL PARCIAL ESPECÍFICO
    $lockP1 = $p1 !== '';
    $lockP2 = $p2 !== '';
    $lockP3 = $p3 !== '';
?>
    <tr>
        <td><?= $i++ ?></td>

        <td><?= htmlspecialchars($row['matricula']) ?></td>

        <td><?= htmlspecialchars($row['nombres'] . ' ' . $row['apePaterno'] . ' ' . $row['apeMaterno']) ?></td>

        <!-- PARCIAL 1 -->
        <td>
            <input type="number" min="0" max="10" step="0.01"
                class="form-control input-calif parcial"
                data-row="<?= $row['idInscripcion'] ?>"
                name="alumnos[<?= $row['idInscripcion'] ?>][p1]"
                value="<?= $p1 ?>"
                <?= $lockP1 ? 'readonly style="background:#e9ecef;"' : '' ?>>
        </td>

        <!-- PARCIAL 2 -->
        <td>
            <input type="number" min="0" max="10" step="0.01"
                class="form-control input-calif parcial"
                data-row="<?= $row['idInscripcion'] ?>"
                name="alumnos[<?= $row['idInscripcion'] ?>][p2]"
                value="<?= $p2 ?>"
                <?= $lockP2 ? 'readonly style="background:#e9ecef;"' : '' ?>>
        </td>

        <!-- PARCIAL 3 -->
        <td>
            <input type="number" min="0" max="10" step="0.01"
                class="form-control input-calif parcial"
                data-row="<?= $row['idInscripcion'] ?>"
                name="alumnos[<?= $row['idInscripcion'] ?>][p3]"
                value="<?= $p3 ?>"
                <?= $lockP3 ? 'readonly style="background:#e9ecef;"' : '' ?>>
        </td>

        <!-- FINAL AUTOMÁTICO -->
        <td>
            <input type="text" 
                class="form-control bg-light final" 
                id="final-<?= $row['idInscripcion'] ?>"
                value="<?= $final ?>"
                readonly>
        </td>
    </tr>
<?php endwhile; ?>
</tbody>

                </table>

                <!-- BOTÓN GUARDAR CENTRADO -->
                <div class="text-center">
                    <button class="btn btn-dinamico px-5 py-2">Guardar Calificaciones</button>
                </div>

            </div>
        </div>

    </form>
</div>

<!-- ========================================================= -->
<!-- FOOTER -->
<!-- ========================================================= -->
<div class="footer">
    © 2025 Sistema de Gestión Escolar
</div>

<!-- ========================================================= -->
<!-- SCRIPTS -->
<!-- ========================================================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Calcular final SOLO cuando los 3 parciales son válidos (0–10)
document.querySelectorAll(".parcial").forEach(input => {
    input.addEventListener("input", () => {
        let row = input.dataset.row;

        let rawP1 = document.querySelector(`input[name='alumnos[${row}][p1]']`).value.trim();
        let rawP2 = document.querySelector(`input[name='alumnos[${row}][p2]']`).value.trim();
        let rawP3 = document.querySelector(`input[name='alumnos[${row}][p3]']`).value.trim();

        // Si alguno está vacío → final vacío
        if (rawP1 === "" || rawP2 === "" || rawP3 === "") {
            document.querySelector(`#final-${row}`).value = "";
            return;
        }

        let p1 = parseFloat(rawP1);
        let p2 = parseFloat(rawP2);
        let p3 = parseFloat(rawP3);

        // Validar rango correcto (0–10)
        if (
            isNaN(p1) || isNaN(p2) || isNaN(p3) ||
            p1 < 0 || p1 > 10 ||
            p2 < 0 || p2 > 10 ||
            p3 < 0 || p3 > 10
        ) {
            // Valor inválido → NO calcular
            document.querySelector(`#final-${row}`).value = "";
            return;
        }

        // Calcular final solo si todo es correcto
        let final = (p1 + p2 + p3) / 3;
        document.querySelector(`#final-${row}`).value = final.toFixed(2);
    });
});


// Confirmación antes de guardar
document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault();

    Swal.fire({
        title: "¿Guardar calificaciones?",
        text: "Una vez guardadas solo podrán ser modificados por el personal administrativo o administrador del sistema",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#6D28D9"
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>

<?php 
if (!isset($_SESSION)) session_start();

/* =============================
   COLOR POR ROL
============================= */
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'Sin rol';

$colorPrimario = "#0A2A43"; // azul admin
$colorHover    = "#0c355e";

if ($rolUsuario === "Administrativo") {
    $colorPrimario = "#320B86";   // morado
    $colorHover    = "#320B86";   // morado más oscuro
}

/* Dashboard según rol */
$dashboardDestino = ($rolUsuario === "Administrativo")
                    ? "dashboard_administrativo"
                    : "dashboard";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alumnos inscritos por carrera | Administración</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f4f4f4; }

        .navbar { background: <?= $colorPrimario ?>; }

        .card-header {
            background: <?= $colorPrimario ?>;
            color: white;
            font-weight: 600;
        }

        .btn-institucional {
            background: <?= $colorPrimario ?> !important;
            color: #fff !important;
        }
        .btn-institucional:hover {
            background: <?= $colorHover ?> !important;
            color: #fff !important;
        }

        .btn-regresar {
            background: <?= $colorPrimario ?>;
            color: white;
            font-weight: 500;
        }
        .btn-regresar:hover {
            background: <?= $colorHover ?>;
            color:white;
        }

        h3.titulo {
            font-weight: bold;
            color: <?= $colorPrimario ?>;
        }
        .subtitulo {
            margin-top: -6px;
            color: #6c757d;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-3">
  <span class="navbar-brand">Sistema de Gestión Escolar</span>
  <span class="text-white">
      <?= $_SESSION['usuario']['nombre'] ?? '' ?> (<?= $_SESSION['usuario']['rol'] ?? '' ?>)
  </span>
</nav>

<div class="container mt-4">

    <!-- ============================
         TÍTULO + BOTÓN REGRESAR
    ============================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="mb-2">
            <h3 class="titulo mb-2">
                <i class="bi bi-people-fill me-2"></i>
                Alumnos inscritos por carrera
            </h3>

            <p class="subtitulo text-muted" style="font-size: 0.95rem;">
                Seleccione una carrera y un periodo para generar el reporte.
            </p>
        </div>

        <a href="<?= BASE_URL ?>index.php?action=<?= $dashboardDestino ?>" class="btn btn-regresar px-4">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>


    <!-- ============================
         FORMULARIO
    ============================= -->
    <div class="card shadow-sm p-4">
        <div class="card-header mb-3">Seleccionar filtros</div>

        <form id="formReporte" method="POST">

            <div class="row mb-4">

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Carrera</label>
                    <select name="idCarrera" id="idCarrera" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php while($c = $carreras->fetch_assoc()): ?>
                            <option value="<?= $c['idCarrera'] ?>"><?= $c['nombreCarrera'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Periodo</label>
                    <select name="idPeriodo" id="idPeriodo" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php while($p = $periodos->fetch_assoc()): ?>
                            <option value="<?= $p['idPeriodo'] ?>"><?= $p['nombrePeriodo'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex gap-3">

                <button type="button" class="btn btn-institucional" id="btnPDF">
                    <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                </button>

                <button type="button" class="btn btn-success" id="btnExcel">
                    <i class="bi bi-file-earmark-excel"></i> Generar Excel
                </button>

            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formReporte");
    const btnPDF = document.getElementById("btnPDF");
    const btnExcel = document.getElementById("btnExcel");

    const selCarrera = document.getElementById("idCarrera");
    const selPeriodo = document.getElementById("idPeriodo");

    function validarCampos() {
        selCarrera.setCustomValidity("");
        selPeriodo.setCustomValidity("");

        if (selCarrera.value === "") {
            selCarrera.setCustomValidity("Seleccione una carrera");
            selCarrera.reportValidity();
            return false;
        }

        if (selPeriodo.value === "") {
            selPeriodo.setCustomValidity("Seleccione un periodo");
            selPeriodo.reportValidity();
            return false;
        }

        return true;
    }

    btnPDF.addEventListener("click", () => {
        if (!validarCampos()) return;
        form.action = "index.php?action=generarPDF_AlumnosCarrera";
        form.target = "_blank";
        form.submit();
    });

    btnExcel.addEventListener("click", () => {
        if (!validarCampos()) return;
        form.action = "index.php?action=generarExcel_AlumnosCarrera";
        form.target = "_self";
        form.submit();
    });
});
</script>

</body>
</html>

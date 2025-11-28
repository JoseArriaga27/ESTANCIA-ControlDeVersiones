<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../index.php?action=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Estadísticas</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { background:#f5f7fb; }

.navbar {
    background:#0A2A43;
    padding:12px;
}

.chart-box {
    height: 300px;
    position: relative;
}

.btn-institucional {
    background: #0A2A43 !important;
    color: white !important;
    border-radius: 6px;
}
.btn-institucional:hover {
    background: #09324f !important;
    color: white !important;
}

.btn-regresar {
    background: #0A2A43;
    color: white;
    font-weight: 500;
    border-radius: 6px;
}
.btn-regresar:hover {
    background: #09324f;
    color: white;
}


</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-3" style="background:#0A2A43;">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    
    <span class="navbar-brand h5 mb-0 text-white">
      Sistema de Gestión Escolar
    </span>

    <div class="d-flex align-items-center">
      <span class="text-white fw-semibold me-3">
        <i class="bi bi-person-circle"></i>
        <?= $_SESSION['usuario']['nombre'] ?>
        <span class="text-white-50">(<?= $_SESSION['usuario']['rol'] ?>)</span>
      </span>

      <a href="<?= BASE_URL ?>index.php?action=logout" 
         class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
      </a>
    </div>

  </div>
</nav>



<div class="container py-4">

<!-- TÍTULO + BOTONES -->
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">

    <!-- TÍTULO -->
    <h3 class="fw-bold" style="color:#0A2A43;">
        <i class="bi bi-bar-chart-fill me-2"></i> Estadísticas del Sistema
    </h3>

    <!-- BOTONES A LA DERECHA -->
    <div class="d-flex gap-2">
        <button class="btn btn-institucional px-4" onclick="exportCharts()">
            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
        </button>

        <a href="<?= BASE_URL ?>index.php?action=dashboard" 
           class="btn btn-regresar px-4">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

</div>


 

    <!-- FORM OCULTO -->
    <form id="chartsForm" 
      action="<?= BASE_URL ?>index.php?action=generarPDF_Estadisticas" 
      method="POST" 
      target="_blank" 
      style="display:none;">
</form>


    <!-- GRÁFICAS (iguales que el dashboard) -->

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary fw-semibold">Alumnos por Carrera</h6>
                <div class="chart-box">
                    <canvas id="chartCarreras"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary fw-semibold">Alumnos por Grupo</h6>
                <div class="chart-box">
                    <canvas id="chartGrupos"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary fw-semibold">Materias más asignadas</h6>
                <div class="chart-box">
                    <canvas id="chartMaterias"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary fw-semibold">Docentes con más asignaciones</h6>
                <div class="chart-box">
                    <canvas id="chartDocentes"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary fw-semibold">% Inscritos</h6>
                <div class="chart-box">
                    <canvas id="chartInscritos"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6 class="text-secondary fw-semibold">Distribución de Género</h6>
                <div class="chart-box">
                    <canvas id="chartGenero"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>


<script>
/* ======================================
       🚀 CÓDIGO PARA EXPORTAR EN HD
====================================== */

function exportCharts() {
    const charts = document.querySelectorAll("canvas");
    const form = document.getElementById("chartsForm");

    let index = 1;
    charts.forEach((canvas) => {

        const tempCanvas = document.createElement("canvas");

        // 🔥 TRIPLE RESOLUCIÓN PARA EVITAR PIXELES
        tempCanvas.width = canvas.width * 4;
        tempCanvas.height = canvas.height * 4;

        const ctx = tempCanvas.getContext("2d");
        ctx.scale(4, 4);
        ctx.drawImage(canvas, 0, 0);

        const imgData = tempCanvas.toDataURL("image/png");

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "img" + index;
        input.value = imgData;

        form.appendChild(input);
        index++;
    });

    form.submit();
}
</script>


<script>
/* ======================================
   🎨 PALETA
====================================== */
const colors = {
  navy: "#0A2A43",
  orange: "#FFA726",
  yellow: "#FFCC80",
  gray: "#9E9E9E"
};

/* ======================================
   🎯 CARGA DE DATOS DINÁMICOS
   (El controlador ya te pasa las variables)
====================================== */

<?php
// Convertimos los resultsets a arrays legibles en JS
$alCarr = []; $alTot = [];
while($r = $alumnosCarrera->fetch_assoc()){ $alCarr[] = $r['nombreCarrera']; $alTot[] = $r['total']; }

$grN = []; $grT = [];
while($r = $alumnosGrupo->fetch_assoc()){ $grN[] = $r['nombreGrupo']; $grT[] = $r['total']; }

$matN = []; $matT = [];
while($r = $materiasRanking->fetch_assoc()){ $matN[] = $r['nombreMateria']; $matT[] = $r['total']; }

$docN = []; $docT = [];
while($r = $docentesRanking->fetch_assoc()){ $docN[] = $r['docente']; $docT[] = $r['total']; }

$genN = []; $genT = [];
while($r = $generoAlumnos->fetch_assoc()){ $genN[] = $r['sexo']; $genT[] = $r['total']; }
?>


/* ======================================
   📊 GRAFICAS (exactas del dashboard)
====================================== */

new Chart(document.getElementById('chartCarreras'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($alCarr) ?>,
        datasets: [{
            label: 'Alumnos',
            data: <?= json_encode($alTot) ?>,
            backgroundColor: colors.navy
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});


new Chart(document.getElementById('chartGrupos'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($grN) ?>,
        datasets: [{
            label: 'Alumnos',
            data: <?= json_encode($grT) ?>,
            backgroundColor: colors.orange
        }]
    },
    options: { indexAxis:'y', responsive:true, maintainAspectRatio:false }
});


new Chart(document.getElementById('chartMaterias'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($matN) ?>,
        datasets: [{
            label: 'Asignaciones',
            data: <?= json_encode($matT) ?>,
            backgroundColor: colors.navy
        }]
    },
    options: { responsive:true, maintainAspectRatio:false }
});


new Chart(document.getElementById('chartDocentes'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($docN) ?>,
        datasets: [{
            label: 'Asignaciones',
            data: <?= json_encode($docT) ?>,
            backgroundColor: colors.yellow
        }]
    },
    options: { responsive:true, maintainAspectRatio:false }
});


new Chart(document.getElementById('chartInscritos'), {
    type: 'doughnut',
    data: {
        labels: ['Inscritos', 'No inscritos'],
        datasets: [{
            data: [
                <?= $inscritosVsTotales['inscritos'] ?>,
                <?= $inscritosVsTotales['totalAlumnos'] - $inscritosVsTotales['inscritos'] ?>
            ],
            backgroundColor: [colors.orange, colors.gray]
        }]
    },
    options: { responsive:true, maintainAspectRatio:false }
});


new Chart(document.getElementById('chartGenero'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($genN) ?>,
        datasets: [{
            data: <?= json_encode($genT) ?>,
            backgroundColor: [colors.navy, colors.orange]
        }]
    },
    options: { responsive:true, maintainAspectRatio:false }
});
</script>

</body>
</html>

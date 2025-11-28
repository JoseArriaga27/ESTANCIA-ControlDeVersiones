<?php
if (!isset($_SESSION)) session_start();

$nombreUsuario = $_SESSION['usuario']['nombre'];
$rolUsuario    = $_SESSION['usuario']['rol'];

// ======================
// COLOR SEGÚN EL ROL
// ======================
$color = "#0A2A43"; // ADMIN

if ($rolUsuario === "Docente") {
    $color = "#06402B"; // Verde
}
if ($rolUsuario === "Administrativo") {
    $color = "#320B86"; // Morado
}

// ======================
// DASHBOARD SEGÚN ROL
// ======================
$dashboardURL = "dashboard";

if ($rolUsuario === "Docente") $dashboardURL = "dashboard_docente";
if ($rolUsuario === "Administrativo") $dashboardURL = "dashboard_administrativo";

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Calificaciones por Grupo</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }

.navbar, footer { background: <?= $color ?> !important; }

.card-header { background: <?= $color ?>; color:white; font-weight:600; }

.btn-institucional {
  background: <?= $color ?>; 
  color:white;
  border:1px solid <?= $color ?>; 
  border-radius:8px;
}

.btn-institucional:hover { 
  background: #09324f; 
  color:white; 
}

.btn-regresar { 
    background: <?= $color ?>; 
    color:white; 
}

.btn-regresar:hover { background:#09324f; }

html, body { height: 100%; display:flex; flex-direction:column; }

footer {
    color:white;
    text-align:center;
    padding:10px 0;
    margin-top:auto;
    font-weight:500;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <span class="navbar-brand h5 mb-0">Sistema de Gestión Escolar</span>

  <div class="d-flex ms-auto text-white fw-semibold">
      <i class="bi bi-person-circle me-1"></i> <?= $nombreUsuario ?>
      <span class="text-white-50 ms-1">(<?= $rolUsuario ?>)</span>
      <a href="<?= BASE_URL ?>index.php?action=logout" class="btn btn-outline-light btn-sm ms-3">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>
  </div>
</nav>


<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold" style="color: <?= $color ?>;">
          <i class="bi bi-clipboard-data me-2"></i> Calificaciones por Grupo
      </h3>

      <a href="<?= BASE_URL ?>index.php?action=<?= $dashboardURL ?>" class="btn btn-regresar px-4">
          <i class="bi bi-arrow-left"></i> Regresar
      </a>
  </div>

  <div class="card shadow-sm p-4">
    <div class="card-header">Seleccionar Grupo</div>

    <form method="POST" id="formReporte">

      <label class="form-label fw-semibold">Grupo</label>
      <select name="idGrupo" id="idGrupo" class="form-select mb-3" required>
          <option value="">Seleccione...</option>
          <?php while($g = $grupos->fetch_assoc()): ?>
              <option value="<?= $g['idGrupo'] ?>">
                  <?= $g['nombreGrupo'] ?> — <?= $g['nombreCarrera'] ?> (<?= $g['nombrePeriodo'] ?>)
              </option>
          <?php endwhile; ?>
      </select>

      <div class="d-flex gap-3 mt-3">
          <button type="button" id="btnPDF" class="btn btn-institucional">
              <i class="bi bi-file-earmark-pdf"></i> Generar PDF
          </button>

          <button type="button" id="btnExcel" class="btn btn-success">
              <i class="bi bi-file-earmark-excel"></i> Generar Excel
          </button>
      </div>

    </form>
  </div>
</div>


<footer>© 2025 Sistema de Gestión Escolar</footer>



<!-- ======================================= -->
<!-- BOOTSTRAP TOAST BONITO -->
<!-- ======================================= -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
  <div id="toastAlert" class="toast align-items-center text-white bg-danger border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">Mensaje aquí</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// 🔹 Función para toast
function showToast(msg) {
    document.getElementById("toastMessage").innerText = msg;
    new bootstrap.Toast(document.getElementById("toastAlert")).show();
}

// 🔹 Validación estilo tooltip (como en tu ejemplo)
function validarGrupo() {
    const grupo = document.getElementById("idGrupo");

    grupo.setCustomValidity("");

    if (grupo.value === "") {
        grupo.setCustomValidity("Seleccione un grupo");
        grupo.reportValidity();
        return false;
    }

    return true;
}

const form = document.getElementById("formReporte");

// ---- Botón PDF ----
document.getElementById("btnPDF").addEventListener("click", () => {
    if (!validarGrupo()) return;

    form.action = "<?= BASE_URL ?>index.php?action=generarPDF_CalificacionesGrupo";
    form.target = "_blank";
    form.submit();
});

// ---- Botón Excel ----
document.getElementById("btnExcel").addEventListener("click", () => {
    if (!validarGrupo()) return;

    form.action = "<?= BASE_URL ?>index.php?action=generarExcel_CalificacionesGrupo";
    form.target = "_self";
    form.submit();
});
</script>

</body>
</html>

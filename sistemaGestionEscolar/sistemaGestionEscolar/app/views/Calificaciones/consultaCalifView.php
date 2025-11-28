<?php 
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?action=login");
        exit;
    }

    // COLORES POR ROL
    $rol = $_SESSION['usuario']['rol'];
    $colorRol = match($rol) {
        'Administrador'  => '#0A2A43',  // Azul
        'Administrativo' => '#320B86',  // Morado
        'Docente'        => '#06402B',  // Verde
        'Alumno'         => '#1E88E5',  // Azul alumno
        default          => '#6D28D9'
    };
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Calificaciones</title>
</head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<body>
    <style>
        .fade-slide {
            opacity: 0;
            transform: translateY(-10px);
            animation: fadeSlide .4s ease forwards;
        }

        @keyframes fadeSlide {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Color por rol */
        .navbar-dinamica {
            background: <?= $colorRol ?> !important;
        }

        .btn-dinamico {
            background: <?= $colorRol ?> !important;
            color:white !important;
        }
        .btn-dinamico:hover {
            background: <?= $colorRol ?>CC !important;
            color:white !important;
        }

        .thead-dinamico {
            background: <?= $colorRol ?> !important;
            color:white !important;
        }
    </style>


    <nav class="navbar navbar-expand-lg navbar-dark navbar-dinamica">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                Sistema de Gestión Escolar
            </span>

            <div class="text-white">
                <?= $_SESSION['usuario']['nombre'] ?> (<?= $_SESSION['usuario']['rol'] ?>)

                <a href="<?= BASE_URL ?>index.php?action=logout" 
                class="btn btn-light btn-sm ms-3">
                Cerrar sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-bar-chart-fill me-2"></i>
                    Consulta de Calificaciones
                </h3>
                <p class="text-muted mb-0">Calificaciones registradas por los docentes.</p>
            </div>
            <?php
                $dashboard = match($_SESSION['usuario']['rol']) {
                    'Administrador'  => 'dashboard',
                    'Administrativo' => 'dashboard_administrativo',
                    'Docente'        => 'dashboard_docente',
                    'Alumno'         => 'dashboard_alumno',
                    default          => 'dashboard'
                };
            ?>
            <a href="index.php?action=<?= $dashboard ?>" class="btn btn-dinamico px-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>


        </div>


        <?php if (in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])): ?>
            <?php if ($califEditar): ?>

            <div class="card mb-4 shadow fade-slide">
            <div class="card-header bg-warning text-dark fw-semibold">
                Editar Calificación de: <?= $califEditar['alumno'] ?>
            </div>

            <div class="card-body">

                <!-- INFO DEL ALUMNO -->
                <div class="alert alert-secondary">
                <b>Alumno:</b> <?= $califEditar['alumno'] ?><br>
                <b>Matrícula:</b> <?= $califEditar['matricula'] ?><br>
                <b>Materia:</b> <?= $califEditar['nombreMateria'] ?><br>
                <b>Grupo:</b> <?= $califEditar['nombreGrupo'] ?><br>
                <b>Periodo:</b> <?= $califEditar['nombrePeriodo'] ?><br>
                </div>

                <!-- FORMULARIO -->
                <form method="POST" action="index.php?action=actualizarCalificacion" class="row g-3">

                <input type="hidden" name="idInscripcion" value="<?= $califEditar['idInscripcion'] ?>">
                <input type="hidden" name="idMateria" value="<?= $califEditar['idMateria'] ?>">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Parcial 1</label>
                    <input type="number" min="0" max="10" step="0.01"
                        name="p1" class="form-control"
                        value="<?= $califEditar['calificacionParcial1'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Parcial 2</label>
                    <input type="number" min="0" max="10" step="0.01"
                        name="p2" class="form-control"
                        value="<?= $califEditar['calificacionParcial2'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Parcial 3</label>
                    <input type="number" min="0" max="10" step="0.01"
                        name="p3" class="form-control"
                        value="<?= $califEditar['calificacionParcial3'] ?>" required>
                </div>


                <div class="col-12 text-end mt-3">
                    <button class="btn btn-warning px-4">Actualizar</button>
                    <a href="index.php?action=consultaCalificaciones" class="btn btn-secondary px-4">Cancelar</a>
                </div>

                </form>

            </div>
            </div>

            <?php endif; ?>
        <?php endif; ?>



        <div class="card mb-4 shadow fade-slide">
            <div class="card-body">

                <table class="table table-hover align-middle">
                <thead class="thead-dinamico text-center">
                    <tr>
                        <th>Materia</th>
                        <th>Grupo</th>
                        <th>Matrícula</th>
                        <th>Alumno</th>
                        <th>P1</th>
                        <th>P2</th>
                        <th>P3</th>
                        <th>Final</th>
                        <th>Periodo</th>

                        <?php if (in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])): ?>
                        <th>Editar</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody class="text-center">

                <?php while($row = $datos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nombreMateria'] ?></td>
                    <td><?= $row['nombreGrupo'] ?></td>
                    <td><?= $row['matricula'] ?></td>
                    <td><?= $row['alumno'] ?></td>
                    <td><?= $row['calificacionParcial1'] ?></td>
                    <td><?= $row['calificacionParcial2'] ?></td>
                    <td><?= $row['calificacionParcial3'] ?></td>
                    <td><?= $row['calificacionFinal'] ?></td>
                    <td><?= $row['nombrePeriodo'] ?></td>

                    <?php if (in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])): ?>
                    <td>
                        <a href="index.php?action=consultaCalificaciones&edit=1&ins=<?= $row['idInscripcion'] ?>&mat=<?= $row['idMateria'] ?>"
                        class="btn btn-warning btn-sm">
                        Editar
                        </a>
                    </td>
                    <?php endif; ?>

                </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
            <div id="toast-exito" 
                style="
                    position:fixed;
                    top:80px;                 
                    left:50%;                 
                    transform:translateX(-50%);
                    background:#28a745;
                    color:white;
                    padding:12px 25px;
                    border-radius:8px;
                    box-shadow:0 3px 8px rgba(0,0,0,0.25);
                    z-index:9999;
                    font-size:16px;
                    font-weight:600;
                    animation: fadeIn .3s ease;
                "> Calificación actualizada correctamente
            </div>

            <script>
            setTimeout(() => {
                const toast = document.getElementById("toast-exito");
                if (toast) {
                    toast.style.transition = "opacity .5s";
                    toast.style.opacity = "0";
                    setTimeout(() => toast.remove(), 600);
                }
            }, 2000);
            </script>

            <?php endif; ?>

            

            </div>
        </div>

    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {

        const p1 = document.querySelector("input[name='p1']");
        const p2 = document.querySelector("input[name='p2']");
        const p3 = document.querySelector("input[name='p3']");
        const finalInput = document.querySelector("#final_calculado");

        function recalcularFinal() {
            let v1 = parseFloat(p1.value) || 0;
            let v2 = parseFloat(p2.value) || 0;
            let v3 = parseFloat(p3.value) || 0;

            let final = (v1 + v2 + v3) / 3;
            finalInput.value = final.toFixed(2);
        }

        if (p1 && p2 && p3 && finalInput) {
            p1.addEventListener("input", recalcularFinal);
            p2.addEventListener("input", recalcularFinal);
            p3.addEventListener("input", recalcularFinal);
        }
    });
    </script>

</body>
</html>



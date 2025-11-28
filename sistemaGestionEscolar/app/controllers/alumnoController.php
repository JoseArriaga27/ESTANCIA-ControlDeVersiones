<?php
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../models/alumnoModel.php';

$model = new AlumnoModel($connection);

// ============================================
// PETICIÓN AJAX: obtener grupos por carrera
// ============================================
if (isset($_GET['ajax_grupos'])) {
    header('Content-Type: application/json');
    $idCarrera = intval($_GET['idCarrera'] ?? 0);
    $res = $model->obtenerGruposPorCarrera($idCarrera);
    $data = [];
    while ($g = $res->fetch_assoc()) {
        $data[] = $g;
    }
    echo json_encode($data);
    exit;
}

// ============================================
// VARIABLES DE MENSAJE
// ============================================
$mensaje = '';
$tipo = '';

// ============================================
// INSERTAR NUEVO ALUMNO
// ============================================
if (isset($_POST['insertar'])) {
    $idUsuario = $_POST['idUsuario'];
    $idCarrera = $_POST['idCarrera'];

    if ($model->agregarAlumno($idUsuario, $idCarrera)) {
        $mensaje = "Alumno registrado correctamente.";
        $tipo = "success";
    } else {
        $mensaje = "Error al registrar al alumno.";
        $tipo = "danger";
    }

    header("Location: ../views/alumnosView.php?msg=$mensaje&type=$tipo");
    exit;
}

// ============================================
// ACTUALIZAR ALUMNO
// ============================================
if (isset($_POST['actualizar'])) {
    $idAlumno = $_POST['idAlumno'];
    $idCarrera = $_POST['idCarrera'];

    if ($model->editarAlumno($idAlumno, $idCarrera)) {
        $mensaje = "Alumno actualizado correctamente.";
        $tipo = "success";
    } else {
        $mensaje = "Error al actualizar al alumno.";
        $tipo = "danger";
    }

    header("Location: ../views/alumnosView.php?msg=$mensaje&type=$tipo");
    exit;
}

// ============================================
// ELIMINAR ALUMNO
// ============================================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if ($model->eliminarAlumno($id)) {
        $mensaje = "Alumno eliminado correctamente.";
        $tipo = "success";
    } else {
        $mensaje = "Error al eliminar el alumno.";
        $tipo = "danger";
    }

    header("Location: ../views/alumnosView.php?msg=$mensaje&type=$tipo");
    exit;
}

// ============================================
// INSCRIBIR ALUMNO EN UN GRUPO
// ============================================
if (isset($_POST['inscribir'])) {
    $idAlumno = intval($_POST['idAlumno']);
    $idGrupo = intval($_POST['idGrupo']);
    $fecha = date('Y-m-d');

    // Validar si el alumno ya está inscrito
    $stmt = $connection->prepare("SELECT COUNT(*) AS existe FROM inscripciones WHERE idAlumno = ?");
    $stmt->bind_param("i", $idAlumno);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($res['existe'] > 0) {
        // Ya está inscrito en otro grupo
        $mensaje = "El alumno ya está inscrito en un grupo.";
        $tipo = "warning";
        header("Location: ../views/alumnosView.php?msg=$mensaje&type=$tipo");
        exit;
    }

    // Insertar inscripción
    $stmt = $connection->prepare("INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $idAlumno, $idGrupo, $fecha);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        $mensaje = "Alumno inscrito correctamente.";
        $tipo = "success";
    } else {
        $mensaje = "Error al inscribir al alumno.";
        $tipo = "danger";
    }

    header("Location: ../views/alumnosView.php?msg=$mensaje&type=$tipo");
    exit;
}
?>

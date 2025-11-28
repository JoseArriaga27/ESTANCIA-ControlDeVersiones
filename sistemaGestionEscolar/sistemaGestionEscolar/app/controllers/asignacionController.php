<?php
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../models/asignacionModel.php';

class AsignacionController {
    private $model;
    public function __construct($connection) {
        $this->model = new AsignacionModel($connection);
        $this->model->syncDocentes();
    }

    public function insertar() {
        $idDocente = intval($_POST['idDocente'] ?? 0);
        $idMateria = intval($_POST['idMateria'] ?? 0);
        $idGrupo   = intval($_POST['idGrupo'] ?? 0);
        $idPeriodo = intval($_POST['idPeriodo'] ?? 0);

        $ok = ($idDocente && $idMateria && $idGrupo && $idPeriodo)
            ? $this->model->agregarAsignacion($idDocente, $idMateria, $idGrupo, $idPeriodo)
            : false;

        $msg  = $ok ? "Asignación registrada correctamente." : "Error: completa todos los campos.";
        $type = $ok ? "success" : "danger";
        header("Location: ../views/asignacionesView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }

    public function actualizar() {
        $idAsig   = intval($_POST['idAsignacion'] ?? 0);
        $idDoc    = intval($_POST['idDocente'] ?? 0);
        $idMat    = intval($_POST['idMateria'] ?? 0);
        $idGru    = intval($_POST['idGrupo'] ?? 0);
        $idPer    = intval($_POST['idPeriodo'] ?? 0);

        $ok = ($idAsig && $idDoc && $idMat && $idGru && $idPer)
            ? $this->model->editarAsignacion($idAsig, $idDoc, $idMat, $idGru, $idPer)
            : false;

        $msg  = $ok ? "Asignación actualizada correctamente." : "Error al actualizar la asignación.";
        $type = $ok ? "success" : "danger";
        header("Location: ../views/asignacionesView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }

    public function eliminar() {
        $id  = intval($_GET['delete'] ?? 0);
        $ok  = $id ? $this->model->eliminarAsignacion($id) : false;
        $msg = $ok ? "Asignación eliminada correctamente." : "Error al eliminar la asignación.";
        $type = $ok ? "success" : "danger";
        header("Location: ../views/asignacionesView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }
}

$controller = new AsignacionController($connection);

if (isset($_POST['insertar'])) {
    $controller->insertar();
} elseif (isset($_POST['actualizar'])) {
    $controller->actualizar();
} elseif (isset($_GET['delete'])) {
    $controller->eliminar();
} else {
    header("Location: ../views/asignacionesView.php");
    exit;
}

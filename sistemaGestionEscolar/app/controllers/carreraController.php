<?php
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../models/carreraModel.php';

class CarreraController {
    private $model;

    public function __construct($connection) {
        $this->model = new CarreraModel($connection);
    }

    public function insertar() {
        $nombre = trim($_POST['nombreCarrera'] ?? '');
        $desc   = trim($_POST['descripcion'] ?? '');

        $ok = ($nombre !== '') ? $this->model->agregarCarrera($nombre, $desc) : false;
        $msg = $ok ? "Carrera registrada correctamente." : "Error: completa todos los campos.";
        $type = $ok ? "success" : "danger";
        header("Location: ../views/carrerasView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }

    public function actualizar() {
        $id = intval($_POST['idCarrera'] ?? 0);
        $nombre = trim($_POST['nombreCarrera'] ?? '');
        $desc   = trim($_POST['descripcion'] ?? '');
        $ok = ($id && $nombre !== '') ? $this->model->editarCarrera($id, $nombre, $desc) : false;
        $msg = $ok ? "Carrera actualizada correctamente." : "Error al actualizar la carrera.";
        $type = $ok ? "success" : "danger";
        header("Location: ../views/carrerasView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }

    public function eliminar() {
        $id = intval($_GET['delete'] ?? 0);
        $ok = false;
        $msg = "";
        $type = "danger";

        if ($id) {
            $ok = $this->model->eliminarCarrera($id);
            if ($ok) {
                $msg = "Carrera eliminada correctamente.";
                $type = "success";
            } else {
                $msg = "No se puede eliminar la carrera porque tiene grupos o alumnos asignados.";
                $type = "warning";
            }
        } else {
            $msg = "ID de carrera inválido.";
        }

        header("Location: ../views/carrerasView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }
}

$controller = new CarreraController($connection);

if (isset($_POST['insertar'])) {
    $controller->insertar();
} elseif (isset($_POST['actualizar'])) {
    $controller->actualizar();
} elseif (isset($_GET['delete'])) {
    $controller->eliminar();
} else {
    header("Location: ../views/carrerasView.php");
    exit;
}

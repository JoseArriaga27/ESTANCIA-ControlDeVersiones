<?php
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../models/periodoModel.php';

class PeriodoController {
    private $model;

    public function __construct($connection) {
        $this->model = new PeriodoModel($connection);
    }

    // Mostrar vista principal
    public function index() {
        $mensaje = $_GET['msg'] ?? '';
        $tipo = $_GET['type'] ?? '';

        require_once __DIR__ . '/../views/periodosView.php';
    }

    // Insertar
    public function insertar() {
        global $connection;
        $nombre = $_POST['nombrePeriodo'];
        $inicio = $_POST['fechaInicio'];
        $fin = $_POST['fechaFin'];

        $resultado = $this->model->agregarPeriodo($nombre, $inicio, $fin);
        $msg = $resultado ? "Periodo guardado correctamente." : "Error al guardar el periodo.";
        $type = $resultado ? "success" : "danger";

        header("Location: ../views/periodosView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }

    // Editar
    public function actualizar() {
        $id = $_POST['idPeriodo'];
        $nombre = $_POST['nombrePeriodo'];
        $inicio = $_POST['fechaInicio'];
        $fin = $_POST['fechaFin'];

        $resultado = $this->model->editarPeriodo($id, $nombre, $inicio, $fin);
        $msg = $resultado ? "Periodo actualizado correctamente." : "Error al actualizar el periodo.";
        $type = $resultado ? "success" : "danger";

        header("Location: ../views/periodosView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }

    // Eliminar
    public function eliminar() {
        $id = $_GET['delete'];
        $resultado = $this->model->eliminarPeriodo($id);
        $msg = $resultado ? "Periodo eliminado correctamente." : "Error al eliminar el periodo.";
        $type = $resultado ? "success" : "danger";

        header("Location: ../views/periodosView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
        exit;
    }
}

// ==============
// Enrutador simple
// ==============
$controller = new PeriodoController($connection);

if (isset($_POST['insertar'])) {
    $controller->insertar();
} elseif (isset($_POST['actualizar'])) {
    $controller->actualizar();
} elseif (isset($_GET['delete'])) {
    $controller->eliminar();
} else {
    $controller->index();
}
?>

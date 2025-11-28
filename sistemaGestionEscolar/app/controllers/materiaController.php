<?php
    require_once __DIR__ . '/../../config/db_connection.php';
    require_once __DIR__ . '/../models/materiaModel.php';

    class MateriaController {
        private $model;

        public function __construct($connection) {
            $this->model = new MateriaModel($connection);
        }

        // Mostrar vista principal
        public function index() {
            $mensaje = $_GET['msg'] ?? '';
            $tipo = $_GET['type'] ?? '';
            require_once __DIR__ . '/../views/materiasView.php';
        }

        // Insertar materia
        public function insertar() {
            $nombre = $_POST['nombreMateria'];
            $clave = $_POST['claveMateria'];
            $horas = $_POST['horasSemana'];
            $idPeriodo = $_POST['idPeriodo'];

            mysqli_report(MYSQLI_REPORT_OFF);
            try {
                $resultado = $this->model->agregarMateria($nombre, $clave, $horas, $idPeriodo);
                $msg = $resultado ? "Materia registrada correctamente." : "Error al registrar la materia.";
                $type = $resultado ? "success" : "danger";
            } catch (mysqli_sql_exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $msg = "Ya existe una materia con esa clave.";
                    $type = "danger";
                } else {
                    $msg = "Error: " . $e->getMessage();
                    $type = "danger";
                }
            }

            header("Location: ../views/materiasView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
            exit;
        }

        // Actualizar materia
        public function actualizar() {
            $id = $_POST['idMateria'];
            $nombre = $_POST['nombreMateria'];
            $clave = $_POST['claveMateria'];
            $horas = $_POST['horasSemana'];
            $idPeriodo = $_POST['idPeriodo'];

            $resultado = $this->model->editarMateria($id, $nombre, $clave, $horas, $idPeriodo);
            $msg = $resultado ? "Materia actualizada correctamente." : "Error al actualizar la materia.";
            $type = $resultado ? "success" : "danger";

            header("Location: ../views/materiasView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
            exit;
        }

        // Eliminar materia
        public function eliminar() {
            $id = $_GET['delete'];
            $resultado = $this->model->eliminarMateria($id);
            $msg = $resultado ? "Materia eliminada correctamente." : "Error al eliminar la materia.";
            $type = $resultado ? "success" : "danger";

            header("Location: ../views/materiasView.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
            exit;
        }
    }

    $controller = new MateriaController($connection);
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

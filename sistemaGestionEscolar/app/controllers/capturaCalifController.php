<?php 
require_once __DIR__ . '/../models/capturaCalifModel.php';

class CapturaCalifController {

    private $connection;
    private $model;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->model = new CapturaCalifModel($connection);
    }

    // ========================================================
    // 1. Mostrar materias asignadas
    // ========================================================
    public function seleccionarMateria() {

        if (!isset($_SESSION['usuario']) || 
            !in_array($_SESSION['usuario']['rol'], ['Docente','Administrador','Administrativo'])) {

            header("Location: index.php?action=login");
            exit;
        }

        $rol = $_SESSION['usuario']['rol'];

        // ====== DOCENTE ======
        if ($rol === 'Docente') {

            if (!isset($_SESSION['usuario']['idDocente'])) {
                die("Error: el docente no tiene un ID asociado.");
            }

            $idDocente = intval($_SESSION['usuario']['idDocente']);
            $materias = $this->model->obtenerAsignacionesDocente($idDocente);

        } else {
            // ====== ADMINISTRADOR — ADMINISTRATIVO ======
            // Ellos pueden ver TODAS las materias
            $materias = $this->model->obtenerTodasLasAsignaciones();
        }

        include __DIR__ . '/../views/Calificaciones/seleccionarMateria.php';
    }

    // ========================================================
    // 2. Mostrar alumnos según grupo
    // ========================================================
    public function seleccionarAlumnos() {

        $idMateria = intval($_GET['materia']);
        $idGrupo   = intval($_GET['grupo']);

        $alumnos = $this->model->obtenerAlumnosGrupo($idGrupo);

        $modelCalif = $this->model;

        include __DIR__ . '/../views/Calificaciones/seleccionarAlumnos.php';
    }

    // ========================================================
    // 3. Guardar calificaciones
    // ========================================================
    public function guardarCalificaciones() {

        $idMateria = $_POST['idMateria'];
        $idGrupo   = $_POST['idGrupo'];

        foreach ($_POST['alumnos'] as $idInscripcion => $datos) {

        $p1 = ($datos['p1'] ?? '') === '' ? null : floatval($datos['p1']);
        $p2 = ($datos['p2'] ?? '') === '' ? null : floatval($datos['p2']);
        $p3 = ($datos['p3'] ?? '') === '' ? null : floatval($datos['p3']);

        if ($p1 !== null && $p2 !== null && $p3 !== null) {
            $final = ($p1 + $p2 + $p3) / 3;
        } else {
            $final = null;
        }


            $this->model->guardarCalificacion($idInscripcion, $idMateria, $p1, $p2, $p3, $final);
        }

        header("Location: index.php?action=capturaCalificaciones&msg=ok");
        exit;
    }

}

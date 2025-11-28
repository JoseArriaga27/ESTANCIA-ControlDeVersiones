<?php
require_once __DIR__ . '/../models/DocenteModel.php';

class DocenteController {

    private $db;
    private $model;

    public function __construct($connection) {
        $this->db = $connection;
        $this->model = new DocenteModel($connection);

        if (!isset($_SESSION)) session_start();
        if ($_SESSION['usuario']['rol'] !== 'Docente') {
            header("Location: index.php?action=login");
            exit;
        }
    }

    // ===============================================
    // LISTA DE GRUPOS DEL DOCENTE
    // ===============================================
    public function misGrupos() {

        $idUsuario = $_SESSION['usuario']['id'];

        $idDocente = $this->model->obtenerIdDocentePorUsuario($idUsuario);

        if (!$idDocente) die("Error: No se encontró el docente.");

        $grupos = $this->model->obtenerGruposDelDocente($idDocente);

        require __DIR__ . '/../views/Docente/misGruposView.php';
    }

    // ===============================================
    // AJAX: ALUMNOS DEL GRUPO
    // ===============================================
    public function ajaxAlumnosGrupo() {

        if (empty($_GET['idGrupo'])) {
            echo json_encode([]);
            return;
        }

        $idGrupo = intval($_GET['idGrupo']);
        $alumnos = $this->model->obtenerAlumnosDeGrupo($idGrupo);

        $lista = [];
        while ($a = $alumnos->fetch_assoc()) {
            $lista[] = $a;
        }

        echo json_encode($lista);
    }


    public function misMaterias() {

        if ($_SESSION['usuario']['rol'] !== 'Docente') {
            header("Location: index.php?action=login");
            exit;
        }

        $idDocente = $_SESSION['usuario']['idDocente'];

        if (!$idDocente) {
            die("Error: No se encontró el docente.");
        }

        $materias = $this->model->obtenerMateriasDocente($idDocente);

        require __DIR__ . '/../views/Docente/misMateriasViews.php';
    }


}

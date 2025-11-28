<?php
require_once __DIR__ . '/../models/docenteModel.php';

class AdministrativoController {

    private $db;
    private $model;

    public function __construct($connection) {
        $this->db = $connection;
        $this->model = new DocenteModel($connection);

        if (!isset($_SESSION)) session_start();

        if (!in_array($_SESSION['usuario']['rol'], ['Administrativo','Administrador'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function consultaDocentes() {
        $docentes = $this->model->listarDocentes();
        require __DIR__ . '/../views/consultaDocentesView.php';
    }
}

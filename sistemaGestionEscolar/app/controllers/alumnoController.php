<?php

require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../models/alumnoModel.php';

class AlumnoController {

    private $connection;
    private $model;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->model = new AlumnoModel($connection);
    }

    // ============================================================
    // MOSTRAR MATERIAS DEL ALUMNO (VISTA DEL ALUMNO)
    // ============================================================
    public function misMaterias() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /sistemaGestionEscolar/index.php?action=login");
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id']; 
        $materias = $this->model->obtenerMateriasAlumno($idUsuario);

        include __DIR__ . '/../views/misMateriasView.php';
    }

    // ============================================================
    // PETICIÓN AJAX: obtener grupos por carrera
    // ============================================================
    public function ajaxGrupos() {

        $idCarrera = intval($_GET['idCarrera'] ?? 0);
        $res = $this->model->obtenerGruposPorCarrera($idCarrera);

        $data = [];
        while ($g = $res->fetch_assoc()) {
            $data[] = $g;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ============================================================
    // REGISTRAR NUEVO ALUMNO
    // ============================================================
    public function insertar() {

        $idUsuario = $_POST['idUsuario'] ?? null;
        $idCarrera = $_POST['idCarrera'] ?? null;

        if ($this->model->agregarAlumno($idUsuario, $idCarrera)) {
            $msg = "Alumno registrado correctamente.";
            $type = "success";
        } else {
            $msg = "Error al registrar al alumno.";
            $type = "danger";
        }

        header("Location: " . BASE_URL . "app/views/alumnosView.php?msg=$msg&type=$type");
exit;        exit;
    }

    // ============================================================
    // ACTUALIZAR ALUMNO
    // ============================================================
    public function actualizar() {

        $idAlumno = $_POST['idAlumno'];
        $idCarrera = $_POST['idCarrera'];

        if ($this->model->editarAlumno($idAlumno, $idCarrera)) {
            $msg = "Alumno actualizado correctamente.";
            $type = "success";
        } else {
            $msg = "Error al actualizar al alumno.";
            $type = "danger";
        }

        header("Location: " . BASE_URL . "app/views/alumnosView.php?msg=$msg&type=$type");
exit;        exit;
    }

    // ============================================================
    // ELIMINAR ALUMNO
    // ============================================================
    public function eliminar() {

        $id = $_GET['delete'] ?? null;

        if ($this->model->eliminarAlumno($id)) {
            $msg = "Alumno eliminado correctamente.";
            $type = "success";
        } else {
            $msg = "Error al eliminar el alumno.";
            $type = "danger";
        }

        header("Location: " . BASE_URL . "app/views/alumnosView.php?msg=$msg&type=$type");
exit;        exit;
    }

    // ============================================================
    // INSCRIBIR ALUMNO A UN GRUPO
    // ============================================================
    public function inscribir() {

        $idAlumno = intval($_POST['idAlumno']);
        $idGrupo  = intval($_POST['idGrupo']);
        $fecha    = date('Y-m-d');

        // Validar inscripción previa
        $stmt = $this->connection->prepare("SELECT COUNT(*) AS existe FROM inscripciones WHERE idAlumno = ?");
        $stmt->bind_param("i", $idAlumno);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res['existe'] > 0) {
            $msg = "El alumno ya está inscrito en un grupo.";
            $type = "warning";
            header("Location: " . BASE_URL . "app/views/alumnosView.php?msg=$msg&type=$type");
exit;            exit;
        }

        // Realizar inscripción
        $stmt = $this->connection->prepare("
            INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $idAlumno, $idGrupo, $fecha);
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
            $msg = "Alumno inscrito correctamente.";
            $type = "success";
        } else {
            $msg = "Error al inscribir al alumno.";
            $type = "danger";
        }

        header("Location: " . BASE_URL . "app/views/alumnosView.php?msg=$msg&type=$type");
exit;        exit;
    }


    public function misCalificaciones() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /sistemaGestionEscolar/index.php?action=login");
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id'];
        $calificaciones = $this->model->obtenerCalificacionesAlumno($idUsuario);

        include __DIR__ . '/../views/misCalificacionesView.php';
    }

    public function dashboardAlumno() {

    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: /sistemaGestionEscolar/index.php?action=login");
        exit;
    }

    require_once __DIR__ . '/../models/alumnoModel.php';
    $model = new AlumnoModel($this->connection);

    $idUsuario = $_SESSION['usuario']['id'];

    // === Calificaciones por materia ===
    $res = $model->obtenerCalificacionesDashboard($idUsuario);

    $materias = [];
    $calificaciones = [];

    while ($row = $res->fetch_assoc()) {
        $materias[] = $row['nombreMateria'];
        $calificaciones[] = floatval($row['calificacion']);
    }

    // === Promedio general ===
    $promedio = $model->obtenerPromedioDashboard($idUsuario);

    include __DIR__ . '/../views/Dashboard/dashboard_alumno.php';
}



}

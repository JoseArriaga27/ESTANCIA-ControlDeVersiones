<?php
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../models/dashboardModel.php';

class DashboardController {
    private $connection;
    private $userModel;
    private $dashboardModel;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->userModel = new UserModel($connection);
        $this->dashboardModel = new DashboardModel($connection);
    }

    public function index() {

        $cuentas = $this->userModel->cuentas();
        $usuarios = $cuentas['usuarios'];
        $materias = $cuentas['materias'];
        $grupos   = $cuentas['grupos'];

        // 1. Alumnos por Carrera
        $alumnosCarrera = $this->dashboardModel->getAlumnosPorCarrera();

        // 2. Alumnos por Grupo
        $alumnosGrupo = $this->dashboardModel->getAlumnosPorGrupo();

        // 3. Materias más asignadas
        $materiasRanking = $this->dashboardModel->getMateriasMasAsignadas();

        // 4. Docentes con más asignaciones
        $docentesRanking = $this->dashboardModel->getDocentesMasAsignados();

        // 5. Inscritos vs totales
        $inscritosVsTotales = $this->dashboardModel->getInscritosVsTotales();

        // 6. Género del alumnado
        $generoAlumnos = $this->dashboardModel->getGeneroAlumnos();

        /* ============================
           Cargar la vista
        ============================= */
        require __DIR__ . '/../views/Dashboard/dashboard_admin.php';
    }

    public function indexAdministrativo() {

    // Obtener totales
    $cuentas = $this->userModel->cuentas();
    $usuarios = $cuentas['usuarios'];
    $materias = $cuentas['materias'];
    $grupos   = $cuentas['grupos'];

    // Gráficas
    $alumnosCarrera     = $this->dashboardModel->getAlumnosPorCarrera();
    $alumnosGrupo       = $this->dashboardModel->getAlumnosPorGrupo();
    $materiasRanking    = $this->dashboardModel->getMateriasMasAsignadas();
    $docentesRanking    = $this->dashboardModel->getDocentesMasAsignados();
    $inscritosVsTotales = $this->dashboardModel->getInscritosVsTotales();
    $generoAlumnos      = $this->dashboardModel->getGeneroAlumnos();

    require __DIR__ . '/../views/Dashboard/dashboard_administrativo.php';
}


}


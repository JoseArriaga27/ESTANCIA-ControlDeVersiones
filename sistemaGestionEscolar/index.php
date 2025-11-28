<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db_connection.php';
require_once __DIR__ . '/app/controllers/loginController.php';
require_once __DIR__ . '/app/controllers/userController.php';
require_once __DIR__ . '/app/controllers/backupController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {

    case 'login':
        $controller = new LoginController($connection);
        $controller->iniciarSesion();
        break;

    case 'logout':
        $controller = new LoginController($connection);
        $controller->cerrarSesion();
        break;

    case 'backup':
        $controller = new BackupController($connection);
        $controller->realizarRespaldoBD();
        break;

    case 'restore':
        $controller = new BackupController($connection);
        $controller -> restaurarBD();
        break;   

    case 'usuarios':
        $controller = new UserController($connection);
        $controller->gestionarUsuarios();
        break;

    case 'dashboard':
        require_once __DIR__ . '/app/controllers/DashboardController.php';
        $controller = new DashboardController($connection);
        $controller->index();
        break;

    case 'capturaCalificaciones':
        require_once __DIR__ . '/app/controllers/capturaCalifController.php';
        $controller = new CapturaCalifController($connection);
        $controller->seleccionarMateria();
        break;

    case 'calif_alumnos':
        require_once __DIR__ . '/app/controllers/capturaCalifController.php';
        $controller = new CapturaCalifController($connection);
        $controller->seleccionarAlumnos();
        break;

    case 'guardarCalificaciones':
        require_once __DIR__ . '/app/controllers/capturaCalifController.php';
        $controller = new CapturaCalifController($connection);
        $controller->guardarCalificaciones();
        break;

    case 'dashboard_alumno':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->dashboardAlumno();
        break;

    case 'dashboard_administrativo':
        require_once __DIR__ . '/app/controllers/DashboardController.php';
        $controller = new DashboardController($connection);
        $controller->indexAdministrativo();
        break;


    case 'consultaCalificaciones':
        require_once 'app/controllers/consultaCalifController.php';
        $controller = new ConsultaCalifController($connection);
        $controller->consulta();
        break;

    case 'actualizarCalificacion':
        require_once 'app/controllers/consultaCalifController.php';
        $controller = new ConsultaCalifController($connection);
        $controller->actualizar();
        break;
    case 'generarKardex':
        require_once 'app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarKardex();
        break;

    case 'generarKardexA': 
        require_once 'app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarKardexAlumno();
        break;

    case 'misMaterias':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->misMaterias();
        break;

    case 'misCalificaciones':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->misCalificaciones();
        break;

    case 'ajaxGrupos':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->ajaxGrupos();
        break;

    case 'insertarAlumno':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->insertar();
        break;

    case 'actualizarAlumno':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->actualizar();
        break;

    case 'eliminarAlumno':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->eliminar();
        break;

    case 'inscribirAlumno':
        require_once 'app/controllers/alumnoController.php';
        $controller = new AlumnoController($connection);
        $controller->inscribir();
        break;

    case 'reporteAlumnosCarrera':
        require_once 'app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->alumnosInscritosCarrera();
        break;

    case 'generarPDF_AlumnosCarrera':
        require_once 'app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarPDF_AlumnosCarrera();
        break;

    case 'generarExcel_AlumnosCarrera':
        require_once 'app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarExcel_AlumnosCarrera();
        break;
    case 'alumnosGeneral':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->alumnosGeneral();
        break;
    case 'generarPDF_AlumnosGeneral':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarPDF_AlumnosGeneral();
        break;

    case 'generarExcel_AlumnosGeneral':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarExcel_AlumnosGeneral();
        break;
    case 'calificacionesGrupo':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->calificacionesPorGrupo();
        break;

    case 'generarPDF_CalificacionesGrupo':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarPDF_CalificacionesGrupo();
        break;

    case 'generarExcel_CalificacionesGrupo':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarExcel_CalificacionesGrupo();
        break;
    case 'estadisticasPDF':
        require_once 'app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->estadisticasPDF();
        break;

    case 'generarPDF_Estadisticas':
        require_once __DIR__ . '/app/controllers/reporteController.php';
        $controller = new ReporteController($connection);
        $controller->generarPDF_Estadisticas();
        break;
    case 'misGrupos':
        require_once __DIR__ . '/app/controllers/DocenteController.php';
        $controller = new DocenteController($connection);
        $controller->misGrupos();
        break;

    case 'ajaxAlumnosGrupo':
        require_once __DIR__ . '/app/controllers/DocenteController.php';
        $controller = new DocenteController($connection);
        $controller->ajaxAlumnosGrupo();
        break;

    case 'misMateriasDocente':
        require_once __DIR__ . '/app/controllers/docenteController.php';
        $controller = new DocenteController($connection);
        $controller->misMaterias();
        break;
    case 'consultaDocentes':
        require_once 'app/controllers/administrativoController.php';
        $controller = new AdministrativoController($connection);
        $controller->consultaDocentes();
        break;
    case 'dashboard_docente':
        require_once __DIR__ . '/app/controllers/dashboardController.php';
        $controller = new DashboardController($connection);
        $controller->dashboardDocente();
        break;

    default:
        header('Location: ' . BASE_URL . 'index.php?action=login');
        break;
}

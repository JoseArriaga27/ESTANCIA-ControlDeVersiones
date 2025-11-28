<?php
require_once __DIR__ . '/../models/reporteModel.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

class ReporteController {

    private $db;
    private $model;

    public function __construct($connection) {
        $this->db = $connection;
        $this->model = new ReporteModel($connection);
        if (!isset($_SESSION)) session_start();
    }

    // ======================================================
    // NORMALIZAR PARA PDF (FPDF — ISO-8859-1)
    // ======================================================
    private function normalizarTextoPDF($str) {
        if (!$str) return '';

        // Guiones raros → -
        $str = str_replace(['–','—','―'], '-', $str);

        // Quitar acentos
        $buscar  = ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ'];
        $reempl  = ['a','e','i','o','u','A','E','I','O','U','n','N'];
        $str = str_replace($buscar, $reempl, $str);

        // Convertir para FPDF
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
    }

    // ======================================================
    // NORMALIZAR PARA EXCEL (UTF-8)
    // ======================================================
    private function normalizarTextoExcel($str) {
        if (!$str) return '';

        $str = str_replace(['–','—','―'], '-', $str);
        $buscar  = ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ'];
        $reempl  = ['a','e','i','o','u','A','E','I','O','U','n','N'];
        return str_replace($buscar, $reempl, $str);
    }


    // =======================================================================
    // =======================   REPORTE 1  ==================================
    // =======================   ALUMNOS POR CARRERA  ========================
    // =======================================================================

    public function alumnosInscritosCarrera() {

        if (!in_array($_SESSION['usuario']['rol'], ['Administrador', 'Administrativo'])) {
            die("Acceso no autorizado");
        }

        $carreras = $this->model->obtenerCarreras();
        $periodos = $this->model->obtenerPeriodos();

        require __DIR__ . '/../views/Reportes/reporteInscritosCarrera.php';
    }

    // ====================== PDF ======================
    public function generarPDF_AlumnosCarrera() {

        if (empty($_POST['idCarrera']) || empty($_POST['idPeriodo'])) {
            die("Error: Seleccione carrera y periodo.");
        }

        $idCarrera = intval($_POST['idCarrera']);
        $idPeriodo = intval($_POST['idPeriodo']);

        $alumnos = $this->model->obtenerAlumnosPorCarreraPeriodo($idCarrera, $idPeriodo);
        $totalCarrera = $this->model->contarAlumnosCarrera($idCarrera);
        $totalSistema = $this->model->totalAlumnosSistema();

        $porcentaje = ($totalSistema > 0) ? ($totalCarrera / $totalSistema) * 100 : 0;

        $carrera = $this->normalizarTextoPDF($this->model->obtenerCarreraNombre($idCarrera));
        $periodo = $this->normalizarTextoPDF($this->model->obtenerPeriodoNombre($idPeriodo));

        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();

        // Barra superior
        $pdf->SetFillColor(10,42,67);
        $pdf->Rect(0,0,216,22,'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',14);
        $pdf->SetXY(0,5);
        $pdf->Cell(216,6,'SISTEMA ESCOLAR',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(216,6,'REPORTE DE ALUMNOS INSCRITOS POR CARRERA',0,1,'C');

        $pdf->Ln(15);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',12);

        $pdf->Cell(0,8,"Carrera: ".$carrera,0,1);
        $pdf->Cell(0,8,"Periodo: ".$periodo,0,1);
        $pdf->Ln(4);

        // Encabezados
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(10,42,67);
        $pdf->SetTextColor(255,255,255);

        $pdf->Cell(30,8,'Matricula',1,0,'C',true);
        $pdf->Cell(65,8,'Nombre',1,0,'C',true);
        $pdf->Cell(25,8,'Grupo',1,0,'C',true);
        $pdf->Cell(60,8,'Periodo',1,1,'C',true);

        // Datos
        $pdf->SetFont('Arial','',9);
        $pdf->SetTextColor(0,0,0);

        while ($a = $alumnos->fetch_assoc()) {
            $pdf->Cell(30,7,$this->normalizarTextoPDF($a['matricula']),1);
            $pdf->Cell(65,7,$this->normalizarTextoPDF($a['nombreCompleto']),1);
            $pdf->Cell(25,7,$this->normalizarTextoPDF($a['nombreGrupo']),1);
            $pdf->Cell(60,7,$this->normalizarTextoPDF($a['nombrePeriodo']),1);
            $pdf->Ln();
        }

        // Métricas
        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8,"Total inscritos: ".$totalCarrera,0,1);
        $pdf->Cell(0,8,"Porcentaje correspondiente del total de la plantilla escolar: ".number_format($porcentaje,2)." %",0,1);

        $pdf->Output();
        exit;
    }


    // ====================== EXCEL ======================
    public function generarExcel_AlumnosCarrera() {

        if (empty($_POST['idCarrera']) || empty($_POST['idPeriodo'])) {
            die("Error: Seleccione carrera y periodo.");
        }

        $idCarrera = intval($_POST['idCarrera']);
        $idPeriodo = intval($_POST['idPeriodo']);

        $alumnos = $this->model->obtenerAlumnosPorCarreraPeriodo($idCarrera, $idPeriodo);

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_alumnos_carrera.xls");

        echo "Matricula\tNombre\tGrupo\tPeriodo\n";

        while ($a = $alumnos->fetch_assoc()) {
            echo $this->normalizarTextoExcel($a['matricula'])."\t".
                 $this->normalizarTextoExcel($a['nombreCompleto'])."\t".
                 $this->normalizarTextoExcel($a['nombreGrupo'])."\t".
                 $this->normalizarTextoExcel($a['nombrePeriodo'])."\n";
        }

        exit;
    }



    // =======================================================================
    // =======================   REPORTE 2  ==================================
    // =======================   ALUMNOS GENERAL  ============================
    // =======================================================================

    public function alumnosGeneral() {

        if (!in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])) {
            die("Acceso no autorizado");
        }

        $alumnos = $this->model->obtenerAlumnosGeneral();

        require __DIR__ . '/../views/reportes/alumnosGeneralView.php';
    }


    // ======================= PDF =======================
    public function generarPDF_AlumnosGeneral() {

        $alumnos = $this->model->obtenerAlumnosGeneral();

        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();

        // Barra superior
        $pdf->SetFillColor(10,42,67);
        $pdf->Rect(0,0,216,22,'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',14);
        $pdf->SetXY(0,5);
        $pdf->Cell(216,6,'SISTEMA ESCOLAR',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(216,6,'LISTADO GENERAL DE ALUMNOS',0,1,'C');

        $pdf->Ln(10);

        // Encabezados
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(10,42,67);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(25,8,'Matricula',1,0,'C',true);
        $pdf->Cell(60,8,'Nombre',1,0,'C',true);
        $pdf->Cell(55,8,'Carrera',1,0,'C',true);
        $pdf->Cell(50,8,'Correo',1,1,'C',true);

        // Datos
        $pdf->SetFont('Arial','',9);
        $pdf->SetTextColor(0,0,0);

        while ($a = $alumnos->fetch_assoc()) {
            $pdf->Cell(25,7,$this->normalizarTextoPDF($a['matricula']),1);
            $pdf->Cell(60,7,$this->normalizarTextoPDF($a['nombreCompleto']),1);
            $pdf->Cell(55,7,$this->normalizarTextoPDF($a['nombreCarrera']),1);
            $pdf->Cell(50,7,$this->normalizarTextoPDF($a['correo']),1);
            $pdf->Ln();
        }

        $pdf->Output();
        exit;
    }


    // ======================= EXCEL =======================
    public function generarExcel_AlumnosGeneral() {

        $alumnos = $this->model->obtenerAlumnosGeneral();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=alumnos_general.xls");

        echo "Matricula\tNombre\tCarrera\tCorreo\n";

        while ($a = $alumnos->fetch_assoc()) {
            echo $this->normalizarTextoExcel($a['matricula'])."\t".
                 $this->normalizarTextoExcel($a['nombreCompleto'])."\t".
                 $this->normalizarTextoExcel($a['nombreCarrera'])."\t".
                 $this->normalizarTextoExcel($a['correo'])."\n";
        }

        exit;
    }
    public function calificacionesPorGrupo() {

    if (!in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo','Docente'])) {
        die("Acceso no autorizado");
    }

    $grupos = $this->model->obtenerGruposActivos();

    require __DIR__ . '/../views/reportes/calificacionesGrupoView.php';
}

public function generarPDF_CalificacionesGrupo() {

    if (empty($_POST['idGrupo'])) die("Error: Selecciona un grupo.");

    $idGrupo = intval($_POST['idGrupo']);
    $datos = $this->model->obtenerCalificacionesGrupo($idGrupo);

    require_once __DIR__ . '/../../lib/fpdf/fpdf.php';
    $pdf = new FPDF('L', 'mm', 'Letter');
    $pdf->AddPage();

    // Encabezado
    $pdf->SetFillColor(10,42,67);
    $pdf->Rect(0,0,280,20,'F');
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(280,10,'REPORTE DE CALIFICACIONES POR GRUPO',0,1,'C');
    $pdf->Ln(5);

    // Encabezados tabla
    $pdf->SetFont('Arial','B',9);
    $pdf->SetFillColor(10,42,67);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(25,8,'Matricula',1,0,'C',true);
    $pdf->Cell(55,8,'Alumno',1,0,'C',true);
    $pdf->Cell(55,8,'Materia',1,0,'C',true);
    $pdf->Cell(20,8,'P1',1,0,'C',true);
    $pdf->Cell(20,8,'P2',1,0,'C',true);
    $pdf->Cell(20,8,'P3',1,0,'C',true);
    $pdf->Cell(20,8,'Final',1,1,'C',true);

    $pdf->SetFont('Arial','',8);
    $pdf->SetTextColor(0,0,0);

    while($row = $datos->fetch_assoc()) {

        $pdf->Cell(25,7,$this->normalizarTextoPDF($row['matricula']),1);
        $pdf->Cell(55,7,$this->normalizarTextoPDF($row['nombreCompleto']),1);
        $pdf->Cell(55,7,$this->normalizarTextoPDF($row['nombreMateria']),1);

        $pdf->Cell(20,7,$row['calificacionParcial1'],1,0,'C');
        $pdf->Cell(20,7,$row['calificacionParcial2'],1,0,'C');
        $pdf->Cell(20,7,$row['calificacionParcial3'],1,0,'C');
        $pdf->Cell(20,7,$row['calificacionFinal'],1,1,'C');
    }


    $pdf->Output();
    exit;
}

public function generarExcel_CalificacionesGrupo() {

    if (empty($_POST['idGrupo'])) die("Error: Selecciona un grupo.");

    $idGrupo = intval($_POST['idGrupo']);
    $datos = $this->model->obtenerCalificacionesGrupo($idGrupo);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=calificaciones_grupo.xls");

    echo "Matricula\tAlumno\tMateria\tP1\tP2\tP3\tFinal\n";

    while ($row = $datos->fetch_assoc()) {

        echo $this->normalizarTextoExcel($row['matricula'])."\t".
            $this->normalizarTextoExcel($row['nombreCompleto'])."\t".
            $this->normalizarTextoExcel($row['nombreMateria'])."\t".
            $row['calificacionParcial1']."\t".
            $row['calificacionParcial2']."\t".
            $row['calificacionParcial3']."\t".
            $row['calificacionFinal']."\n";
    }

    exit;
}
public function estadisticasPDF() {

    require_once __DIR__ . '/../models/dashboardModel.php';
    $dash = new DashboardModel($this->db);

    $alumnosCarrera = $dash->getAlumnosPorCarrera();
    $alumnosGrupo = $dash->getAlumnosPorGrupo();
    $materiasRanking = $dash->getMateriasMasAsignadas();
    $docentesRanking = $dash->getDocentesMasAsignados();
    $inscritosVsTotales = $dash->getInscritosVsTotales();
    $generoAlumnos = $dash->getGeneroAlumnos();

    require __DIR__ . '/../views/reportes/estadisticasPDFGenerator.php';
}
public function generarPDF_Estadisticas() {

    if (!isset($_POST['img1'])) die("Error: No se recibieron las imágenes.");

    $imgs = [];
    for ($i=1; $i<=6; $i++) {
        $imgData = $_POST["img$i"];
        $imgData = str_replace('data:image/png;base64,', '', $imgData);
        $imgData = base64_decode($imgData);

        $filename = __DIR__ . "/../../temp/chart$i.png";
        file_put_contents($filename, $imgData);
        $imgs[$i] = $filename;
    }

    require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

    $pdf = new FPDF('P','mm','Letter');
    $pdf->AddPage();

    // Barra azul
    $pdf->SetFillColor(10,42,67);
    $pdf->Rect(0,0,216,20,'F');
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(0,5);
    $pdf->Cell(216,10,"REPORTE DE ESTADISTICAS DEL SISTEMA",0,1,'C');

    $pdf->Ln(10);

    // INSERTAR 6 GRAFICAS (dos por fila)
    $w = 95;
    $h = 70;

    // FILA 1
    $pdf->Image($imgs[1], 10, 35, $w, $h);
    $pdf->Image($imgs[2], 110, 35, $w, $h);

    // FILA 2
    $y = 35 + $h + 10;
    $pdf->Image($imgs[3], 10, $y, $w, $h);
    $pdf->Image($imgs[4], 110, $y, $w, $h);

    // SALTO Página 2
    $pdf->AddPage();
    $pdf->Image($imgs[5], 10, 35, $w, $h);
    $pdf->Image($imgs[6], 110, 35, $w, $h);

    $pdf->Output();

    // BORRAR IMÁGENES TEMPORALES
    foreach ($imgs as $file) unlink($file);

    exit;
}

/* ======================================================
   REPORTE: KARDEX INDIVIDUAL
====================================================== */
public function generarKardex() {

    // ============================================
    // CONTROL DE ACCESO
    // ============================================

    $rol = $_SESSION['usuario']['rol'] ?? null;
    $idUsuario = $_SESSION['usuario']['id'] ?? null;

    // Si viene un alumno → validar que sea SU propio kardex
    if ($rol === 'Alumno') {

        $idAlumnoSesion = $this->model->obtenerIdAlumnoPorUsuario($idUsuario);

        if (!$idAlumnoSesion) {
            die("Error: No se encontró tu información.");
        }

        if (!isset($_GET['id']) || intval($_GET['id']) !== intval($idAlumnoSesion)) {
            die("Acceso no autorizado");
        }
    }
    else if (!in_array($rol, ['Administrador', 'Administrativo'])) {
        die("Acceso no autorizado");
    }

    // ============================================
    // VALIDAR ID DEL ALUMNO
    // ============================================

    if (empty($_GET['id'])) {
        die("Error: No se recibió el alumno.");
    }

    $idAlumno = intval($_GET['id']);

    // Obtener datos del alumno
    $alumno = $this->model->obtenerDatosAlumno($idAlumno);
    if (!$alumno) die("Error: Alumno no encontrado.");

    // Obtener su kardex (materias y calificaciones)
    $kardex = $this->model->obtenerKardex($idAlumno);

    require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

    $pdf = new FPDF('P','mm','Letter');
    $pdf->AddPage();

    // Barra superior
    $pdf->SetFillColor(10,42,67);
    $pdf->Rect(0,0,216,22,'F');
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',14);
    $pdf->SetXY(0,5);
    $pdf->Cell(216,8,'SISTEMA ESCOLAR - KARDEX DE ALUMNO',0,1,'C');

    $pdf->Ln(10);

    // ============= DATOS DEL ALUMNO =============
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0,0,0);

    $pdf->Cell(0,7,"Nombre:  ".$this->normalizarTextoPDF($alumno['nombreCompleto']),0,1);
    $pdf->Cell(0,7,"Matricula: ".$this->normalizarTextoPDF($alumno['matricula']),0,1);
    $pdf->Cell(0,7,"Carrera:  ".$this->normalizarTextoPDF($alumno['nombreCarrera']),0,1);

    $pdf->Ln(5);

    // ============= TABLA =============
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(10,42,67);
    $pdf->SetTextColor(255,255,255);

    $pdf->Cell(80,8,'Materia',1,0,'C',true);
    $pdf->Cell(25,8,'P1',1,0,'C',true);
    $pdf->Cell(25,8,'P2',1,0,'C',true);
    $pdf->Cell(25,8,'P3',1,0,'C',true);
    $pdf->Cell(25,8,'Final',1,1,'C',true);

    $pdf->SetFont('Arial','',9);
    $pdf->SetTextColor(0,0,0);

    // ============================================
    // VARIABLES PARA PROMEDIO GENERAL
    // ============================================
    $sumaFinal = 0;
    $conteoMaterias = 0;

    // ============= FILAS =============
    while($row = $kardex->fetch_assoc()) {

        $pdf->Cell(80,7,$this->normalizarTextoPDF($row['nombreMateria']),1,0,'L');
        $pdf->Cell(25,7,$row['calificacionParcial1'],1,0,'C');
        $pdf->Cell(25,7,$row['calificacionParcial2'],1,0,'C');
        $pdf->Cell(25,7,$row['calificacionParcial3'],1,0,'C');
        $pdf->Cell(25,7,$row['calificacionFinal'],1,1,'C');

        // ===== ACUMULAR PROMEDIO EN ESCALA 0–10 =====
        if (!is_null($row['calificacionFinal']) && $row['calificacionFinal'] !== '' && is_numeric($row['calificacionFinal'])) {
            $sumaFinal += floatval($row['calificacionFinal']);
            $conteoMaterias++;
        }
    }

    // ============================================
    // PROMEDIO GENERAL
    // ============================================
    $pdf->Ln(8);
    $pdf->SetFont('Arial','B',12);

    $promedioGeneral = ($conteoMaterias > 0)
        ? ($sumaFinal / $conteoMaterias)
        : 0;

    $pdf->Cell(0,10,"Promedio General: ".number_format($promedioGeneral,2),0,1,'L');

    $pdf->Output();
    exit;
}




public function generarKardexAlumno() {

    if ($_SESSION['usuario']['rol'] !== 'Alumno') {
        die("Acceso no autorizado");
    }

    $idUsuario = $_SESSION['usuario']['id'];

    $idAlumno = $this->model->obtenerIdAlumnoPorUsuario($idUsuario);

    if (!$idAlumno) {
        die("Error: No se encontró tu información.");
    }

    $_GET['id'] = $idAlumno;
    return $this->generarKardex();
}



}

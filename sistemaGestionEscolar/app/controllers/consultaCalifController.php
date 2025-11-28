<?php
require_once __DIR__ . '/../models/consultaCalifModel.php';
require_once __DIR__ . '/../models/capturaCalifModel.php';

class ConsultaCalifController {

    private $connection;
    private $model;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->model = new ConsultaCalifModel($connection);
        if (!isset($_SESSION)) session_start();
    }

    public function consulta() {

        $rol = $_SESSION['usuario']['rol'];
        $idUsuario = $_SESSION['usuario']['id'] ?? null;
        $idDocente = $_SESSION['usuario']['idDocente'] ?? null;

        // ===== Obtener lista =====
        switch ($rol) {
            case 'Alumno':
                $datos = $this->model->obtenerCalificacionesAlumno($idUsuario);
                break;
            case 'Docente':
                $datos = $this->model->obtenerCalificacionesDocente($idDocente);
                break;
            case 'Administrador':
            case 'Administrativo':
                $datos = $this->model->obtenerTodasLasCalificaciones();
                break;
            default:
                die("Rol no permitido.");
        }

        // ===== Si se seleccionó editar =====
        $califEditar = null;

        if (isset($_GET['edit']) && isset($_GET['ins']) && isset($_GET['mat'])) {

            $idIns = intval($_GET['ins']);
            $idMat = intval($_GET['mat']);


            $query = $this->connection->prepare("
                SELECT 
                    c.*,
                    m.nombreMateria,
                    g.nombreGrupo,
                    u.matricula,
                    CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS alumno,
                    p.nombrePeriodo

                FROM calificaciones c
                INNER JOIN inscripciones i ON i.idInscripcion = c.idInscripcion
                INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
                INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
                INNER JOIN grupos g ON g.idGrupo = i.idGrupo
                INNER JOIN materias m ON m.idMateria = c.idMateria
                INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo

                WHERE c.idInscripcion = ? AND c.idMateria = ?
                LIMIT 1
            ");

            $query->bind_param("ii", $idIns, $idMat);
            $query->execute();
            $califEditar = $query->get_result()->fetch_assoc();
        }

        // ❗❗ ESTA ES LA LLAVE QUE TE FALTABA
        include __DIR__ . '/../views/Calificaciones/consultaCalifView.php';
    }  //  ← ← ← AQUÍ SE CIERRA LA FUNCIÓN consulta()



    // =================================================== 
    // ACTUALIZAR CALIFICACIÓN
    // ===================================================
    public function actualizar() {

        if (!in_array($_SESSION['usuario']['rol'], ['Administrador','Administrativo'])) {
            die("NO AUTORIZADO");
        }

        $idIns = $_POST['idInscripcion'];
        $idMat = $_POST['idMateria'];

        $p1 = floatval($_POST['p1']);
        $p2 = floatval($_POST['p2']);
        $p3 = floatval($_POST['p3']);

        $final = ($p1 + $p2 + $p3) / 3;

        $model = new CapturaCalifModel($this->connection);
        $model->guardarCalificacion($idIns, $idMat, $p1, $p2, $p3, $final);

        header("Location: index.php?action=consultaCalificaciones&msg=ok");
        exit;
    }
}

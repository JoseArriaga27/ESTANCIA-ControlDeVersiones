<?php
class ReporteModel {

    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    public function obtenerAlumnosConCarrera() {
        $query = $this->db->query("
            SELECT 
                a.idAlumno,
                u.matricula,
                CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS nombreCompleto,
                c.nombreCarrera AS carrera
            FROM alumnos a
            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
            LEFT JOIN carreras c ON c.idCarrera = a.idCarrera
            ORDER BY nombreCompleto
        ");
        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerKardex($idAlumno) {
        return $this->db->query("
            SELECT 
                m.nombreMateria,
                c.calificacionParcial1,
                c.calificacionParcial2,
                c.calificacionParcial3,
                c.calificacionFinal,
                p.nombrePeriodo
            FROM calificaciones c
            INNER JOIN materias m ON m.idMateria = c.idMateria
            INNER JOIN inscripciones i ON i.idInscripcion = c.idInscripcion
            INNER JOIN grupos g ON g.idGrupo = i.idGrupo
            INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
            WHERE i.idAlumno = $idAlumno
            ORDER BY p.idPeriodo, m.nombreMateria
        ");
    }

    public function obtenerDatosAlumno($idAlumno) {
        return $this->db->query("
            SELECT 
                a.idAlumno,
                u.matricula,
                CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS nombreCompleto,
                c.nombreCarrera,
                g.nombreGrupo
            FROM alumnos a
            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
            LEFT JOIN carreras c ON c.idCarrera = a.idCarrera
            LEFT JOIN inscripciones i ON i.idAlumno = a.idAlumno
            LEFT JOIN grupos g ON g.idGrupo = i.idGrupo
            WHERE a.idAlumno = $idAlumno
            LIMIT 1
        ")->fetch_assoc();
    }

    // ============================================================
    // REPORTE: Alumnos inscritos por carrera y periodo
    // ============================================================

    public function obtenerCarreras() {
        return $this->db->query("SELECT * FROM carreras ORDER BY nombreCarrera ASC");
    }

    public function obtenerPeriodos() {
        return $this->db->query("SELECT * FROM periodosEscolares ORDER BY idPeriodo DESC");
    }

    public function obtenerAlumnosPorCarreraPeriodo($idCarrera, $idPeriodo) {
        $sql = "
            SELECT 
                u.matricula,
                CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS nombreCompleto,
                g.nombreGrupo,
                p.nombrePeriodo
            FROM inscripciones i
            INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
            INNER JOIN grupos g ON g.idGrupo = i.idGrupo
            INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
            WHERE a.idCarrera = ? AND g.idPeriodo = ?
            ORDER BY nombreCompleto ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $idCarrera, $idPeriodo);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function contarAlumnosCarrera($idCarrera) {
        $sql = "SELECT COUNT(*) AS total FROM alumnos WHERE idCarrera = $idCarrera";
        return $this->db->query($sql)->fetch_assoc()['total'];
    }

    public function totalAlumnosSistema() {
        $sql = "SELECT COUNT(*) AS total FROM alumnos";
        return $this->db->query($sql)->fetch_assoc()['total'];
    }

    // ============================================================
    // NOMBRES DE CARRERA Y PERIODO (CORREGIDOS)
    // ============================================================

    public function obtenerCarreraNombre($idCarrera) {
        $stmt = $this->db->prepare("
            SELECT nombreCarrera 
            FROM carreras 
            WHERE idCarrera = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $idCarrera);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['nombreCarrera'] : '';
    }

    public function obtenerPeriodoNombre($idPeriodo) {
        $stmt = $this->db->prepare("
            SELECT nombrePeriodo 
            FROM periodosEscolares 
            WHERE idPeriodo = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $idPeriodo);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['nombrePeriodo'] : '';
    }
    public function obtenerAlumnosGeneral() {
    return $this->db->query("
        SELECT 
            u.matricula,
            CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS nombreCompleto,
            c.nombreCarrera,
            u.correo
        FROM alumnos a
        INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
        LEFT JOIN carreras c ON c.idCarrera = a.idCarrera
        ORDER BY nombreCompleto ASC
    ");
}

public function obtenerGruposActivos() {
    return $this->db->query("
        SELECT g.idGrupo, g.nombreGrupo, c.nombreCarrera, p.nombrePeriodo
        FROM grupos g
        INNER JOIN carreras c ON c.idCarrera = g.idCarrera
        INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
        ORDER BY p.idPeriodo DESC, g.nombreGrupo ASC
    ");
}

public function obtenerCalificacionesGrupo($idGrupo) {

    $sql = "
        SELECT 
            u.matricula,
            CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS nombreCompleto,
            m.nombreMateria,
            c.calificacionParcial1,
            c.calificacionParcial2,
            c.calificacionParcial3,
            c.calificacionFinal
        FROM calificaciones c
        INNER JOIN inscripciones i ON i.idInscripcion = c.idInscripcion
        INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
        INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
        INNER JOIN materias m ON m.idMateria = c.idMateria
        WHERE i.idGrupo = ?
        ORDER BY nombreCompleto, m.nombreMateria
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $idGrupo);
    $stmt->execute();
    return $stmt->get_result();
}
public function obtenerIdAlumnoPorUsuario($idUsuario) {
    $sql = "SELECT idAlumno FROM alumnos WHERE idUsuario = $idUsuario LIMIT 1";
    $res = $this->db->query($sql);
    return ($res && $res->num_rows > 0) ? $res->fetch_assoc()['idAlumno'] : null;
}



}

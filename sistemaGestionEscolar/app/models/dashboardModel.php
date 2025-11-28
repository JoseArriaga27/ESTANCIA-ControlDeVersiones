<?php
class DashboardModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    /* =============================
       1. Alumnos por Carrera
    ============================== */
    public function getAlumnosPorCarrera() {
        $sql = "
            SELECT c.nombreCarrera, COUNT(a.idAlumno) AS total
            FROM alumnos a
            LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
            GROUP BY c.nombreCarrera
        ";
        return $this->connection->query($sql);
    }

    /* =============================
       2. Alumnos por Grupo
    ============================== */
    public function getAlumnosPorGrupo() {
        $sql = "
            SELECT g.nombreGrupo, COUNT(i.idInscripcion) AS total
            FROM inscripciones i
            LEFT JOIN grupos g ON i.idGrupo = g.idGrupo
            GROUP BY g.nombreGrupo
        ";
        return $this->connection->query($sql);
    }

    /* =============================
       3. Materias más asignadas
    ============================== */
    public function getMateriasMasAsignadas() {
        $sql = "
            SELECT m.nombreMateria, COUNT(a.idAsignacion) AS total
            FROM asignaciones a
            LEFT JOIN materias m ON a.idMateria = m.idMateria
            GROUP BY m.nombreMateria
            ORDER BY total DESC
        ";
        return $this->connection->query($sql);
    }

    /* =============================
       4. Docentes con más asignaciones
    ============================== */
    public function getDocentesMasAsignados() {
        $sql = "
            SELECT CONCAT(u.nombres,' ',u.apePaterno) AS docente,
                   COUNT(a.idAsignacion) AS total
            FROM asignaciones a
            LEFT JOIN docentes d ON a.idDocente = d.idDocente
            LEFT JOIN usuarios u ON d.idUsuario = u.idUsuario
            GROUP BY docente
            ORDER BY total DESC
        ";
        return $this->connection->query($sql);
    }

    /* =============================
       5. Inscritos vs Totales
    ============================== */
    public function getInscritosVsTotales() {
        $sql = "
            SELECT
                (SELECT COUNT(*) FROM alumnos) AS totalAlumnos,
                (SELECT COUNT(DISTINCT idAlumno) FROM inscripciones) AS inscritos
        ";
        return $this->connection->query($sql)->fetch_assoc();
    }

    /* =============================
       6. Género de Alumnos
    ============================== */
    public function getGeneroAlumnos() {
        $sql = "
            SELECT sexo, COUNT(*) AS total
            FROM usuarios
            WHERE rol = 'Alumno'
            GROUP BY sexo
        ";
        return $this->connection->query($sql);
    }
    
    public function obtenerTodasLasAsignaciones() {
        $sql = "
            SELECT 
                a.idAsignacion,
                a.idMateria,
                a.idGrupo,
                m.nombreMateria,
                g.nombreGrupo,
                p.nombrePeriodo,
                d.idDocente,
                u.nombres AS nombreDocente,
                u.apePaterno AS apePatDocente,
                u.apeMaterno AS apeMatDocente
            FROM asignaciones a
            INNER JOIN materias m ON a.idMateria = m.idMateria
            INNER JOIN grupos g ON a.idGrupo = g.idGrupo
            INNER JOIN periodosEscolares p ON a.idPeriodo = p.idPeriodo
            INNER JOIN docentes d ON a.idDocente = d.idDocente
            INNER JOIN usuarios u ON d.idUsuario = u.idUsuario
            ORDER BY g.nombreGrupo, m.nombreMateria
        ";
        return $this->connection->query($sql);
    }


    /* ============================================================
    DISTRIBUCIÓN DE CALIFICACIONES (ESCALA 1–10)
    ============================================================ */
    public function getDistribucionCalificaciones($idDocente) {

        if (empty($idDocente)) {
            return [];
        }

        $sql = "
            SELECT 
                m.nombreMateria,
                SUM(CASE WHEN c.calificacionFinal < 7 THEN 1 ELSE 0 END) AS reprobados,
                SUM(CASE WHEN c.calificacionFinal >= 7 AND c.calificacionFinal < 8 THEN 1 ELSE 0 END) AS setentas,
                SUM(CASE WHEN c.calificacionFinal >= 8 AND c.calificacionFinal < 9 THEN 1 ELSE 0 END) AS ochentas,
                SUM(CASE WHEN c.calificacionFinal >= 9 THEN 1 ELSE 0 END) AS noventas

            FROM asignaciones a
            INNER JOIN materias m ON a.idMateria = m.idMateria
            INNER JOIN inscripciones i ON a.idGrupo = i.idGrupo

            INNER JOIN calificaciones c 
                ON i.idInscripcion = c.idInscripcion
            AND c.idMateria = a.idMateria

            WHERE a.idDocente = $idDocente
            GROUP BY m.nombreMateria
        ";

        $result = $this->connection->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    /* ============================================================
    AVANCE DE CAPTURA DE CALIFICACIONES
    ============================================================ */
public function getAvanceCapturaGlobal($idDocente) {

    if (empty($idDocente)) return [];

    $sql = "
        SELECT 
            COUNT(c.idCalificacion) AS totalRegistros,

            SUM(CASE WHEN c.calificacionParcial1 IS NOT NULL THEN 1 ELSE 0 END) AS p1,
            SUM(CASE WHEN c.calificacionParcial2 IS NOT NULL THEN 1 ELSE 0 END) AS p2,
            SUM(CASE WHEN c.calificacionParcial3 IS NOT NULL THEN 1 ELSE 0 END) AS p3,
            SUM(CASE WHEN c.calificacionFinal   IS NOT NULL THEN 1 ELSE 0 END) AS finalCal

        FROM asignaciones a
        INNER JOIN inscripciones i ON a.idGrupo = i.idGrupo
        INNER JOIN calificaciones c 
            ON i.idInscripcion = c.idInscripcion
           AND c.idMateria = a.idMateria

        WHERE a.idDocente = $idDocente
    ";

    $result = $this->connection->query($sql);
    return $result ? $result->fetch_assoc() : [];
}

public function getAsignacionesDocente($idDocente) {
    $sql = "
        SELECT idMateria, idGrupo
        FROM asignaciones
        WHERE idDocente = $idDocente
        ORDER BY idMateria ASC
    ";
    $result = $this->connection->query($sql);

    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


}
?>

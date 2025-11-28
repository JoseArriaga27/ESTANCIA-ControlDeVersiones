<?php
class DocenteModel {

    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    // ============================================================
    // OBTENER idDocente A PARTIR DEL idUsuario (docente logueado)
    // ============================================================
    public function obtenerIdDocentePorUsuario($idUsuario) {
        $sql = "SELECT idDocente FROM docentes WHERE idUsuario = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['idDocente'] : null;
    }

    // ============================================================
    // OBTENER GRUPOS DEL DOCENTE LOGUEADO (via asignaciones)
    // ============================================================
    public function obtenerGruposDelDocente($idDocente) {
        $sql = "
            SELECT 
                g.idGrupo,
                g.nombreGrupo,
                c.nombreCarrera,
                p.nombrePeriodo
            FROM asignaciones a
            INNER JOIN grupos g ON g.idGrupo = a.idGrupo
            INNER JOIN carreras c ON c.idCarrera = g.idCarrera
            INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
            WHERE a.idDocente = ?
            GROUP BY g.idGrupo
            ORDER BY p.idPeriodo DESC, g.nombreGrupo ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idDocente);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ============================================================
    // OBTENER ALUMNOS DE UN GRUPO
    // ============================================================
    public function obtenerAlumnosDeGrupo($idGrupo) {
        $sql = "
            SELECT 
                u.matricula,
                CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS nombreCompleto,
                c.nombreCarrera
            FROM inscripciones i
            INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
            INNER JOIN carreras c ON c.idCarrera = a.idCarrera
            WHERE i.idGrupo = ?
            ORDER BY nombreCompleto ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idGrupo);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function obtenerMateriasDocente($idDocente) {

            $sql = "
                SELECT 
                    m.idMateria,
                    m.nombreMateria,
                    g.nombreGrupo,
                    c.nombreCarrera,
                    p.nombrePeriodo
                FROM asignaciones a
                INNER JOIN materias m ON m.idMateria = a.idMateria
                INNER JOIN grupos g ON g.idGrupo = a.idGrupo
                INNER JOIN carreras c ON c.idCarrera = g.idCarrera
                INNER JOIN periodosEscolares p ON p.idPeriodo = a.idPeriodo
                WHERE a.idDocente = ?
                ORDER BY p.idPeriodo DESC, g.nombreGrupo ASC
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $idDocente);
            $stmt->execute();
            return $stmt->get_result();
        }
        public function listarDocentes() {
            return $this->db->query("
                SELECT 
                    d.idDocente,
                    u.correo,
                    CONCAT(u.nombres, ' ', u.apePaterno, ' ', u.apeMaterno) AS nombreCompleto
                FROM docentes d
                INNER JOIN usuarios u ON u.idUsuario = d.idUsuario
                ORDER BY nombreCompleto ASC
            ");
        }

}

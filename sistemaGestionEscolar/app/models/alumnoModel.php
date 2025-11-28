<?php
class AlumnoModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function obtenerAlumnos() {
        $sql = "SELECT a.idAlumno, u.matricula, CONCAT(u.nombres,' ',u.apePaterno) AS nombreCompleto, 
                       c.nombreCarrera, c.idCarrera, u.correo
                FROM alumnos a
                INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
                LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
                ORDER BY a.idAlumno ASC";
        return $this->connection->query($sql);
    }

    public function obtenerCarreras() {
        return $this->connection->query("SELECT * FROM carreras ORDER BY nombreCarrera ASC");
    }

    public function obtenerUsuariosDisponibles() {
        $query = "
            SELECT 
                a.idAlumno,
                CONCAT(u.nombres, ' ', u.apePaterno, ' ', IFNULL(u.apeMaterno, '')) AS nombreCompleto,
                u.matricula,
                u.correo,
                IFNULL(c.nombreCarrera, 'Sin carrera') AS nombreCarrera,
                c.idCarrera
            FROM alumnos a
            INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
            LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
            LEFT JOIN inscripciones i ON a.idAlumno = i.idAlumno
            WHERE i.idAlumno IS NULL
            ORDER BY u.nombres ASC
        ";
        return $this->connection->query($query);
    }

    public function agregarAlumno($idUsuario, $idCarrera) {
        $stmt = $this->connection->prepare("INSERT INTO alumnos (idUsuario, idCarrera) VALUES (?, ?)");
        $stmt->bind_param("ii", $idUsuario, $idCarrera);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->connection->prepare("SELECT * FROM alumnos WHERE idAlumno=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editarAlumno($idAlumno, $idCarrera) {
        $stmt = $this->connection->prepare("UPDATE alumnos SET idCarrera=? WHERE idAlumno=?");
        $stmt->bind_param("ii", $idCarrera, $idAlumno);
        return $stmt->execute();
    }

    public function eliminarAlumno($id) {
        $stmt = $this->connection->prepare("DELETE FROM alumnos WHERE idAlumno=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerGruposPorCarrera($idCarrera) {
        $stmt = $this->connection->prepare("
            SELECT g.idGrupo, g.nombreGrupo, c.nombreCarrera
            FROM grupos g
            INNER JOIN carreras c ON g.idCarrera = c.idCarrera
            WHERE g.idCarrera = ?
        ");
        $stmt->bind_param("i", $idCarrera);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerGrupos() {
        $sql = "SELECT g.idGrupo, g.nombreGrupo, c.nombreCarrera, p.nombrePeriodo
                FROM grupos g
                LEFT JOIN carreras c ON g.idCarrera = c.idCarrera
                LEFT JOIN periodosEscolares p ON g.idPeriodo = p.idPeriodo";
        return $this->connection->query($sql);
    }

    public function inscribirAlumno($idAlumno, $idGrupo) {
        $fecha = date('Y-m-d');
        $stmt = $this->connection->prepare("
            INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion)
            SELECT ?, ?, ? FROM DUAL
            WHERE NOT EXISTS (
                SELECT 1 FROM inscripciones WHERE idAlumno=? AND idGrupo=?
            )
        ");
        $stmt->bind_param("iisii", $idAlumno, $idGrupo, $fecha, $idAlumno, $idGrupo);
        return $stmt->execute();
    }

    public function obtenerInscripciones() {
        $sql = "SELECT i.idInscripcion, CONCAT(u.nombres,' ',u.apePaterno) AS alumno, g.nombreGrupo, 
                       c.nombreCarrera, i.fechaInscripcion
                FROM inscripciones i
                INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
                INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
                INNER JOIN grupos g ON g.idGrupo = i.idGrupo
                LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
                ORDER BY i.idInscripcion DESC";
        return $this->connection->query($sql);
    }
}
?>

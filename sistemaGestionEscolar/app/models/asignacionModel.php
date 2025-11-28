<?php
class AsignacionModel {
    private $connection;
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function syncDocentes() {
        // Insertar SOLO idUsuario porque la tabla docentes solo tiene 2 columnas
        $sql = "INSERT INTO docentes (idUsuario)
                SELECT u.idUsuario
                FROM usuarios u
                LEFT JOIN docentes d ON d.idUsuario = u.idUsuario
                WHERE u.rol = 'Docente' AND d.idDocente IS NULL";
        
        return $this->connection->query($sql);
    }


    public function obtenerDocentes() {
        $sql = "SELECT d.idDocente, CONCAT(u.nombres,' ',u.apePaterno) AS nombre
                FROM docentes d
                INNER JOIN usuarios u ON u.idUsuario = d.idUsuario
                ORDER BY u.nombres ASC";
        return $this->connection->query($sql);
    }

    public function obtenerMaterias() {
        return $this->connection->query("SELECT idMateria, nombreMateria FROM materias ORDER BY nombreMateria ASC");
    }

    public function obtenerGrupos() {
        return $this->connection->query("SELECT idGrupo, nombreGrupo FROM grupos ORDER BY nombreGrupo ASC");
    }

    public function obtenerPeriodos() {
        return $this->connection->query("SELECT idPeriodo, nombrePeriodo FROM periodosEscolares ORDER BY fechaInicio DESC");
    }

    public function agregarAsignacion($idDocente, $idMateria, $idGrupo, $idPeriodo) {
        $stmt = $this->connection->prepare("INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $idDocente, $idMateria, $idGrupo, $idPeriodo);
        return $stmt->execute();
    }

    public function editarAsignacion($idAsignacion, $idDocente, $idMateria, $idGrupo, $idPeriodo) {
        $stmt = $this->connection->prepare("UPDATE asignaciones SET idDocente=?, idMateria=?, idGrupo=?, idPeriodo=? WHERE idAsignacion=?");
        $stmt->bind_param("iiiii", $idDocente, $idMateria, $idGrupo, $idPeriodo, $idAsignacion);
        return $stmt->execute();
    }

    public function eliminarAsignacion($id) {
        $stmt = $this->connection->prepare("DELETE FROM asignaciones WHERE idAsignacion = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerAsignaciones() {
        $sql = "SELECT a.idAsignacion,
                       CONCAT(u.nombres,' ',u.apePaterno) AS docente,
                       m.nombreMateria,
                       g.nombreGrupo,
                       p.nombrePeriodo
                FROM asignaciones a
                INNER JOIN docentes d ON d.idDocente = a.idDocente
                INNER JOIN usuarios u ON u.idUsuario = d.idUsuario
                INNER JOIN materias m ON m.idMateria = a.idMateria
                INNER JOIN grupos g ON g.idGrupo = a.idGrupo
                INNER JOIN periodosEscolares p ON p.idPeriodo = a.idPeriodo
                ORDER BY a.idAsignacion ASC";
        return $this->connection->query($sql);
    }

    public function obtenerAsignacionPorId($id) {
        $stmt = $this->connection->prepare("SELECT idAsignacion, idDocente, idMateria, idGrupo, idPeriodo FROM asignaciones WHERE idAsignacion=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    } 

    
}

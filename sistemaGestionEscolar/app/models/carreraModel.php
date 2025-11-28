<?php
class CarreraModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function obtenerCarreras() {
        return $this->connection->query("SELECT * FROM carreras ORDER BY idCarrera ASC");
    }

    public function agregarCarrera($nombre, $descripcion) {
        $stmt = $this->connection->prepare("INSERT INTO carreras (nombreCarrera, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->connection->prepare("SELECT * FROM carreras WHERE idCarrera = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editarCarrera($id, $nombre, $descripcion) {
        $stmt = $this->connection->prepare("UPDATE carreras SET nombreCarrera=?, descripcion=? WHERE idCarrera=?");
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        return $stmt->execute();
    }

    /**
     * Verifica si la carrera tiene alumnos o grupos asociados.
     */
    private function tieneDependencias($idCarrera) {
        //¿Existen alumnos registrados en esta carrera?
        $query1 = $this->connection->prepare("SELECT COUNT(*) AS total FROM alumnos WHERE idCarrera = ?");
        $query1->bind_param("i", $idCarrera);
        $query1->execute();
        $alumnos = $query1->get_result()->fetch_assoc()['total'];
        $query1->close();

        // ¿Existen grupos con inscripciones para esta carrera?
        $query2 = $this->connection->prepare("
            SELECT COUNT(*) AS total
            FROM grupos g
            INNER JOIN inscripciones i ON g.idGrupo = i.idGrupo
            WHERE g.idCarrera = ?
        ");
        $query2->bind_param("i", $idCarrera);
        $query2->execute();
        $grupos = $query2->get_result()->fetch_assoc()['total'];
        $query2->close();

        return ($alumnos > 0 || $grupos > 0);
    }

    /**
     * Elimina una carrera solo si no tiene dependencias.
     */
    public function eliminarCarrera($id) {
        if ($this->tieneDependencias($id)) {
            // No eliminar si hay alumnos o grupos asociados
            return false;
        }

        $stmt = $this->connection->prepare("DELETE FROM carreras WHERE idCarrera=?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

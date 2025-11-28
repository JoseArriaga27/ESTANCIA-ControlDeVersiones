<?php
class MateriaModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function obtenerMaterias() {
        $sql = "SELECT m.idMateria, m.nombreMateria, m.claveMateria, m.horasSemana, p.nombrePeriodo
                FROM materias m
                LEFT JOIN periodosEscolares p ON m.idPeriodo = p.idPeriodo
                ORDER BY m.idMateria ASC";
        return $this->connection->query($sql);
    }

    public function obtenerPorId($id) {
        $stmt = $this->connection->prepare("SELECT * FROM materias WHERE idMateria=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function agregarMateria($nombre, $clave, $horas, $idPeriodo) {
        $stmt = $this->connection->prepare("INSERT INTO materias (nombreMateria, claveMateria, horasSemana, idPeriodo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nombre, $clave, $horas, $idPeriodo);
        return $stmt->execute();
    }

    public function editarMateria($id, $nombre, $clave, $horas, $idPeriodo) {
        $stmt = $this->connection->prepare("UPDATE materias SET nombreMateria=?, claveMateria=?, horasSemana=?, idPeriodo=? WHERE idMateria=?");
        $stmt->bind_param("ssiii", $nombre, $clave, $horas, $idPeriodo, $id);
        return $stmt->execute();
    }

    public function eliminarMateria($id) {
        $stmt = $this->connection->prepare("DELETE FROM materias WHERE idMateria=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerPeriodosActivos() {
        return $this->connection->query("SELECT * FROM periodosEscolares WHERE fechaFin > CURDATE() ORDER BY fechaInicio ASC");
    }
}
?>

<?php
class PeriodoModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function obtenerPeriodos() {
        return $this->connection->query("SELECT * FROM periodosEscolares ORDER BY idPeriodo DESC");
    }

    public function agregarPeriodo($nombre, $inicio, $fin) {
        $stmt = $this->connection->prepare("INSERT INTO periodosEscolares (nombrePeriodo, fechaInicio, fechaFin) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $inicio, $fin);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->connection->prepare("SELECT * FROM periodosEscolares WHERE idPeriodo = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editarPeriodo($id, $nombre, $inicio, $fin) {
        $stmt = $this->connection->prepare("UPDATE periodosEscolares SET nombrePeriodo=?, fechaInicio=?, fechaFin=? WHERE idPeriodo=?");
        $stmt->bind_param("sssi", $nombre, $inicio, $fin, $id);
        return $stmt->execute();
    }

    public function eliminarPeriodo($id) {
        $stmt = $this->connection->prepare("DELETE FROM periodosEscolares WHERE idPeriodo=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

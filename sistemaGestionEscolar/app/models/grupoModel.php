<?php
class GrupoModel {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function obtenerGrupos() {
        $query = "SELECT g.idGrupo, g.nombreGrupo, p.nombrePeriodo, c.nombreCarrera
                  FROM grupos g
                  LEFT JOIN periodosEscolares p ON g.idPeriodo = p.idPeriodo
                  LEFT JOIN carreras c ON g.idCarrera = c.idCarrera
                  ORDER BY g.idGrupo ASC";
        return $this->connection->query($query);
    }

    public function obtenerCarreras() {
        return $this->connection->query("SELECT * FROM carreras ORDER BY nombreCarrera ASC");
    }

    public function agregarGrupo($nombre, $idPeriodo, $idCarrera) {
        $stmt = $this->connection->prepare("INSERT INTO grupos (nombreGrupo, idPeriodo, idCarrera) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $nombre, $idPeriodo, $idCarrera);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->connection->prepare("SELECT * FROM grupos WHERE idGrupo=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editarGrupo($id, $nombre, $idPeriodo, $idCarrera) {
        $stmt = $this->connection->prepare("UPDATE grupos SET nombreGrupo=?, idPeriodo=?, idCarrera=? WHERE idGrupo=?");
        $stmt->bind_param("siii", $nombre, $idPeriodo, $idCarrera, $id);
        return $stmt->execute();
    }

    public function eliminarGrupo($id) {
        $stmt = $this->connection->prepare("DELETE FROM grupos WHERE idGrupo=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ====================================================
    // MÉTODO NECESARIO PARA EL DASHBOARD ADMIN
    // ====================================================
    public function contarGrupos() {
        $sql = "SELECT COUNT(*) AS total FROM grupos";
        $result = $this->connection->query($sql);
        return $result->fetch_assoc()['total'];
    }
}

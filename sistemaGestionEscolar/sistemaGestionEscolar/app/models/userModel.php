<?php
class UserModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function obtenerUsuarios() {
        $query = "SELECT * FROM usuarios ORDER BY idUsuario DESC";
        return $this->connection->query($query);
    }

    // ====================================================
    // INSERTAR USUARIO (con soporte para rol Alumno)
    // ====================================================
    public function insertarUsuario($connection, $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $contrasena, $idCarrera = null) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $connection->prepare("
            INSERT INTO usuarios (nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, matricula, correo, rol, contrasena, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");
        $stmt->bind_param("sssssssss", $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $hash);
        $stmt->execute();

        if ($stmt->error) {
            error_log("Error al insertar usuario: " . $stmt->error);
            return false;
        }

        $idUsuario = $connection->insert_id;
        $stmt->close();

        // Si es alumno
        if ($rol === 'Alumno' && !empty($idCarrera)) {
            $stmt = $connection->prepare("INSERT INTO alumnos (idUsuario, idCarrera) VALUES (?, ?)");
            $stmt->bind_param("ii", $idUsuario, $idCarrera);
            $stmt->execute();
            $stmt->close();
        }

        return true;
    }

    // ====================================================
    // ACTUALIZAR USUARIO
    // ====================================================
    public function actualizarUsuario($connection, $idUsuario, $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $contrasena = null, $idCarrera = null) {
        if (!empty($contrasena)) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("
                UPDATE usuarios 
                SET nombres=?, apePaterno=?, apeMaterno=?, sexo=?, fechaNacimiento=?, matricula=?, correo=?, rol=?, contrasena=? 
                WHERE idUsuario=?
            ");
            $stmt->bind_param("sssssssssi", $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $hash, $idUsuario);
        } else {
            $stmt = $connection->prepare("
                UPDATE usuarios 
                SET nombres=?, apePaterno=?, apeMaterno=?, sexo=?, fechaNacimiento=?, matricula=?, correo=?, rol=? 
                WHERE idUsuario=?
            ");
            $stmt->bind_param("ssssssssi", $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $idUsuario);
        }

        $stmt->execute();
        $stmt->close();

        // Si es alumno
        if ($rol === 'Alumno' && !empty($idCarrera)) {
            $res = $connection->prepare("SELECT idAlumno FROM alumnos WHERE idUsuario = ?");
            $res->bind_param("i", $idUsuario);
            $res->execute();
            $res->store_result();

            if ($res->num_rows > 0) {
                $stmt = $connection->prepare("UPDATE alumnos SET idCarrera = ? WHERE idUsuario = ?");
                $stmt->bind_param("ii", $idCarrera, $idUsuario);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $connection->prepare("INSERT INTO alumnos (idUsuario, idCarrera) VALUES (?, ?)");
                $stmt->bind_param("ii", $idUsuario, $idCarrera);
                $stmt->execute();
                $stmt->close();
            }
            $res->close();

        } else {
            $del = $connection->prepare("DELETE FROM alumnos WHERE idUsuario = ?");
            $del->bind_param("i", $idUsuario);
            $del->execute();
            $del->close();
        }

        return true;
    }

    // ====================================================
    // ELIMINAR USUARIO
    // ====================================================
    public function eliminarUsuario($connection, $idUsuario) {
        $idUsuario = intval($idUsuario);
        if ($idUsuario <= 0) return false;

        $stmt = $connection->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $filas = $stmt->affected_rows;
        $stmt->close();

        return $filas > 0;
    }

    // ====================================================
    // CONTADORES PARA DASHBOARD
    // ====================================================

    

    public function contarUsuarios() {
        $sql = "SELECT COUNT(*) AS total FROM usuarios";
        $result = $this->connection->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function contarMaterias() {
        return $this->connection->query("SELECT COUNT(*) AS total FROM materias")->fetch_assoc()['total'];
    }

    public function contarGrupos() {
        return $this->connection->query("SELECT COUNT(*) AS total FROM grupos")->fetch_assoc()['total'];
    }
    
    public function cuentas() {
        return [
            'usuarios' => $this->contarUsuarios(),
            'materias' => $this->contarMaterias(),
            'grupos'   => $this->contarGrupos()
        ];
    }
}

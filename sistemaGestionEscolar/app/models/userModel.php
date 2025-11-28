<?php
// ====================================
// MODELO: Operaciones SQL de Usuarios
// ====================================

function obtenerUsuarios($connection) {
    $query = "SELECT * FROM usuarios ORDER BY idUsuario DESC";
    return $connection->query($query);
}

// ====================================================
// INSERTAR USUARIO (con soporte para rol Alumno)
// ====================================================
function insertarUsuario($connection, $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $contrasena, $idCarrera = null) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar usuario principal
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

    // Si es Alumno, registrar también su carrera
    if ($rol === 'Alumno' && !empty($idCarrera)) {
        $stmt = $connection->prepare("INSERT INTO alumnos (idUsuario, idCarrera) VALUES (?, ?)");
        $stmt->bind_param("ii", $idUsuario, $idCarrera);
        $stmt->execute();

        if ($stmt->error) {
            error_log("Error al insertar alumno: " . $stmt->error);
        }
        $stmt->close();
    }

    return true;
}

// ====================================================
// ACTUALIZAR USUARIO (con control de rol Alumno)
// ====================================================
function actualizarUsuario($connection, $idUsuario, $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $contrasena = null, $idCarrera = null) {
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
    if ($stmt->error) {
        error_log("Error al actualizar usuario: " . $stmt->error);
    }
    $stmt->close();

    // Si es Alumno, manejar registro en tabla alumnos
    if ($rol === 'Alumno' && !empty($idCarrera)) {
        $res = $connection->prepare("SELECT idAlumno FROM alumnos WHERE idUsuario = ?");
        $res->bind_param("i", $idUsuario);
        $res->execute();
        $res->store_result();

        if ($res->num_rows > 0) {
            // Actualizar carrera existente
            $stmt = $connection->prepare("UPDATE alumnos SET idCarrera = ? WHERE idUsuario = ?");
            $stmt->bind_param("ii", $idCarrera, $idUsuario);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insertar nuevo registro de alumno
            $stmt = $connection->prepare("INSERT INTO alumnos (idUsuario, idCarrera) VALUES (?, ?)");
            $stmt->bind_param("ii", $idUsuario, $idCarrera);
            $stmt->execute();
            $stmt->close();
        }
        $res->close();
    } else {
        // Si cambia de rol, eliminar de alumnos
        $del = $connection->prepare("DELETE FROM alumnos WHERE idUsuario = ?");
        $del->bind_param("i", $idUsuario);
        $del->execute();
        $del->close();
    }

    return true;
}

// ====================================================
// ELIMINAR USUARIO (con seguridad y logs de error)
// ====================================================
function eliminarUsuario($connection, $idUsuario) {
    $idUsuario = intval($idUsuario);
    if ($idUsuario <= 0) return false;

    $stmt = $connection->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
    if (!$stmt) {
        error_log("Error en prepare(): " . $connection->error);
        return false;
    }

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();

    if ($stmt->error) {
        error_log("Error al eliminar usuario: " . $stmt->error);
    }

    $filas = $stmt->affected_rows;
    $stmt->close();

    return $filas > 0;
}

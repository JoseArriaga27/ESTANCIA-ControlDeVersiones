<?php
// ====================================
// MODELO: Operaciones SQL de Usuarios
// ====================================

function obtenerUsuarios($connection) {
    $query = "SELECT * FROM usuarios ORDER BY idUsuario DESC";
    return $connection->query($query);
}

function insertarUsuario($connection, $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $contrasena) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $connection->prepare("
        INSERT INTO usuarios (nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, matricula, correo, rol, contrasena, activo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
    ");
    $stmt->bind_param("sssssssss", $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $hash);
    return $stmt->execute();
}

function actualizarUsuario($connection, $idUsuario, $nombres, $apePaterno, $apeMaterno, $sexo, $fechaNacimiento, $matricula, $correo, $rol, $contrasena = null) {
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
    return $stmt->execute();
}

function eliminarUsuario($connection, $idUsuario) {
    $idUsuario = intval($idUsuario);

    if ($idUsuario <= 0) {
        echo "<script>console.error('ID no válido para eliminar');</script>";
        return false;
    }

    $stmt = $connection->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
    if (!$stmt) {
        echo "<script>console.error('Error prepare: " . $connection->error . "');</script>";
        return false;
    }

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();

    if ($stmt->error) {
        echo "<script>console.error('Error execute: " . $stmt->error . "');</script>";
    }

    $filas = $stmt->affected_rows;
    $stmt->close();

    return $filas > 0;
}

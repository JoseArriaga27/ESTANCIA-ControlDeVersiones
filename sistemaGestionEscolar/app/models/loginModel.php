<?php
function verificarCredenciales($connection, $correo, $password) {
    $stmt = $connection->prepare(
        "SELECT * FROM usuarios WHERE correo = ? AND activo = 1 LIMIT 1"
    );
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows !== 1) return false;

    $u = $res->fetch_assoc();
    $hashGuardado = $u['contrasena'];

    // 1) Contraseña ya hasheada (bcrypt)
    if (password_verify($password, $hashGuardado)) {
        return $u;
    }

    // 2) Soporte legacy: texto plano → migración a hash
    $esBcrypt = substr($hashGuardado, 0, 4) === '$2y$';
    if (!$esBcrypt && hash_equals($hashGuardado, $password)) {
        $nuevoHash = password_hash($password, PASSWORD_DEFAULT);
        $upd = $connection->prepare("UPDATE usuarios SET contrasena=? WHERE idUsuario=?");
        $upd->bind_param("si", $nuevoHash, $u['idUsuario']);
        $upd->execute();
        return $u;
    }

    // 3) Si no coincide, credenciales incorrectas
    return false;
}

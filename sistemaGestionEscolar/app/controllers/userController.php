<?php
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../../config/config.php';

class UserController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function gestionarUsuarios() {
        $mensaje = '';
        $tipo = '';

        // --- INSERTAR ---
        if (isset($_POST['insertar'])) {
            insertarUsuario(
                $this->connection,
                $_POST['nombres'],
                $_POST['apePaterno'],
                $_POST['apeMaterno'],
                $_POST['sexo'],
                $_POST['fechaNacimiento'],
                $_POST['matricula'],
                $_POST['correo'],
                $_POST['rol'],
                $_POST['contrasena']
            );
            $mensaje = 'Usuario registrado correctamente.';
            $tipo = 'success';
        }

        // --- ACTUALIZAR ---
        if (isset($_POST['actualizar'])) {
            actualizarUsuario(
                $this->connection,
                $_POST['idUsuario'],
                $_POST['nombres'],
                $_POST['apePaterno'],
                $_POST['apeMaterno'],
                $_POST['sexo'],
                $_POST['fechaNacimiento'],
                $_POST['matricula'],
                $_POST['correo'],
                $_POST['rol'],
                $_POST['contrasena']
            );
            $mensaje = 'Usuario actualizado correctamente.';
            $tipo = 'success';
        }

        // ELIMINAR USUARIO
        if (isset($_GET['delete'])) {
            $id = intval($_GET['delete']);
            $ok = eliminarUsuario($this->connection, $id);

            if ($ok) {
                $mensaje = 'Usuario eliminado correctamente.';
                $tipo = 'success';
            } else {
                $mensaje = 'No se pudo eliminar el usuario (revisa dependencias o FK).';
                $tipo = 'danger';
            }
        }


        // --- FILTROS ---
        $rolFiltro = $_GET['rol'] ?? '';
        $buscarNombre = $_GET['buscar'] ?? '';

        $usuarios = obtenerUsuarios($this->connection, $rolFiltro, $buscarNombre);
        $connection = $this->connection;

        include __DIR__ . '/../views/GestionUsuarios/gestion_usuarios.php';
    }
}

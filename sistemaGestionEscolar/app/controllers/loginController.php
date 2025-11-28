<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/loginModel.php';

class LoginController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo']);
            $password = trim($_POST['password']);

            $usuario = verificarCredenciales($this->connection, $correo, $password);

            if ($usuario) {

                $idDocente = null;

                if ($usuario['rol'] === 'Docente') {
                    $consulta = $this->connection->prepare(
                        "SELECT idDocente FROM docentes WHERE idUsuario = ? LIMIT 1"
                    );
                    $consulta->bind_param("i", $usuario['idUsuario']);
                    $consulta->execute();
                    $resultado = $consulta->get_result();

                    if ($resultado->num_rows === 1) {
                        $fila = $resultado->fetch_assoc();
                        $idDocente = $fila['idDocente'];
                    }
                }

                // ------------------------------------------------------
                //  GUARDAR DATOS EN SESIÓN
                // ------------------------------------------------------
                $_SESSION['usuario'] = [
                    'id' => $usuario['idUsuario'],
                    'idDocente' => $idDocente,    // ⬅⬅ YA ESTÁ CORREGIDO
                    'nombre' => $usuario['nombres'] . ' ' . $usuario['apePaterno'],
                    'rol'     => $usuario['rol']
                ];

                // ------------------------------------------------------
                // REDIRECCIÓN POR ROL
                // ------------------------------------------------------
                switch ($usuario['rol']) {
                    case 'Administrador':
                        header('Location: ' . BASE_URL . 'index.php?action=dashboard');
                        break;

                    case 'Docente':
                        header('Location: ' . BASE_URL . 'index.php?action=dashboard_docente');
                        break;

                    case 'Alumno':
                        header('Location: ' . BASE_URL . 'index.php?action=dashboard_alumno');
                        break;

                    case 'Administrativo':
                        header('Location: ' . BASE_URL . 'index.php?action=dashboard_administrativo');
                        break;
                }

                exit;
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        }

        include __DIR__ . '/../views/login.php';
    }

    public function cerrarSesion() {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL . 'index.php?action=login');
        exit;
    }
}

<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/loginModel.php';

class LoginController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function iniciarSesion() {
        session_start();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo']);
            $password = trim($_POST['password']);

            $usuario = verificarCredenciales($this->connection, $correo, $password);

            if ($usuario) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['idUsuario'],
                    'nombre' => $usuario['nombres'] . ' ' . $usuario['apePaterno'],
                    'rol' => $usuario['rol']
                ];

                // ✅ Redirección absoluta según el rol
                switch ($usuario['rol']) {
                    case 'Administrador':
                        header('Location: ' . BASE_URL . 'app/views/Dashboard/dashboard_admin.php');
                        break;
                    case 'Docente':
                        header('Location: ' . BASE_URL . 'app/views/Dashboard/dashboard_docente.php');
                        break;
                    case 'Alumno':
                        header('Location: ' . BASE_URL . 'app/views/Dashboard/dashboard_alumno.php');
                        break;
                    case 'Administrativo':
                        header('Location: ' . BASE_URL . 'app/views/Dashboard/dashboard_administrativo.php');
                        break;
                    default:
                        header('Location: ' . BASE_URL . 'index.php?action=login');
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

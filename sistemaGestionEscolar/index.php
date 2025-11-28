<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db_connection.php';
require_once __DIR__ . '/app/controllers/loginController.php';
require_once __DIR__ . '/app/controllers/userController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        $controller = new LoginController($connection);
        $controller->iniciarSesion();
        break;

    case 'logout':
        $controller = new LoginController($connection);
        $controller->cerrarSesion();
        break;

    case 'usuarios':
        $controller = new UserController($connection);
        $controller->gestionarUsuarios();
        break;

    default:
        header('Location: ' . BASE_URL . 'index.php?action=login');
        break;
}

<?php
    // Incluir el controlador
    include_once "app/controllers/UserController.php";
    include_once "config/db_connection.php";

    // Objeto del controlador
    $controller = new UserController($connection);
    $controller -> insertarUsuario();
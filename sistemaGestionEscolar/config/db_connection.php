<?php
    // config/db_connection.php

    // Apagar reportes automáticos
    mysqli_report(MYSQLI_REPORT_OFF);

    $server = "localhost";
    $user = "root"; 
    $password = "";
    $db = "gestionEscolar";

    // Intentar conectar
    $connection = @new mysqli($server, $user, $password, $db);

    // Lógica de fallo
    if($connection->connect_errno){
        
        // 1. Preparar el mensaje para la vista
        $error_message = $connection->connect_error . " (Código: " . $connection->connect_errno . ")";
        
        require_once __DIR__ . '/../app/views/error_bd.php';
        
        exit;
    }
?>
<?php

    //Variables del servidor
    $server = "localhost";
    $user = "root"; 
    $password = "";
    $db = "gestionEscolar";

    // Conexion a la base de datos
    $connection = new mysqli($server,$user,$password,$db);

    // Verificación de la conexión a la BD
    if($connection -> connect_errno){
    
        //die: Termina el script
        die("Error de la conexión: " . $connection -> connect_errno);
    }//else{echo "Conexión exitosa";}

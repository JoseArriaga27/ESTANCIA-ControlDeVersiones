<?php

    // Incluir el modelo y la base de datos
    include_once "app/models/userModel.php";
    include_once "config/db_connection.php";

    // Crear la clase del controlador
    class UserController {

        private $model;

        // Constructor para el objeto del modelo
        public function __construct($connection){
            $this -> model = new UserModel($connection);
        }

        public function insertarUsuario(){ 

            if(isset($_POST['enviar'])){
                $nombre = trim($_POST['nombre']);
                $edad = (int) $_POST['edad'];
                $fecha = $_POST['fecha'];
                // La contraseña se hashea correctamente aquí
                $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
                
                if(!empty($nombre) && !empty($edad) && !empty($fecha) && !empty($pass)){
                    
                    // Llamada al método del modelo
                    $insert = $this -> model -> insertarUsuario($nombre, $edad, $fecha, $pass);

                    if($insert){
                        echo "<br>Registro exitoso";
                    }else{
                        echo "<br>Error en el registro";
                    }
                }
            }

            // Cargar el formulario a la vista
            include_once "app/views/form_insert.php";
        }
    }
    // ¡Aquí debería ir el cierre de la etiqueta PHP si no hay más código!
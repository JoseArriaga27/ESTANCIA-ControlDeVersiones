<?php
    //Siempre se usa para el modelo
    class UserModel{

        private $connection;

        //Crear el constructor de la clase
        public function __construct($connection){

            $this -> connection = $connection;

        }

        //Crear método para insertar el usuario
        public function insertarUsuario($nombre, $edad, $fecha, $pass){

            //Toda la lógicas para las inserciones en la base de datos
            $sql_statement = "insert into lista (nombre, edad, fecha, pass) values (?,?,?,?)";

            //Preparar el statement
            $statement = $this -> connection -> prepare($sql_statement);
            
            // CORRECCIÓN: Cambiado 'binf_param' por 'bind_param'
            $statement -> bind_param("siss", $nombre, $edad, $fecha, $pass);

            return $statement -> execute();
        }
    }
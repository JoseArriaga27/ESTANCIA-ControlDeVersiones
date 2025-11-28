<?php 
require_once __DIR__ . '/../models/backupModel.php';

class BackupController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    public function realizarRespaldoBD() {
        $server = "localhost";
        $user = "root"; 
        $password = "";
        $db = "gestionescolar";

        // Aquí se usa el modelo, no la conexión directa
        $backupModel = new BackupModel($this->connection);
        $backupModel->backup_tables($server, $user, $password, $db);

        $fecha = date("Y-m-d");
        $archivo = "config/backups/db-backup-" . $fecha . ".sql";

        if (file_exists($archivo)) {
            header("Content-disposition: attachment; filename=db-backup-" . $fecha . ".sql");
            header("Content-type: application/octet-stream");
            readfile($archivo);
            exit;
        } else {
            echo "No se pudo generar el respaldo.";
        }
    }

    public function restaurarBD(){

        $fecha = date("Y-m-d");
        $ruta = "config/backups/db-backup-" . $fecha . ".sql"; 

        $backupModel = new BackupModel($this->connection);
        $restore = $backupModel->restaurarBD($ruta); 

        session_start();

        if ($restore === "Restauración Exitosa") {
            $_SESSION['flash_msg'] = "Restauración éxitosa.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg'] = $restore;
            $_SESSION['flash_type'] = "danger";
        }

        header("Location: " . BASE_URL . "index.php?action=dashboard");
        exit;
    }

 

}

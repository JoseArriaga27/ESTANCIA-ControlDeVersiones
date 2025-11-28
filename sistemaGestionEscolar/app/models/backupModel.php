<?php 
class BackupModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function backup_tables($host, $user, $password, $name, $tables = '*') {
        $return = '';
        $link = new mysqli($host, $user, $password, $name);

        if ($tables == '*') {
            $tables = array();
            $result = $link->query('SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        foreach ($tables as $table) {
            $result = $link->query('SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);

            $row2 = mysqli_fetch_row($link->query('SHOW CREATE TABLE ' . $table));

            // Drop siempre antes del create
            $return .= "DROP TABLE IF EXISTS `$table`;\n";
            $return .= $row2[1] . ";\n\n";

            while ($row = mysqli_fetch_row($result)) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                    if (isset($row[$j])) { 
                        $return .= '"' . $row[$j] . '"'; 
                    } else { 
                        $return .= '""'; 
                    }
                    if ($j < ($num_fields - 1)) { 
                        $return .= ','; 
                    }
                }
                $return .= ");\n";
            }

$return .= "\n\n";
        }

        $fecha = date("Y-m-d");
        $ruta = __DIR__ . '/../../config/backups/db-backup-' . $fecha . '.sql';
        $handle = fopen($ruta, 'w+');
        fwrite($handle, $return);
        fclose($handle);

        return $ruta;
    }
    public function restaurarBD($ruta){

        // 1. **VERIFICAR EXISTENCIA DEL ARCHIVO**
        if (!file_exists($ruta)) {
            return "Error: No se ha generado un respaldo previamente o no hay archivos sql en la ruta especificada.";
        }

        // Leer archivo
        $query_archivo = file_get_contents($ruta);

        // 2. **VERIFICAR SI EL ARCHIVO ESTÁ VACÍO**
        if ($query_archivo === false || trim($query_archivo) === "") {
            return "Error: El archivo de restauración está vacío o no se pudo leer su contenido.";
        }

        // QUITAR SALTOS INÚTILES
        $query_archivo = trim($query_archivo);

        // DESACTIVAR CLAVES FORÁNEAS
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

        // EJECUTAR MULTIQUERY
        if($this->connection->multi_query($query_archivo)){

            do {
                if ($result = $this->connection->store_result()) {
                    $result->free();
                }
            } while ($this->connection->more_results() && $this->connection->next_result());

            // REACTIVAR CLAVES FORÁNEAS
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");

            return "Restauración Exitosa";
        } 
        else {

            // REACTIVAR CLAVES FORÁNEAS
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");

            return "Error en la restauración: " . $this->connection->error;
        }
    }

}

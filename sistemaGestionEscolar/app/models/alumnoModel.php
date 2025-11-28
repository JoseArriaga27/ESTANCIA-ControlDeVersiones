<?php
    class AlumnoModel {
        private $connection;

        public function __construct($connection) {
            $this->connection = $connection;
        }

        public function obtenerAlumnos() {
            $sql = "SELECT 
                        a.idAlumno, 
                        u.matricula, 
                        CONCAT(u.nombres,' ',u.apePaterno,' ',IFNULL(u.apeMaterno,'')) AS nombreCompleto, 
                        c.nombreCarrera, 
                        c.idCarrera, 
                        u.correo
                    FROM alumnos a
                    INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
                    LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
                    ORDER BY a.idAlumno ASC";
            return $this->connection->query($sql);
        }


        public function obtenerCarreras() {
            return $this->connection->query("SELECT * FROM carreras ORDER BY nombreCarrera ASC");
        }

        public function obtenerUsuariosDisponibles() {
            $query = "
                SELECT 
                    a.idAlumno,
                    CONCAT(u.nombres, ' ', u.apePaterno, ' ', IFNULL(u.apeMaterno, '')) AS nombreCompleto,
                    u.matricula,
                    u.correo,
                    IFNULL(c.nombreCarrera, 'Sin carrera') AS nombreCarrera,
                    c.idCarrera
                FROM alumnos a
                INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
                LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
                LEFT JOIN inscripciones i ON a.idAlumno = i.idAlumno
                WHERE i.idAlumno IS NULL
                ORDER BY u.nombres ASC
            ";
            return $this->connection->query($query);
        }


        public function agregarAlumno($idUsuario, $idCarrera) {
            $stmt = $this->connection->prepare("INSERT INTO alumnos (idUsuario, idCarrera) VALUES (?, ?)");
            $stmt->bind_param("ii", $idUsuario, $idCarrera);
            return $stmt->execute();
        }

        public function obtenerPorId($id) {
            $stmt = $this->connection->prepare("SELECT * FROM alumnos WHERE idAlumno=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function editarAlumno($idAlumno, $idCarrera) {
            $stmt = $this->connection->prepare("UPDATE alumnos SET idCarrera=? WHERE idAlumno=?");
            $stmt->bind_param("ii", $idCarrera, $idAlumno);
            return $stmt->execute();
        }

        public function eliminarAlumno($id) {
            $stmt = $this->connection->prepare("DELETE FROM alumnos WHERE idAlumno=?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }

        public function obtenerGruposPorCarrera($idCarrera) {
            $stmt = $this->connection->prepare("
                SELECT g.idGrupo, g.nombreGrupo, c.nombreCarrera
                FROM grupos g
                INNER JOIN carreras c ON g.idCarrera = c.idCarrera
                WHERE g.idCarrera = ?
            ");
            $stmt->bind_param("i", $idCarrera);
            $stmt->execute();
            return $stmt->get_result();
        }

        public function obtenerGrupos() {
            $sql = "SELECT g.idGrupo, g.nombreGrupo, c.nombreCarrera, p.nombrePeriodo
                    FROM grupos g
                    LEFT JOIN carreras c ON g.idCarrera = c.idCarrera
                    LEFT JOIN periodosEscolares p ON g.idPeriodo = p.idPeriodo";
            return $this->connection->query($sql);
        }

        public function inscribirAlumno($idAlumno, $idGrupo) {
            $fecha = date('Y-m-d');
            $stmt = $this->connection->prepare("
                INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion)
                SELECT ?, ?, ? FROM DUAL
                WHERE NOT EXISTS (
                    SELECT 1 FROM inscripciones WHERE idAlumno=? AND idGrupo=?
                )
            ");
            $stmt->bind_param("iisii", $idAlumno, $idGrupo, $fecha, $idAlumno, $idGrupo);
            return $stmt->execute();
        }

        public function obtenerInscripciones() {
            $sql = "SELECT 
                        i.idInscripcion, 
                        CONCAT(u.nombres,' ',u.apePaterno,' ',IFNULL(u.apeMaterno,'')) AS alumno, 
                        g.nombreGrupo, 
                        g.idGrupo,
                        c.nombreCarrera, 
                        i.fechaInscripcion
                    FROM inscripciones i
                    INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
                    INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
                    INNER JOIN grupos g ON g.idGrupo = i.idGrupo
                    LEFT JOIN carreras c ON a.idCarrera = c.idCarrera
                    ORDER BY g.idGrupo ASC, i.idInscripcion DESC";

            return $this->connection->query($sql);
        }


        public function obtenerMateriasAlumno($idUsuario) {
            $sql = "SELECT 
                        m.idMateria,
                        m.nombreMateria,
                        m.claveMateria,
                        g.nombreGrupo,
                        p.nombrePeriodo,
                        CONCAT(u.nombres, ' ', u.apePaterno, ' ', u.apeMaterno) AS docente
                    FROM usuarios uAlumno
                    INNER JOIN alumnos a ON uAlumno.idUsuario = a.idUsuario
                    INNER JOIN inscripciones i ON a.idAlumno = i.idAlumno
                    INNER JOIN grupos g ON i.idGrupo = g.idGrupo
                    INNER JOIN periodosEscolares p ON g.idPeriodo = p.idPeriodo
                    INNER JOIN asignaciones asig ON g.idGrupo = asig.idGrupo
                    INNER JOIN materias m ON asig.idMateria = m.idMateria
                    LEFT JOIN docentes d ON asig.idDocente = d.idDocente
                    LEFT JOIN usuarios u ON d.idUsuario = u.idUsuario
                    WHERE uAlumno.idUsuario = ?";

            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            return $stmt->get_result();
        }


    
    public function obtenerCalificacionesAlumno($idUsuario) {
        $sql = "SELECT 
                    m.claveMateria,
                    m.nombreMateria,
                    g.nombreGrupo,
                    p.nombrePeriodo,
                    c.calificacionParcial1,
                    c.calificacionParcial2,
                    c.calificacionParcial3,
                    c.calificacionFinal
                FROM usuarios u
                INNER JOIN alumnos a ON u.idUsuario = a.idUsuario
                INNER JOIN inscripciones i ON a.idAlumno = i.idAlumno
                INNER JOIN grupos g ON i.idGrupo = g.idGrupo
                INNER JOIN periodosEscolares p ON g.idPeriodo = p.idPeriodo
                INNER JOIN asignaciones asig ON asig.idGrupo = g.idGrupo
                INNER JOIN materias m ON m.idMateria = asig.idMateria
                LEFT JOIN calificaciones c 
                    ON c.idInscripcion = i.idInscripcion 
                    AND c.idMateria = m.idMateria
                WHERE u.idUsuario = ? 
                ORDER BY m.nombreMateria ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ==========================================
    // OBTENER CALIFICACIONES PARA EL DASHBOARD
    // ==========================================
    public function obtenerCalificacionesDashboard($idUsuario) {

        $sql = "SELECT 
                    m.nombreMateria,
                    IFNULL(c.calificacionFinal, 0) AS calificacion
                FROM usuarios u
                INNER JOIN alumnos a ON u.idUsuario = a.idUsuario
                INNER JOIN inscripciones i ON a.idAlumno = i.idAlumno
                INNER JOIN grupos g ON g.idGrupo = i.idGrupo
                INNER JOIN asignaciones asig ON asig.idGrupo = g.idGrupo
                INNER JOIN materias m ON m.idMateria = asig.idMateria
                LEFT JOIN calificaciones c 
                    ON c.idInscripcion = i.idInscripcion 
                    AND c.idMateria = m.idMateria
                WHERE u.idUsuario = ?
                ORDER BY m.nombreMateria ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ==========================================
    // PROMEDIO GENERAL PARA EL DASHBOARD
    // ==========================================
    public function obtenerPromedioDashboard($idUsuario) {

        $sql = "SELECT 
                    AVG(c.calificacionFinal) AS prom
                FROM usuarios u
                INNER JOIN alumnos a ON u.idUsuario = a.idUsuario
                INNER JOIN inscripciones i ON a.idAlumno = i.idAlumno
                INNER JOIN calificaciones c ON c.idInscripcion = i.idInscripcion
                WHERE u.idUsuario = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        return $res['prom'] ?? 0;
    }


    }


?>

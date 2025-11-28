<?php

class ConsultaCalifModel {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // =======================================================
    // 1. Calificaciones de UN alumno
    // =======================================================
    public function obtenerCalificacionesAlumno($idUsuario) {
        $sql = "
            SELECT 
                m.nombreMateria,
                c.calificacionParcial1,
                c.calificacionParcial2,
                c.calificacionParcial3,
                c.calificacionFinal,
                p.nombrePeriodo,
                
                c.idMateria,
                i.idInscripcion

            FROM usuarios u
            INNER JOIN alumnos a ON a.idUsuario = u.idUsuario
            INNER JOIN inscripciones i ON i.idAlumno = a.idAlumno
            INNER JOIN grupos g ON g.idGrupo = i.idGrupo
            INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
            INNER JOIN materias m ON m.idPeriodo = p.idPeriodo
            LEFT JOIN calificaciones c 
                ON c.idInscripcion = i.idInscripcion
                AND c.idMateria = m.idMateria

            WHERE u.idUsuario = ?
            ORDER BY p.idPeriodo DESC, m.nombreMateria ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    // =======================================================
    // 2. Calificaciones de los grupos del docente
    // =======================================================
    public function obtenerCalificacionesDocente($idDocente) {
        $sql = "
            SELECT 
                m.nombreMateria,
                g.nombreGrupo,
                u.matricula,
                CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS alumno,
                c.calificacionParcial1,
                c.calificacionParcial2,
                c.calificacionParcial3,
                c.calificacionFinal,
                p.nombrePeriodo,

                -- NECESARIO PARA EDITAR
                c.idMateria,
                i.idInscripcion

            FROM asignaciones asg
            INNER JOIN materias m ON m.idMateria = asg.idMateria
            INNER JOIN grupos g ON g.idGrupo = asg.idGrupo
            INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
            INNER JOIN inscripciones i ON i.idGrupo = g.idGrupo
            INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
            LEFT JOIN calificaciones c 
                ON c.idInscripcion = i.idInscripcion 
                AND c.idMateria = m.idMateria

            WHERE asg.idDocente = ?
            ORDER BY p.idPeriodo DESC, m.nombreMateria ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idDocente);
        $stmt->execute();
        return $stmt->get_result();
    }

    // =======================================================
    // 3. Calificaciones globales (Admin y Administrativo)
    // =======================================================
    public function obtenerTodasLasCalificaciones() {
        $sql = "
            SELECT 
                c.idInscripcion,
                c.idMateria,
                m.nombreMateria,
                g.nombreGrupo,
                u.matricula,
                CONCAT(u.nombres,' ',u.apePaterno,' ',u.apeMaterno) AS alumno,
                c.calificacionParcial1,
                c.calificacionParcial2,
                c.calificacionParcial3,
                c.calificacionFinal,
                p.nombrePeriodo
            FROM calificaciones c
            INNER JOIN materias m ON m.idMateria = c.idMateria
            INNER JOIN inscripciones i ON i.idInscripcion = c.idInscripcion
            INNER JOIN grupos g ON g.idGrupo = i.idGrupo
            INNER JOIN periodosEscolares p ON p.idPeriodo = g.idPeriodo
            INNER JOIN alumnos a ON a.idAlumno = i.idAlumno
            INNER JOIN usuarios u ON u.idUsuario = a.idUsuario
            ORDER BY p.idPeriodo DESC
        ";

        return $this->connection->query($sql);
    }

}

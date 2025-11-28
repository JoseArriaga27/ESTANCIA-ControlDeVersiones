<?php
class CapturaCalifModel {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // ============================================================
    // 1. Asignaciones SOLO del docente
    // ============================================================
    public function obtenerAsignacionesDocente($idDocente) {
        $sql = "
            SELECT 
                a.idAsignacion,
                a.idMateria,
                a.idGrupo,
                m.nombreMateria,
                g.nombreGrupo,
                p.nombrePeriodo
            FROM asignaciones a
            INNER JOIN materias m ON a.idMateria = m.idMateria
            INNER JOIN grupos g ON a.idGrupo = g.idGrupo
            INNER JOIN periodosEscolares p ON m.idPeriodo = p.idPeriodo
            WHERE a.idDocente = ?
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idDocente);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ============================================================
    // 2. NUEVO → Asignaciones para ADMIN y ADMINISTRATIVO (todas)
    // ============================================================
    public function obtenerTodasLasAsignaciones() {
        $sql = "
            SELECT 
                a.idAsignacion,
                a.idMateria,
                a.idGrupo,
                m.nombreMateria,
                g.nombreGrupo,
                p.nombrePeriodo
            FROM asignaciones a
            INNER JOIN materias m ON a.idMateria = m.idMateria
            INNER JOIN grupos g ON a.idGrupo = g.idGrupo
            INNER JOIN periodosEscolares p ON m.idPeriodo = p.idPeriodo
            ORDER BY g.nombreGrupo ASC, m.nombreMateria ASC
        ";
        return $this->connection->query($sql);
    }

    // ============================================================
    // 3. Contar materias asignadas al docente
    // ============================================================
    public function contarMateriasDocente($idDocente) {
        $sql = "
            SELECT COUNT(DISTINCT idMateria) AS total
            FROM asignaciones
            WHERE idDocente = ?
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idDocente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    // ============================================================
    // 4. Contar grupos asignados al docente
    // ============================================================
    public function contarGruposDocente($idDocente) {
        $sql = "
            SELECT COUNT(DISTINCT idGrupo) AS total
            FROM asignaciones
            WHERE idDocente = ?
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idDocente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    // ============================================================
    // 5. Obtener alumnos de un grupo
    // ============================================================
    public function obtenerAlumnosGrupo($idGrupo) {
        $sql = "
            SELECT 
                i.idInscripcion,
                u.matricula,
                u.nombres,
                u.apePaterno,
                u.apeMaterno
            FROM inscripciones i
            INNER JOIN alumnos a ON i.idAlumno = a.idAlumno
            INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
            WHERE i.idGrupo = ?
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idGrupo);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ============================================================
    // 6. Obtener una calificación ya guardada
    // ============================================================
    public function obtenerCalificacion($idInscripcion, $idMateria) {
        $sql = "
            SELECT * FROM calificaciones
            WHERE idInscripcion = ? AND idMateria = ? LIMIT 1
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $idInscripcion, $idMateria);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ============================================================
    // 7. Guardar o actualizar calificaciones
    // ============================================================
    public function guardarCalificacion($idInscripcion, $idMateria, $p1, $p2, $p3, $final) {

    // Convertir valores vacíos a NULL real
    $p1 = ($p1 === '' || $p1 === null) ? null : $p1;
    $p2 = ($p2 === '' || $p2 === null) ? null : $p2;
    $p3 = ($p3 === '' || $p3 === null) ? null : $p3;
    $final = ($final === '' || $final === null) ? null : $final;

    // ¿Ya existe?
    $check = "SELECT idCalificacion FROM calificaciones 
              WHERE idInscripcion = ? AND idMateria = ?";
    $stmt = $this->connection->prepare($check);
    $stmt->bind_param("ii", $idInscripcion, $idMateria);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        // UPDATE usando NULL correctamente
        $update = "UPDATE calificaciones
                   SET calificacionParcial1 = ?, 
                       calificacionParcial2 = ?, 
                       calificacionParcial3 = ?, 
                       calificacionFinal = ?
                   WHERE idInscripcion = ? AND idMateria = ?";

        $u = $this->connection->prepare($update);
        $u->bind_param("ddddii", $p1, $p2, $p3, $final, $idInscripcion, $idMateria);
        return $u->execute();
    }

    // INSERT CON NULLS
    $insert = "INSERT INTO calificaciones
               (idInscripcion, idMateria, calificacionParcial1, calificacionParcial2, calificacionParcial3, calificacionFinal)
               VALUES (?, ?, ?, ?, ?, ?)";

    $i = $this->connection->prepare($insert);
    $i->bind_param("iidddd", $idInscripcion, $idMateria, $p1, $p2, $p3, $final);
    return $i->execute();
}

}

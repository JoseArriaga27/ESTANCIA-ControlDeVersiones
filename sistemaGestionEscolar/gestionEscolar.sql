-- ============================================================
-- BASE DE DATOS: Sistema de Gestión Escolar
-- Autores: José Manuel Arriaga Monroy y Erick Ariel Méndez Corona
-- Fecha: Octubre 2025
-- ============================================================

CREATE DATABASE IF NOT EXISTS gestionEscolar;
USE gestionEscolar;

-- ============================================================
-- TABLA: usuarios
-- ============================================================
CREATE TABLE usuarios (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apePaterno VARCHAR(50) NOT NULL,
    apeMaterno VARCHAR(50),
    sexo ENUM('Masculino','Femenino') NOT NULL,
    fechaNacimiento DATE NOT NULL,
    correo VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('Administrador','Docente','Alumno','Administrativo') NOT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: docentes
-- ============================================================
CREATE TABLE docentes (
    idDocente INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    departamento VARCHAR(100),
    telefono VARCHAR(20),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: alumnos
-- ============================================================
CREATE TABLE alumnos (
    idAlumno INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    matriculaAlumno VARCHAR(20) UNIQUE NOT NULL,
    carrera VARCHAR(100),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: periodosEscolares
-- ============================================================
CREATE TABLE periodosEscolares (
    idPeriodo INT AUTO_INCREMENT PRIMARY KEY,
    nombrePeriodo VARCHAR(50) NOT NULL,
    fechaInicio DATE NOT NULL,
    fechaFin DATE NOT NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: grupos
-- ============================================================
CREATE TABLE grupos (
    idGrupo INT AUTO_INCREMENT PRIMARY KEY,
    nombreGrupo VARCHAR(50) NOT NULL,
    idPeriodo INT,
    FOREIGN KEY (idPeriodo) REFERENCES periodosEscolares(idPeriodo)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: materias
-- ============================================================
CREATE TABLE materias (
    idMateria INT AUTO_INCREMENT PRIMARY KEY,
    nombreMateria VARCHAR(100) NOT NULL,
    claveMateria VARCHAR(20) UNIQUE NOT NULL,
    horasSemana INT,
    idPeriodo INT,
    FOREIGN KEY (idPeriodo) REFERENCES periodosEscolares(idPeriodo)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: asignaciones
-- ============================================================
CREATE TABLE asignaciones (
    idAsignacion INT AUTO_INCREMENT PRIMARY KEY,
    idDocente INT NOT NULL,
    idMateria INT NOT NULL,
    idGrupo INT NOT NULL,
    idPeriodo INT NOT NULL,
    FOREIGN KEY (idDocente) REFERENCES docentes(idDocente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idMateria) REFERENCES materias(idMateria)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idGrupo) REFERENCES grupos(idGrupo)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idPeriodo) REFERENCES periodosEscolares(idPeriodo)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: inscripciones
-- ============================================================
CREATE TABLE inscripciones (
    idInscripcion INT AUTO_INCREMENT PRIMARY KEY,
    idAlumno INT NOT NULL,
    idGrupo INT NOT NULL,
    fechaInscripcion DATE NOT NULL,
    FOREIGN KEY (idAlumno) REFERENCES alumnos(idAlumno)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idGrupo) REFERENCES grupos(idGrupo)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: calificaciones
-- ============================================================
CREATE TABLE calificaciones (
    idCalificacion INT AUTO_INCREMENT PRIMARY KEY,
    idInscripcion INT NOT NULL,
    idMateria INT NOT NULL,
    calificacionParcial1 DECIMAL(5,2),
    calificacionParcial2 DECIMAL(5,2),
    calificacionFinal DECIMAL(5,2),
    FOREIGN KEY (idInscripcion) REFERENCES inscripciones(idInscripcion)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idMateria) REFERENCES materias(idMateria)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: reportes
-- ============================================================
CREATE TABLE reportes (
    idReporte INT AUTO_INCREMENT PRIMARY KEY,
    tipoReporte ENUM('Calificaciones','General') NOT NULL,
    fechaGeneracion DATE NOT NULL,
    generadoPor INT NOT NULL,
    FOREIGN KEY (generadoPor) REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: respaldos
-- ============================================================
CREATE TABLE respaldos (
    idRespaldo INT AUTO_INCREMENT PRIMARY KEY,
    nombreArchivo VARCHAR(150) NOT NULL,
    fechaGeneracion DATE NOT NULL,
    generadoPor INT NOT NULL,
    FOREIGN KEY (generadoPor) REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- DATOS EXACTOS DE USUARIOS (de tu phpMyAdmin)
-- ============================================================
INSERT INTO usuarios 
(idUsuario, matricula, nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, correo, contrasena, rol, activo) VALUES
(1, 'ADM001', 'Sandra', 'León', NULL, 'Femenino', '1980-01-01', 'sandra.leon@upemor.edu.mx', 'admin123', 'Administrador', 1),
(2, 'DOC001', 'José', 'Arriaga', NULL, 'Masculino', '1985-01-01', 'jose.arriaga@upemor.edu.mx', 'jose01', 'Docente', 1),
(3, 'DOC002', 'Erick', 'Méndez', NULL, 'Masculino', '1990-01-01', 'erick.mendez@upemor.edu.mx', 'erick01', 'Docente', 1),
(4, 'ALU001', 'Laura', 'Hernández', NULL, 'Femenino', '2000-01-01', 'laura.hernandez@upemor.edu.mx', 'laura01', 'Alumno', 1);


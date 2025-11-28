-- ==========================================
-- BASE DE DATOS: gestionEscolar
-- Proyecto: Sistema de Gestión Escolar
-- Autores: José Arriaga Monroy & Erick Méndez Corona
-- ==========================================

CREATE DATABASE IF NOT EXISTS gestionEscolar;
USE gestionEscolar;

-- ==============================
-- TABLA: usuarios
-- ==============================
CREATE TABLE usuarios (
  idUsuario INT AUTO_INCREMENT PRIMARY KEY,
  matricula VARCHAR(20) UNIQUE NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('Administrador','Docente','Alumno','Administrativo') NOT NULL,
  activo TINYINT(1) DEFAULT 1
);

-- ==============================
-- TABLA: docentes
-- ==============================
CREATE TABLE docentes (
  idDocente INT AUTO_INCREMENT PRIMARY KEY,
  idUsuario INT NOT NULL,
  departamento VARCHAR(100),
  telefono VARCHAR(20),
  FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
);

-- ==============================
-- TABLA: alumnos
-- ==============================
CREATE TABLE alumnos (
  idAlumno INT AUTO_INCREMENT PRIMARY KEY,
  idUsuario INT NOT NULL,
  matriculaAlumno VARCHAR(20) UNIQUE NOT NULL,
  carrera VARCHAR(100),
  FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
);

-- ==============================
-- TABLA: periodosEscolares
-- ==============================
CREATE TABLE periodosEscolares (
  idPeriodo INT AUTO_INCREMENT PRIMARY KEY,
  nombrePeriodo VARCHAR(50),
  fechaInicio DATE,
  fechaFin DATE
);

-- ==============================
-- TABLA: grupos
-- ==============================
CREATE TABLE grupos (
  idGrupo INT AUTO_INCREMENT PRIMARY KEY,
  nombreGrupo VARCHAR(50),
  idPeriodo INT,
  FOREIGN KEY (idPeriodo) REFERENCES periodosEscolares(idPeriodo)
);

-- ==============================
-- TABLA: materias
-- ==============================
CREATE TABLE materias (
  idMateria INT AUTO_INCREMENT PRIMARY KEY,
  nombreMateria VARCHAR(100),
  claveMateria VARCHAR(20) UNIQUE,
  horasSemana INT,
  idPeriodo INT,
  FOREIGN KEY (idPeriodo) REFERENCES periodosEscolares(idPeriodo)
);

-- ==============================
-- TABLA: asignaciones
-- ==============================
CREATE TABLE asignaciones (
  idAsignacion INT AUTO_INCREMENT PRIMARY KEY,
  idDocente INT,
  idMateria INT,
  idGrupo INT,
  idPeriodo INT,
  FOREIGN KEY (idDocente) REFERENCES docentes(idDocente),
  FOREIGN KEY (idMateria) REFERENCES materias(idMateria),
  FOREIGN KEY (idGrupo) REFERENCES grupos(idGrupo),
  FOREIGN KEY (idPeriodo) REFERENCES periodosEscolares(idPeriodo)
);

-- ==============================
-- TABLA: inscripciones
-- ==============================
CREATE TABLE inscripciones (
  idInscripcion INT AUTO_INCREMENT PRIMARY KEY,
  idAlumno INT,
  idGrupo INT,
  fechaInscripcion DATE,
  FOREIGN KEY (idAlumno) REFERENCES alumnos(idAlumno),
  FOREIGN KEY (idGrupo) REFERENCES grupos(idGrupo)
);

-- ==============================
-- TABLA: calificaciones
-- ==============================
CREATE TABLE calificaciones (
  idCalificacion INT AUTO_INCREMENT PRIMARY KEY,
  idInscripcion INT,
  idMateria INT,
  calificacionParcial1 DECIMAL(5,2),
  calificacionParcial2 DECIMAL(5,2),
  calificacionFinal DECIMAL(5,2),
  FOREIGN KEY (idInscripcion) REFERENCES inscripciones(idInscripcion),
  FOREIGN KEY (idMateria) REFERENCES materias(idMateria)
);

-- ==============================
-- TABLA: reportes
-- ==============================
CREATE TABLE reportes (
  idReporte INT AUTO_INCREMENT PRIMARY KEY,
  tipoReporte ENUM('Calificaciones','General'),
  fechaGeneracion DATE,
  generadoPor INT,
  FOREIGN KEY (generadoPor) REFERENCES usuarios(idUsuario)
);

-- ==============================
-- TABLA: respaldos
-- ==============================
CREATE TABLE respaldos (
  idRespaldo INT AUTO_INCREMENT PRIMARY KEY,
  nombreArchivo VARCHAR(150),
  fechaGeneracion DATE,
  generadoPor INT,
  FOREIGN KEY (generadoPor) REFERENCES usuarios(idUsuario)
);

-- =====================================================
-- INSERCIONES DE PRUEBA (EP03)
-- =====================================================

-- Usuarios
INSERT INTO usuarios (matricula, nombre, correo, contrasena, rol, activo) VALUES
('ADM001', 'Administrador General', 'admin@escuela.edu.mx', MD5('admin123'), 'Administrador', 1),
('DOC001', 'Erick Mendez Corona', 'emendez@escuela.edu.mx', MD5('docente123'), 'Docente', 1),
('ALU001', 'José Arriaga Monroy', 'jarriaga@escuela.edu.mx', MD5('alumno123'), 'Alumno', 1);

-- Docentes
INSERT INTO docentes (idUsuario, departamento, telefono) VALUES
(2, 'Tecnologías de la Información', '7775551234');

-- Alumnos
INSERT INTO alumnos (idUsuario, matriculaAlumno, carrera) VALUES
(3, 'TI2025A001', 'Ingeniería en Tecnologías de la Información');

-- Periodos Escolares
INSERT INTO periodosEscolares (nombrePeriodo, fechaInicio, fechaFin) VALUES
('Septiembre-Diciembre 2025', '2025-09-01', '2025-12-15');

-- Grupos
INSERT INTO grupos (nombreGrupo, idPeriodo) VALUES
('TI7B', 1);

-- Materias
INSERT INTO materias (nombreMateria, claveMateria, horasSemana, idPeriodo) VALUES
('Programación Web Avanzada', 'TI401', 5, 1),
('Base de Datos II', 'TI402', 4, 1),
('Ingeniería de Software', 'TI403', 4, 1);

-- Asignaciones
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(1, 1, 1, 1),
(1, 2, 1, 1),
(1, 3, 1, 1);

-- Inscripciones
INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(1, 1, '2025-09-05');

-- Calificaciones
INSERT INTO calificaciones (idInscripcion, idMateria, calificacionParcial1, calificacionParcial2, calificacionFinal) VALUES
(1, 1, 90, 95, 93),
(1, 2, 88, 91, 90),
(1, 3, 85, 90, 88);


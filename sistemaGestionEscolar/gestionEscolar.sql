DROP DATABASE gestionEscolar;
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
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('Administrador','Docente','Alumno','Administrativo') NOT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: carreras
-- ============================================================
CREATE TABLE carreras (
    idCarrera INT AUTO_INCREMENT PRIMARY KEY,
    nombreCarrera VARCHAR(100) UNIQUE NOT NULL,
    claveCarrera VARCHAR(20) UNIQUE NOT NULL,
    descripcion TEXT
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: docentes
-- ============================================================
CREATE TABLE docentes (
    idDocente INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
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
    idCarrera INT,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idCarrera) REFERENCES carreras(idCarrera)
        ON DELETE SET NULL
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
    idCarrera INT NOT NULL,
    idPeriodo INT NOT NULL,
    FOREIGN KEY (idCarrera) REFERENCES carreras(idCarrera)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
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
-- TABLA: asignaciones (docente-materia-grupo)
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
-- TABLA: calificaciones (ACTUALIZADA A 3 PARCIALES)
-- ============================================================
CREATE TABLE calificaciones (
    idCalificacion INT AUTO_INCREMENT PRIMARY KEY,
    idInscripcion INT NOT NULL,
    idMateria INT NOT NULL,
    calificacionParcial1 DECIMAL(5,2),
    calificacionParcial2 DECIMAL(5,2),
    calificacionParcial3 DECIMAL(5,2),
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

-- Para llevar al sistema a otro nivel

-- ============================================================
-- INSERTAR USUARIOS — ADMINISTRADORES
-- ============================================================
INSERT INTO usuarios (matricula, nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, correo, contrasena, rol)
VALUES
('ADM001', 'Sandra', 'León', 'Martínez', 'Femenino', '1980-02-11', 'sandra.leon@upemor.edu.mx', 'admin123', 'Administrador'),
('ADM002', 'Roberto Enrique', 'Lopez', 'Díaz', 'Masculino', '1978-06-22', 'roberto.cortes@upemor.edu.mx', 'admin2025', 'Administrador');

-- ============================================================
-- INSERTAR USUARIOS — ADMINISTRATIVOS
-- ============================================================
INSERT INTO usuarios (matricula, nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, correo, contrasena, rol)
VALUES
('ADMT001', 'Carmen', 'Vargas', 'Guzmán', 'Femenino', '1985-03-12', 'carmen.vargas@upemor.edu.mx', 'admin01', 'Administrativo'),
('ADMT002', 'Elena', 'Torres', 'Soto', 'Femenino', '1989-11-03', 'elena.torres@upemor.edu.mx', 'admin02', 'Administrativo'),
('ADMT003', 'Ricardo', 'Molina', 'Pérez', 'Masculino', '1983-05-18', 'ricardo.molina@upemor.edu.mx', 'admin03', 'Administrativo'),
('ADMT004', 'Gerardo', 'Acosta', 'Cruz', 'Masculino', '1990-08-20', 'gerardo.acosta@upemor.edu.mx', 'admin04', 'Administrativo');

-- ============================================================
-- INSERTAR USUARIOS — DOCENTES
-- ============================================================
INSERT INTO usuarios (matricula, nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, correo, contrasena, rol)
VALUES
('DOC001', 'José', 'Arriaga', 'Monroy', 'Masculino', '1985-04-10', 'jose.arriaga@upemor.edu.mx', 'pass1', 'Docente'),
('DOC002', 'Erick', 'Méndez', 'Corona', 'Masculino', '1990-02-18', 'erick.mendez@upemor.edu.mx', 'pass2', 'Docente'),
('DOC003', 'Mariana', 'Castillo', 'García', 'Femenino', '1987-07-12', 'mariana.castillo@upemor.edu.mx', 'pass3', 'Docente'),
('DOC004', 'Carlos', 'Ramírez', 'López', 'Masculino', '1982-01-15', 'carlos.ramirez@upemor.edu.mx', 'pass4', 'Docente'),
('DOC005', 'Sofía', 'Pineda', 'Rojas', 'Femenino', '1991-03-23', 'sofia.pineda@upemor.edu.mx', 'pass5', 'Docente'),
('DOC006', 'Luis', 'Hernández', 'Santos', 'Masculino', '1989-09-09', 'luis.hernandez@upemor.edu.mx', 'pass6', 'Docente'),
('DOC007', 'Andrea', 'Jiménez', 'Flores', 'Femenino', '1988-12-04', 'andrea.jimenez@upemor.edu.mx', 'pass7', 'Docente'),
('DOC008', 'Miguel', 'Gómez', 'Reyes', 'Masculino', '1984-06-14', 'miguel.gomez@upemor.edu.mx', 'pass8', 'Docente'),
('DOC009', 'Patricia', 'Serrano', 'Luna', 'Femenino', '1992-10-21', 'patricia.serrano@upemor.edu.mx', 'pass9', 'Docente'),
('DOC010', 'Alberto', 'Cordero', 'Mejía', 'Masculino', '1986-11-29', 'alberto.cordero@upemor.edu.mx', 'pass10', 'Docente'),
('DOC011', 'Camila', 'Solórzano', 'Velázquez', 'Femenino', '1991-06-14', 'camila.solorzano@upemor.edu.mx', 'camila01', 'Docente');

INSERT INTO usuarios 
(matricula, nombres, apePaterno, apeMaterno, sexo, fechaNacimiento, correo, contrasena, rol)
VALUES
('ALU001','Santiago','Ramírez','López','Masculino','2003-05-12','santiago.ramirez@upemor.edu.mx','pass01','Alumno'),
('ALU002','Valeria','Martínez','Gómez','Femenino','2004-03-22','valeria.martinez@upemor.edu.mx','pass02','Alumno'),
('ALU003','Luis Ángel','Torres','Sánchez','Masculino','2003-08-15','luis.torres@upemor.edu.mx','pass03','Alumno'),
('ALU004','Ximena','Castillo','Rojas','Femenino','2004-01-11','ximena.castillo@upemor.edu.mx','pass04','Alumno'),
('ALU005','Alexis','Mendoza','Flores','Masculino','2003-07-02','alexis.mendoza@upemor.edu.mx','pass05','Alumno'),
('ALU006','Regina','García','Soto','Femenino','2004-10-10','regina.garcia@upemor.edu.mx','pass06','Alumno'),
('ALU007','Diego','Hernández','Pérez','Masculino','2003-12-09','diego.hernandez@upemor.edu.mx','pass07','Alumno'),
('ALU008','María José','Ortega','Serrano','Femenino','2004-05-28','mariaj.ortega@upemor.edu.mx','pass08','Alumno'),
('ALU009','Fernando','Gómez','Acosta','Masculino','2003-09-03','fernando.gomez@upemor.edu.mx','pass09','Alumno'),
('ALU010','Ana Sofía','Navarro','Vega','Femenino','2004-04-14','anasofia.navarro@upemor.edu.mx','pass10','Alumno'),
('ALU011','Andrés','Santos','Castro','Masculino','2003-11-11','andres.santos@upemor.edu.mx','pass11','Alumno'),
('ALU012','Camila','Pineda','Duarte','Femenino','2004-06-07','camila.pineda@upemor.edu.mx','pass12','Alumno'),
('ALU013','Jorge','Flores','Molina','Masculino','2003-07-21','jorge.flores@upemor.edu.mx','pass13','Alumno'),
('ALU014','Natalia','Silva','Ramos','Femenino','2004-12-10','natalia.silva@upemor.edu.mx','pass14','Alumno'),
('ALU015','Ricardo','Reyes','Correa','Masculino','2003-03-26','ricardo.reyes@upemor.edu.mx','pass15','Alumno'),
('ALU016','Danna','Núñez','Solís','Femenino','2004-02-17','danna.nunez@upemor.edu.mx','pass16','Alumno'),
('ALU017','Oscar','Vargas','Pérez','Masculino','2003-10-30','oscar.vargas@upemor.edu.mx','pass17','Alumno'),
('ALU018','Montserrat','Juárez','Linares','Femenino','2004-09-05','montserrat.juarez@upemor.edu.mx','pass18','Alumno'),
('ALU019','Iván','Carmona','Lara','Masculino','2003-02-14','ivan.carmona@upemor.edu.mx','pass19','Alumno'),
('ALU020','Paola','Salazar','Paz','Femenino','2004-07-19','paola.salazar@upemor.edu.mx','pass20','Alumno'),
('ALU021','Marco','Fuentes','Toledo','Masculino','2003-08-22','marco.fuentes@upemor.edu.mx','pass21','Alumno'),
('ALU022','Carolina','Rosales','Ibarra','Femenino','2004-11-03','carolina.rosales@upemor.edu.mx','pass22','Alumno'),
('ALU023','Adrián','Pérez','González','Masculino','2003-04-05','adrian.perez@upemor.edu.mx','pass23','Alumno'),
('ALU024','Itzel','Prieto','Sosa','Femenino','2004-01-28','itzel.prieto@upemor.edu.mx','pass24','Alumno'),
('ALU025','Eduardo','López','Castañeda','Masculino','2003-12-18','eduardo.lopez@upemor.edu.mx','pass25','Alumno'),
('ALU026','Samantha','Requena','Trejo','Femenino','2004-02-24','samantha.requena@upemor.edu.mx','pass26','Alumno'),
('ALU027','Brandon','Campos','Valdez','Masculino','2003-09-09','brandon.campos@upemor.edu.mx','pass27','Alumno'),
('ALU028','Ariana','Sánchez','Guerrero','Femenino','2004-06-11','ariana.sanchez@upemor.edu.mx','pass28','Alumno'),
('ALU029','Héctor','Ceballos','Mena','Masculino','2003-10-16','hector.ceballos@upemor.edu.mx','pass29','Alumno'),
('ALU030','Julieta','Morales','Quiroz','Femenino','2004-05-09','julieta.morales@upemor.edu.mx','pass30','Alumno'),
('ALU031','Emiliano','Vera','Loyola','Masculino','2003-01-17','emiliano.vera@upemor.edu.mx','pass31','Alumno'),
('ALU032','Zoe','Tejeda','Aguilar','Femenino','2004-03-01','zoe.tejeda@upemor.edu.mx','pass32','Alumno'),
('ALU033','Rodrigo','Sierra','Blanco','Masculino','2003-07-26','rodrigo.sierra@upemor.edu.mx','pass33','Alumno'),
('ALU034','Aylin','Castaño','Salinas','Femenino','2004-12-01','aylin.castano@upemor.edu.mx','pass34','Alumno'),
('ALU035','Jonathan','Mejía','Rubio','Masculino','2003-04-14','jonathan.mejia@upemor.edu.mx','pass35','Alumno'),
('ALU036','Daniela','Zamora','Rivas','Femenino','2004-08-08','daniela.zamora@upemor.edu.mx','pass36','Alumno'),
('ALU037','Pablo','Solano','Ortega','Masculino','2003-12-30','pablo.solano@upemor.edu.mx','pass37','Alumno'),
('ALU038','Victoria','Galindo','Pérez','Femenino','2004-10-02','victoria.galindo@upemor.edu.mx','pass38','Alumno'),
('ALU039','Mauricio','Roldán','Campos','Masculino','2003-03-11','mauricio.roldan@upemor.edu.mx','pass39','Alumno'),
('ALU040','Nicole','Benítez','Serrano','Femenino','2004-06-18','nicole.benitez@upemor.edu.mx','pass40','Alumno'),
('ALU041','Alan','Arellano','Vidal','Masculino','2003-11-01','alan.arellano@upemor.edu.mx','pass41','Alumno'),
('ALU042','Elena','Zúñiga','Cruz','Femenino','2004-04-23','elena.zuniga@upemor.edu.mx','pass42','Alumno'),
('ALU043','Sebastián','Peña','García','Masculino','2003-09-20','sebastian.pena@upemor.edu.mx','pass43','Alumno'),
('ALU044','Dafne','Lozano','Jiménez','Femenino','2004-02-19','dafne.lozano@upemor.edu.mx','pass44','Alumno'),
('ALU045','Óscar','Valle','Ruiz','Masculino','2003-07-07','oscar.valle@upemor.edu.mx','pass45','Alumno'),
('ALU046','Renata','Quiñones','Haro','Femenino','2004-11-18','renata.quinones@upemor.edu.mx','pass46','Alumno'),
('ALU047','Gael','Ramos','Rosales','Masculino','2003-02-27','gael.ramos@upemor.edu.mx','pass47','Alumno'),
('ALU048','Miranda','Esquivel','Pineda','Femenino','2004-08-14','miranda.esquivel@upemor.edu.mx','pass48','Alumno'),
('ALU049','Leonardo','Barrios','Muñoz','Masculino','2003-05-25','leonardo.barrios@upemor.edu.mx','pass49','Alumno'),
('ALU050','Abril','Delgado','Rey','Femenino','2004-03-30','abril.delgado@upemor.edu.mx','pass50','Alumno');

-- ============================================================
-- TABLA docentes (ENLAZAR IDUsuario → IDDocente)
-- ============================================================
INSERT INTO docentes (idUsuario) VALUES
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13); 

-- ============================================================
-- CARRERAS
-- ============================================================
INSERT INTO carreras (idCarrera, nombreCarrera, claveCarrera, descripcion) VALUES
(1, 'Ingeniería en Software', 'ISW', 'Carrera enfocada en desarrollo de software.'),
(2, 'Ingeniería Industrial', 'IIN', 'Carrera enfocada en procesos industriales.'),
(3, 'Ingeniería en Mecatrónica', 'IMT', 'Carrera enfocada en automatización y robótica.');

-- ============================================================
-- PERIODOS ESCOLARES
-- ============================================================
INSERT INTO periodosEscolares (idPeriodo, nombrePeriodo, fechaInicio, fechaFin)
VALUES
(1, 'Septiembre–Diciembre 2025', '2025-09-04', '2025-12-14'),
(2, 'Enero–Abril 2026', '2026-01-10', '2026-04-18');

INSERT INTO alumnos (idUsuario, idCarrera) VALUES
(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),
(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),
(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),
(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),
(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),
(56,3),(57,3),(58,3),(59,3),(60,3),(61,3),(62,3),(63,3);

INSERT INTO grupos (nombreGrupo, idCarrera, idPeriodo) VALUES
('ISW-1A', 1, 1),
('ISW-1B', 1, 1),
('ISW-1C', 1, 1),
('ISW-2A', 1, 1),
('IIN-1A', 2, 1),
('IIN-1B', 2, 1),
('IMT-1A', 3, 1);


INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(1,1,'2025-09-10'),
(2,1,'2025-09-10'),
(3,1,'2025-09-10'),
(4,1,'2025-09-10'),
(5,1,'2025-09-10'),
(6,1,'2025-09-10'),
(7,1,'2025-09-10'),
(8,1,'2025-09-10'),
(9,1,'2025-09-10'),
(10,1,'2025-09-10');

INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(11,2,'2025-09-10'),
(12,2,'2025-09-10'),
(13,2,'2025-09-10'),
(14,2,'2025-09-10'),
(15,2,'2025-09-10'),
(16,2,'2025-09-10'),
(17,2,'2025-09-10'),
(18,2,'2025-09-10'),
(19,2,'2025-09-10'),
(20,2,'2025-09-10');

INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(21,3,'2025-09-10'),
(22,3,'2025-09-10'),
(23,3,'2025-09-10'),
(24,3,'2025-09-10'),
(25,3,'2025-09-10');

INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(26,4,'2025-09-10'),
(27,4,'2025-09-10'),
(28,4,'2025-09-10'),
(29,4,'2025-09-10'),
(30,4,'2025-09-10');

INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(31,5,'2025-09-10'),
(32,5,'2025-09-10'),
(33,5,'2025-09-10'),
(34,5,'2025-09-10'),
(35,5,'2025-09-10'),
(36,5,'2025-09-10');

INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(37,6,'2025-09-10'),
(38,6,'2025-09-10'),
(39,6,'2025-09-10'),
(40,6,'2025-09-10'),
(41,6,'2025-09-10'),
(42,6,'2025-09-10');

INSERT INTO inscripciones (idAlumno, idGrupo, fechaInscripcion) VALUES
(43,7,'2025-09-10'),
(44,7,'2025-09-10'),
(45,7,'2025-09-10'),
(46,7,'2025-09-10'),
(47,7,'2025-09-10'),
(48,7,'2025-09-10'),
(49,7,'2025-09-10'),
(50,7,'2025-09-10');

INSERT INTO materias (nombreMateria, claveMateria, horasSemana, idPeriodo) VALUES
('Programación Web', 'PW2025', 5, 1),
('Bases de Datos', 'BD2025', 5, 1),
('Estructuras de Datos', 'ED2025', 4, 1),
('Ingeniería de Software', 'IS2025', 5, 1),
('Cálculo Diferencial', 'CD2025', 4, 1),
('Física Aplicada', 'FA2025', 4, 1),
('Administración Industrial', 'AI2025', 4, 1),
('Simulación de Procesos', 'SP2025', 4, 1),
('Estancia II', 'EST2025', 6, 1);

-- ===============================
-- ASIGNACIONES PARA ISW-1A (Grupo 1)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(1, 1, 1, 1),  
(2, 2, 1, 1),  
(3, 3, 1, 1),  
(4, 4, 1, 1),  
(11, 9, 1, 1); 

-- ===============================
-- ASIGNACIONES PARA ISW-1B (Grupo 2)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(1, 1, 2, 1),
(2, 2, 2, 1),
(3, 3, 2, 1),
(4, 4, 2, 1),
(11, 9, 2, 1);

-- ===============================
-- ASIGNACIONES PARA ISW-1C (Grupo 3)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(9, 1, 3, 1),
(10, 2, 3, 1),
(11, 3, 3, 1),
(3, 4, 3, 1),
(11, 9, 3, 1);

-- ===============================
-- ASIGNACIONES PARA ISW-2A (Grupo 4)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(5, 1, 4, 1),
(6, 2, 4, 1),
(7, 3, 4, 1),
(11, 4, 4, 1),
(11, 9, 4, 1);

-- ===============================
-- ASIGNACIONES PARA IIN-1A (Grupo 5)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(5, 5, 5, 1), 
(6, 6, 5, 1),  
(10, 7, 5, 1),  
(9, 8, 5, 1);   


-- ===============================
-- ASIGNACIONES PARA IIN-1B (Grupo 6)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(5, 5, 6, 1),
(6, 6, 6, 1),
(9, 7, 6, 1),
(11, 8, 6, 1);

-- ===============================
-- ASIGNACIONES PARA IMT-1A (Grupo 7)
-- ===============================
INSERT INTO asignaciones (idDocente, idMateria, idGrupo, idPeriodo) VALUES
(7, 6, 7, 1), 
(8, 7, 7, 1), 
(9, 8, 7, 1), 
(10, 9, 7, 1); 

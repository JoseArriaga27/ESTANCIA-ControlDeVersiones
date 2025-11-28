create database gestionescolar;
use gestionescolar;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS calificaciones;
DROP TABLE IF EXISTS inscripciones;
DROP TABLE IF EXISTS asignaciones;
DROP TABLE IF EXISTS materias;
DROP TABLE IF EXISTS grupos;
DROP TABLE IF EXISTS periodosescolares;
DROP TABLE IF EXISTS alumnos;
DROP TABLE IF EXISTS docentes;
DROP TABLE IF EXISTS carreras;
DROP TABLE IF EXISTS respaldos;
DROP TABLE IF EXISTS reportes;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- TABLA: usuarios
-- ============================================================
CREATE TABLE usuarios (
  idUsuario INT NOT NULL AUTO_INCREMENT,
  matricula VARCHAR(20) NOT NULL,
  nombres VARCHAR(100) NOT NULL,
  apePaterno VARCHAR(50) NOT NULL,
  apeMaterno VARCHAR(50),
  sexo ENUM('Masculino','Femenino') NOT NULL,
  fechaNacimiento DATE NOT NULL,
  correo VARCHAR(100) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('Administrador','Docente','Alumno','Administrativo') NOT NULL,
  activo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (idUsuario),
  UNIQUE KEY matricula (matricula),
  UNIQUE KEY correo (correo)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios VALUES
("1","ADM001","Sandra","León","Martínez","Femenino","1980-02-11","sandra.leon@upemor.edu.mx","admin123","Administrador","1"),
("2","ADM002","Roberto Enrique","Lopez","Díaz","Masculino","1978-06-22","roberto.enrique@upemor.edu.mx","admin2025","Administrador","1"),
("3","ADMT001","Carmen","Vargas","Guzmán","Femenino","1985-03-12","carmen.vargas@upemor.edu.mx","$2y$10$eAmPRyj.ls6DyKkSixdzxeL/5XEjn/d.aUBVZ35ywG.ClKGOgQ1pi","Administrativo","1"),
("4","ADMT002","Elena","Torres","Soto","Femenino","1989-11-03","elena.torres@upemor.edu.mx","admin02","Administrativo","1"),
("5","ADMT003","Ricardo","Molina","Pérez","Masculino","1983-05-18","ricardo.molina@upemor.edu.mx","admin03","Administrativo","1"),
("6","ADMT004","Gerardo","Acosta","Cruz","Masculino","1990-08-20","gerardo.acosta@upemor.edu.mx","admin04","Administrativo","1"),
("7","DOC001","José","Arriaga","Monroy","Masculino","1985-04-10","jose.arriaga@upemor.edu.mx","pass1","Docente","1"),
("8","DOC002","Erick","Méndez","Corona","Masculino","1990-02-18","erick.mendez@upemor.edu.mx","pass2","Docente","1"),
("9","DOC003","Mariana","Castillo","García","Femenino","1987-07-12","mariana.castillo@upemor.edu.mx","$2y$10$qtsfU2/8f3e0pB3mcAMzzu5ey1ZnguyJCpY5Qhz0wOA6KBBH7m42G","Docente","1"),
("10","DOC004","Carlos","Ramírez","López","Masculino","1982-01-15","carlos.ramirez@upemor.edu.mx","pass4","Docente","1"),
("11","DOC005","Sofía","Pineda","Rojas","Femenino","1991-03-23","sofia.pineda@upemor.edu.mx","pass5","Docente","1"),
("12","DOC006","Luis","Hernández","Santos","Masculino","1989-09-09","luis.hernandez@upemor.edu.mx","pass6","Docente","1"),
("13","DOC007","Andrea","Jiménez","Flores","Femenino","1988-12-04","andrea.jimenez@upemor.edu.mx","pass7","Docente","1"),
("14","DOC008","Miguel","Gómez","Reyes","Masculino","1984-06-14","miguel.gomez@upemor.edu.mx","$2y$10$39wfGqjI4D11TDJv2fV0ReFGGO2gYYXlFrrK2VxdvYn9YAqbehd.G","Docente","1"),
("15","DOC009","Patricia","Serrano","Luna","Femenino","1992-10-21","patricia.serrano@upemor.edu.mx","pass9","Docente","1"),
("16","DOC010","Alberto","Cordero","Mejía","Masculino","1986-11-29","alberto.cordero@upemor.edu.mx","pass10","Docente","1"),
("17","DOC011","Camila","Solórzano","Velázquez","Femenino","1991-06-14","camila.solorzano@upemor.edu.mx","camila01","Docente","1"),
("18","ALU001","Santiago","Ramírez","López","Masculino","2003-05-12","santiago.ramirez@upemor.edu.mx","pass01","Alumno","1"),
("19","ALU002","Valeria","Martínez","Gómez","Femenino","2004-03-22","valeria.martinez@upemor.edu.mx","pass02","Alumno","1"),
("20","ALU003","Luis Ángel","Torres","Sánchez","Masculino","2003-08-15","luis.torres@upemor.edu.mx","pass03","Alumno","1"),
("21","ALU004","Ximena","Castillo","Rojas","Femenino","2004-01-11","ximena.castillo@upemor.edu.mx","pass04","Alumno","1"),
("22","ALU005","Alexis","Mendoza","Flores","Masculino","2003-07-02","alexis.mendoza@upemor.edu.mx","$2y$10$Cl6BroBzMD72QmlapmmgCOs1/PNnhfVNj58NEcyeSxiNa42gLftJW","Alumno","1"),
("23","ALU006","Regina","García","Soto","Femenino","2004-10-10","regina.garcia@upemor.edu.mx","$2y$10$OxIAWO/mjogn6F/1z8LacuzWghhL1qYCvVTGoUVCUcBlLb6EZ.3CS","Alumno","1"),
("24","ALU007","Diego","Hernández","Pérez","Masculino","2003-12-09","diego.hernandez@upemor.edu.mx","pass07","Alumno","1"),
("25","ALU008","María José","Ortega","Serrano","Femenino","2004-05-28","mariaj.ortega@upemor.edu.mx","pass08","Alumno","1"),
("26","ALU009","Fernando","Gómez","Acosta","Masculino","2003-09-03","fernando.gomez@upemor.edu.mx","pass09","Alumno","1"),
("27","ALU010","Ana Sofía","Navarro","Vega","Femenino","2004-04-14","anasofia.navarro@upemor.edu.mx","pass10","Alumno","1"),
("28","ALU011","Andrés","Santos","Castro","Masculino","2003-11-11","andres.santos@upemor.edu.mx","pass11","Alumno","1"),
("29","ALU012","Camila","Pineda","Duarte","Femenino","2004-06-07","camila.pineda@upemor.edu.mx","pass12","Alumno","1"),
("30","ALU013","Jorge","Flores","Molina","Masculino","2003-07-21","jorge.flores@upemor.edu.mx","pass13","Alumno","1"),
("31","ALU014","Natalia","Silva","Ramos","Femenino","2004-12-10","natalia.silva@upemor.edu.mx","pass14","Alumno","1"),
("32","ALU015","Ricardo","Reyes","Correa","Masculino","2003-03-26","ricardo.reyes@upemor.edu.mx","pass15","Alumno","1"),
("33","ALU016","Danna","Núñez","Solís","Femenino","2004-02-17","danna.nunez@upemor.edu.mx","pass16","Alumno","1"),
("34","ALU017","Oscar","Vargas","Pérez","Masculino","2003-10-30","oscar.vargas@upemor.edu.mx","pass17","Alumno","1"),
("35","ALU018","Montserrat","Juárez","Linares","Femenino","2004-09-05","montserrat.juarez@upemor.edu.mx","pass18","Alumno","1"),
("36","ALU019","Iván","Carmona","Lara","Masculino","2003-02-14","ivan.carmona@upemor.edu.mx","pass19","Alumno","1"),
("37","ALU020","Paola","Salazar","Paz","Femenino","2004-07-19","paola.salazar@upemor.edu.mx","pass20","Alumno","1"),
("38","ALU021","Marco","Fuentes","Toledo","Masculino","2003-08-22","marco.fuentes@upemor.edu.mx","pass21","Alumno","1"),
("39","ALU022","Carolina","Rosales","Ibarra","Femenino","2004-11-03","carolina.rosales@upemor.edu.mx","pass22","Alumno","1"),
("40","ALU023","Adrián","Pérez","González","Masculino","2003-04-05","adrian.perez@upemor.edu.mx","$2y$10$boHhz1APVmZ6Q8PnGI7NJO74FY.yyTt1tqinSVREYZ69s7a0FZHh6","Alumno","1"),
("41","ALU024","Itzel","Prieto","Sosa","Femenino","2004-01-28","itzel.prieto@upemor.edu.mx","pass24","Alumno","1"),
("42","ALU025","Eduardo","López","Castañeda","Masculino","2003-12-18","eduardo.lopez@upemor.edu.mx","pass25","Alumno","1"),
("43","ALU026","Samantha","Requena","Trejo","Femenino","2004-02-24","samantha.requena@upemor.edu.mx","pass26","Alumno","1"),
("44","ALU027","Brandon","Campos","Valdez","Masculino","2003-09-09","brandon.campos@upemor.edu.mx","pass27","Alumno","1"),
("45","ALU028","Ariana","Sánchez","Guerrero","Femenino","2004-06-11","ariana.sanchez@upemor.edu.mx","pass28","Alumno","1"),
("46","ALU029","Héctor","Ceballos","Mena","Masculino","2003-10-16","hector.ceballos@upemor.edu.mx","$2y$10$LGHzRSxO.n.9g/zOV0/hzOlo7XTY/JhfJurkDi66tJU/hE9vDBF8q","Alumno","1"),
("47","ALU030","Julieta","Morales","Quiroz","Femenino","2004-05-09","julieta.morales@upemor.edu.mx","pass30","Alumno","1"),
("48","ALU031","Emiliano","Vera","Loyola","Masculino","2003-01-17","emiliano.vera@upemor.edu.mx","pass31","Alumno","1"),
("49","ALU032","Zoe","Tejeda","Aguilar","Femenino","2004-03-01","zoe.tejeda@upemor.edu.mx","pass32","Alumno","1"),
("50","ALU033","Rodrigo","Sierra","Blanco","Masculino","2003-07-26","rodrigo.sierra@upemor.edu.mx","pass33","Alumno","1"),
("51","ALU034","Aylin","Castaño","Salinas","Femenino","2004-12-01","aylin.castano@upemor.edu.mx","pass34","Alumno","1"),
("52","ALU035","Jonathan","Mejía","Rubio","Masculino","2003-04-14","jonathan.mejia@upemor.edu.mx","pass35","Alumno","1"),
("53","ALU036","Daniela","Zamora","Rivas","Femenino","2004-08-08","daniela.zamora@upemor.edu.mx","pass36","Alumno","1"),
("54","ALU037","Pablo","Solano","Ortega","Masculino","2003-12-30","pablo.solano@upemor.edu.mx","pass37","Alumno","1"),
("55","ALU038","Victoria","Galindo","Pérez","Femenino","2004-10-02","victoria.galindo@upemor.edu.mx","pass38","Alumno","1"),
("56","ALU039","Mauricio","Roldán","Campos","Masculino","2003-03-11","mauricio.roldan@upemor.edu.mx","pass39","Alumno","1"),
("57","ALU040","Nicole","Benítez","Serrano","Femenino","2004-06-18","nicole.benitez@upemor.edu.mx","pass40","Alumno","1"),
("58","ALU041","Alan","Arellano","Vidal","Masculino","2003-11-01","alan.arellano@upemor.edu.mx","pass41","Alumno","1"),
("59","ALU042","Elena","Zúñiga","Cruz","Femenino","2004-04-23","elena.zuniga@upemor.edu.mx","pass42","Alumno","1"),
("60","ALU043","Sebastián","Peña","García","Masculino","2003-09-20","sebastian.pena@upemor.edu.mx","pass43","Alumno","1"),
("61","ALU044","Dafne","Lozano","Jiménez","Femenino","2004-02-19","dafne.lozano@upemor.edu.mx","pass44","Alumno","1"),
("62","ALU045","Óscar","Valle","Ruiz","Masculino","2003-07-07","oscar.valle@upemor.edu.mx","pass45","Alumno","1"),
("63","ALU046","Renata","Quiñones","Haro","Femenino","2004-11-18","renata.quinones@upemor.edu.mx","pass46","Alumno","1"),
("64","ALU047","Gael","Ramos","Rosales","Masculino","2003-02-27","gael.ramos@upemor.edu.mx","pass47","Alumno","1"),
("65","ALU048","Miranda","Esquivel","Pineda","Femenino","2004-08-14","miranda.esquivel@upemor.edu.mx","pass48","Alumno","1"),
("66","ALU049","Leonardo","Barrios","Muñoz","Masculino","2003-05-25","leonardo.barrios@upemor.edu.mx","pass49","Alumno","1"),
("67","ALU050","Abril","Delgado","Rey","Femenino","2004-03-30","abril.delgado@upemor.edu.mx","pass50","Alumno","1");

-- ============================================================
-- TABLA: docentes
-- ============================================================
CREATE TABLE docentes (
  idDocente INT NOT NULL AUTO_INCREMENT,
  idUsuario INT NOT NULL,
  PRIMARY KEY (idDocente),
  KEY idUsuario (idUsuario),
  CONSTRAINT docentes_ibfk_1 FOREIGN KEY (idUsuario)
    REFERENCES usuarios (idUsuario)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

INSERT INTO docentes VALUES
("1","3"),
("2","4"),
("3","5"),
("4","6"),
("5","7"),
("6","8"),
("7","9"),
("8","10"),
("9","11"),
("10","12"),
("11","17");

-- ============================================================
-- TABLA: carreras
-- ============================================================
CREATE TABLE carreras (
  idCarrera INT NOT NULL AUTO_INCREMENT,
  nombreCarrera VARCHAR(100) NOT NULL,
  claveCarrera VARCHAR(20) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  PRIMARY KEY (idCarrera),
  UNIQUE KEY nombreCarrera (nombreCarrera),
  UNIQUE KEY claveCarrera (claveCarrera)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO carreras VALUES
("1","Ingeniería en Software","ISW","Carrera enfocada en desarrollo de software."),
("2","Ingeniería Industrial","IIN","Carrera enfocada en procesos industriales."),
("3","Ingeniería en Mecatrónica","IMT","Carrera enfocada en automatización y robótica."),
("4","ITI","ITI2025","Informatica");

-- ============================================================
-- TABLA: alumnos
-- ============================================================
CREATE TABLE alumnos (
  idAlumno INT NOT NULL AUTO_INCREMENT,
  idUsuario INT NOT NULL,
  idCarrera INT DEFAULT NULL,
  PRIMARY KEY (idAlumno),
  KEY idUsuario (idUsuario),
  KEY idCarrera (idCarrera),
  CONSTRAINT alumnos_ibfk_1 FOREIGN KEY (idUsuario)
    REFERENCES usuarios (idUsuario)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT alumnos_ibfk_2 FOREIGN KEY (idCarrera)
    REFERENCES carreras (idCarrera)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

INSERT INTO alumnos VALUES
("5","18","1"),
("6","19","1"),
("7","20","1"),
("8","21","1"),
("9","22","1"),
("10","23","1"),
("11","24","1"),
("12","25","1"),
("13","26","1"),
("14","27","1"),
("15","28","1"),
("16","29","1"),
("17","30","1"),
("18","31","1"),
("19","32","1"),
("20","33","1"),
("21","34","1"),
("22","35","1"),
("23","36","1"),
("24","37","1"),
("25","38","1"),
("26","39","1"),
("27","40","1"),
("28","41","1"),
("29","42","1"),
("30","43","1"),
("31","44","2"),
("32","45","2"),
("33","46","2"),
("34","47","2"),
("35","48","2"),
("36","49","2"),
("37","50","2"),
("38","51","2"),
("39","52","2"),
("40","53","2"),
("41","54","2"),
("42","55","2"),
("43","56","3"),
("44","57","3"),
("45","58","3"),
("46","59","3"),
("47","60","3"),
("48","61","3"),
("49","62","3"),
("50","63","3");

-- ============================================================
-- TABLA: periodosescolares
-- ============================================================
CREATE TABLE periodosescolares (
  idPeriodo INT NOT NULL AUTO_INCREMENT,
  nombrePeriodo VARCHAR(50) NOT NULL,
  fechaInicio DATE NOT NULL,
  fechaFin DATE NOT NULL,
  PRIMARY KEY (idPeriodo)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO periodosescolares VALUES
("1","Septiembre–Diciembre 2025","2025-09-04","2025-12-14"),
("2","Enero–Abril 2026","2026-01-10","2026-04-18");

-- ============================================================
-- TABLA: grupos
-- ============================================================
CREATE TABLE grupos (
  idGrupo INT NOT NULL AUTO_INCREMENT,
  nombreGrupo VARCHAR(50) NOT NULL,
  idCarrera INT NOT NULL,
  idPeriodo INT NOT NULL,
  PRIMARY KEY (idGrupo),
  KEY idCarrera (idCarrera),
  KEY idPeriodo (idPeriodo),
  CONSTRAINT grupos_ibfk_1 FOREIGN KEY (idCarrera)
    REFERENCES carreras (idCarrera)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT grupos_ibfk_2 FOREIGN KEY (idPeriodo)
    REFERENCES periodosescolares (idPeriodo)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO grupos VALUES
("1","ISW-1A","1","1"),
("2","ISW-1B","1","1"),
("3","ISW-1C","1","1"),
("4","ISW-2A","1","1"),
("5","IIN-1A","2","1"),
("6","IIN-1B","2","1"),
("7","IMT-1A","3","1");

-- ============================================================
-- TABLA: materias
-- ============================================================
CREATE TABLE materias (
  idMateria INT NOT NULL AUTO_INCREMENT,
  nombreMateria VARCHAR(100) NOT NULL,
  claveMateria VARCHAR(20) NOT NULL,
  horasSemana INT DEFAULT NULL,
  idPeriodo INT DEFAULT NULL,
  PRIMARY KEY (idMateria),
  UNIQUE KEY claveMateria (claveMateria),
  KEY idPeriodo (idPeriodo),
  CONSTRAINT materias_ibfk_1 FOREIGN KEY (idPeriodo)
    REFERENCES periodosescolares (idPeriodo)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

INSERT INTO materias VALUES
("1","Programación Web","PW2025","5","1"),
("2","Bases de Datos","BD2025","5","1"),
("3","Estructuras de Datos","ED2025","4","1"),
("4","Ingeniería de Software","IS2025","5","1"),
("5","Cálculo Diferencial","CD2025","4","1"),
("6","Física Aplicada","FA2025","4","1"),
("7","Administración Industrial","AI2025","4","1"),
("8","Simulación de Procesos","SP2025","4","1"),
("9","Estancia II","EST2025","6","1");

-- ============================================================
-- TABLA: asignaciones
-- ============================================================
CREATE TABLE asignaciones (
  idAsignacion INT NOT NULL AUTO_INCREMENT,
  idDocente INT NOT NULL,
  idMateria INT NOT NULL,
  idGrupo INT NOT NULL,
  idPeriodo INT NOT NULL,
  PRIMARY KEY (idAsignacion),
  KEY idDocente (idDocente),
  KEY idMateria (idMateria),
  KEY idGrupo (idGrupo),
  KEY idPeriodo (idPeriodo),
  CONSTRAINT asignaciones_ibfk_1 FOREIGN KEY (idDocente)
    REFERENCES docentes (idDocente)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT asignaciones_ibfk_2 FOREIGN KEY (idMateria)
    REFERENCES materias (idMateria)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT asignaciones_ibfk_3 FOREIGN KEY (idGrupo)
    REFERENCES grupos (idGrupo)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT asignaciones_ibfk_4 FOREIGN KEY (idPeriodo)
    REFERENCES periodosescolares (idPeriodo)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;

INSERT INTO asignaciones VALUES
("1","1","1","1","1"),
("2","2","2","1","1"),
("3","3","3","1","1"),
("4","4","4","1","1"),
("5","11","9","1","1"),
("6","1","1","2","1"),
("7","2","2","2","1"),
("8","3","3","2","1"),
("9","4","4","2","1"),
("10","11","9","2","1"),
("11","9","1","3","1"),
("12","10","2","3","1"),
("13","11","3","3","1"),
("14","3","4","3","1"),
("15","11","9","3","1"),
("16","5","1","4","1"),
("17","6","2","4","1"),
("18","7","3","4","1"),
("19","11","4","4","1"),
("20","11","9","4","1"),
("21","5","5","5","1"),
("22","6","6","5","1"),
("23","10","7","5","1"),
("24","9","8","5","1"),
("25","5","5","6","1"),
("26","6","6","6","1"),
("27","9","7","6","1"),
("28","11","8","6","1"),
("29","7","6","7","1"),
("30","8","7","7","1"),
("31","9","8","7","1"),
("32","10","9","7","1"),
("33","8","2","1","1");

-- ============================================================
-- TABLA: inscripciones
-- ============================================================
CREATE TABLE inscripciones (
  idInscripcion INT NOT NULL AUTO_INCREMENT,
  idAlumno INT NOT NULL,
  idGrupo INT NOT NULL,
  fechaInscripcion DATE NOT NULL,
  PRIMARY KEY (idInscripcion),
  KEY idAlumno (idAlumno),
  KEY idGrupo (idGrupo),
  CONSTRAINT inscripciones_ibfk_1 FOREIGN KEY (idAlumno)
    REFERENCES alumnos (idAlumno)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT inscripciones_ibfk_2 FOREIGN KEY (idGrupo)
    REFERENCES grupos (idGrupo)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

INSERT INTO inscripciones VALUES
("5","5","1","2025-09-10"),
("6","6","1","2025-09-10"),
("7","7","1","2025-09-10"),
("8","8","1","2025-09-10"),
("9","9","1","2025-09-10"),
("10","10","1","2025-09-10"),
("11","11","2","2025-09-10"),
("12","12","2","2025-09-10"),
("13","13","2","2025-09-10"),
("14","14","2","2025-09-10"),
("15","15","2","2025-09-10"),
("16","16","2","2025-09-10"),
("17","17","2","2025-09-10"),
("18","18","2","2025-09-10"),
("19","19","2","2025-09-10"),
("20","20","2","2025-09-10"),
("21","21","3","2025-09-10"),
("22","22","3","2025-09-10"),
("23","23","3","2025-09-10"),
("24","24","3","2025-09-10"),
("25","25","3","2025-09-10"),
("26","26","4","2025-09-10"),
("27","27","4","2025-09-10"),
("28","28","4","2025-09-10"),
("29","29","4","2025-09-10"),
("30","30","4","2025-09-10"),
("31","31","5","2025-09-10"),
("32","32","5","2025-09-10"),
("33","33","5","2025-09-10"),
("34","34","5","2025-09-10"),
("35","35","5","2025-09-10"),
("36","36","5","2025-09-10"),
("37","37","6","2025-09-10"),
("38","38","6","2025-09-10"),
("39","39","6","2025-09-10"),
("40","40","6","2025-09-10"),
("41","41","6","2025-09-10"),
("42","42","6","2025-09-10"),
("43","43","7","2025-09-10"),
("44","44","7","2025-09-10"),
("45","45","7","2025-09-10"),
("46","46","7","2025-09-10"),
("47","47","7","2025-09-10"),
("48","48","7","2025-09-10"),
("49","49","7","2025-09-10"),
("50","50","7","2025-09-10");

-- ============================================================
-- TABLA: calificaciones
-- ============================================================
CREATE TABLE calificaciones (
  idCalificacion INT NOT NULL AUTO_INCREMENT,
  idInscripcion INT NOT NULL,
  idMateria INT NOT NULL,
  calificacionParcial1 DECIMAL(5,2) DEFAULT NULL,
  calificacionParcial2 DECIMAL(5,2) DEFAULT NULL,
  calificacionParcial3 DECIMAL(5,2) DEFAULT NULL,
  calificacionFinal DECIMAL(5,2) DEFAULT NULL,
  PRIMARY KEY (idCalificacion),
  KEY idInscripcion (idInscripcion),
  KEY idMateria (idMateria),
  CONSTRAINT calificaciones_ibfk_1 FOREIGN KEY (idInscripcion)
    REFERENCES inscripciones (idInscripcion)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calificaciones_ibfk_2 FOREIGN KEY (idMateria)
    REFERENCES materias (idMateria)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

INSERT INTO calificaciones VALUES
("1","26","3","9.00","9.00","9.00","9.00"),
("2","27","3","9.00","9.00","9.00","9.00"),
("3","28","3","8.00","5.00","5.00","6.00"),
("4","29","3","9.00","9.00","9.00","9.00"),
("5","30","3","9.00","9.00","9.00","9.00"),
("6","31","7","7.00","6.00","5.00","6.00"),
("7","32","7","9.00","9.00","9.00","9.00"),
("8","33","7","9.00","6.00","9.00","8.00"),
("9","34","7","3.00","9.00","9.00","7.00"),
("10","35","7","9.00","4.00","9.00","7.33"),
("11","36","7","9.00","9.00","9.00","9.00");

SET FOREIGN_KEY_CHECKS = 1;

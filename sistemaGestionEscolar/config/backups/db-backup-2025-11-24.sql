DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE `alumnos` (
  `idAlumno` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idCarrera` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAlumno`),
  KEY `idUsuario` (`idUsuario`),
  KEY `idCarrera` (`idCarrera`),
  CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`idCarrera`) REFERENCES `carreras` (`idCarrera`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO alumnos VALUES("5","18","1");
INSERT INTO alumnos VALUES("6","19","1");
INSERT INTO alumnos VALUES("7","20","1");
INSERT INTO alumnos VALUES("8","21","1");
INSERT INTO alumnos VALUES("9","22","1");
INSERT INTO alumnos VALUES("10","23","1");
INSERT INTO alumnos VALUES("11","24","1");
INSERT INTO alumnos VALUES("12","25","1");
INSERT INTO alumnos VALUES("13","26","1");
INSERT INTO alumnos VALUES("14","27","1");
INSERT INTO alumnos VALUES("15","28","1");
INSERT INTO alumnos VALUES("16","29","1");
INSERT INTO alumnos VALUES("17","30","1");
INSERT INTO alumnos VALUES("18","31","1");
INSERT INTO alumnos VALUES("19","32","1");
INSERT INTO alumnos VALUES("20","33","1");
INSERT INTO alumnos VALUES("21","34","1");
INSERT INTO alumnos VALUES("22","35","1");
INSERT INTO alumnos VALUES("23","36","1");
INSERT INTO alumnos VALUES("24","37","1");
INSERT INTO alumnos VALUES("25","38","1");
INSERT INTO alumnos VALUES("26","39","1");
INSERT INTO alumnos VALUES("27","40","1");
INSERT INTO alumnos VALUES("28","41","1");
INSERT INTO alumnos VALUES("29","42","1");
INSERT INTO alumnos VALUES("30","43","1");
INSERT INTO alumnos VALUES("31","44","2");
INSERT INTO alumnos VALUES("32","45","2");
INSERT INTO alumnos VALUES("33","46","2");
INSERT INTO alumnos VALUES("34","47","2");
INSERT INTO alumnos VALUES("35","48","2");
INSERT INTO alumnos VALUES("36","49","2");
INSERT INTO alumnos VALUES("37","50","2");
INSERT INTO alumnos VALUES("38","51","2");
INSERT INTO alumnos VALUES("39","52","2");
INSERT INTO alumnos VALUES("40","53","2");
INSERT INTO alumnos VALUES("41","54","2");
INSERT INTO alumnos VALUES("42","55","2");
INSERT INTO alumnos VALUES("43","56","3");
INSERT INTO alumnos VALUES("44","57","3");
INSERT INTO alumnos VALUES("45","58","3");
INSERT INTO alumnos VALUES("46","59","3");
INSERT INTO alumnos VALUES("47","60","3");
INSERT INTO alumnos VALUES("48","61","3");
INSERT INTO alumnos VALUES("49","62","3");
INSERT INTO alumnos VALUES("50","63","3");


DROP TABLE IF EXISTS `asignaciones`;
CREATE TABLE `asignaciones` (
  `idAsignacion` int(11) NOT NULL AUTO_INCREMENT,
  `idDocente` int(11) NOT NULL,
  `idMateria` int(11) NOT NULL,
  `idGrupo` int(11) NOT NULL,
  `idPeriodo` int(11) NOT NULL,
  PRIMARY KEY (`idAsignacion`),
  KEY `idDocente` (`idDocente`),
  KEY `idMateria` (`idMateria`),
  KEY `idGrupo` (`idGrupo`),
  KEY `idPeriodo` (`idPeriodo`),
  CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`idDocente`) REFERENCES `docentes` (`idDocente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`idMateria`) REFERENCES `materias` (`idMateria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asignaciones_ibfk_3` FOREIGN KEY (`idGrupo`) REFERENCES `grupos` (`idGrupo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asignaciones_ibfk_4` FOREIGN KEY (`idPeriodo`) REFERENCES `periodosescolares` (`idPeriodo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO asignaciones VALUES("1","1","1","1","1");
INSERT INTO asignaciones VALUES("2","2","2","1","1");
INSERT INTO asignaciones VALUES("3","3","3","1","1");
INSERT INTO asignaciones VALUES("4","4","4","1","1");
INSERT INTO asignaciones VALUES("5","11","9","1","1");
INSERT INTO asignaciones VALUES("6","1","1","2","1");
INSERT INTO asignaciones VALUES("7","2","2","2","1");
INSERT INTO asignaciones VALUES("8","3","3","2","1");
INSERT INTO asignaciones VALUES("9","4","4","2","1");
INSERT INTO asignaciones VALUES("10","11","9","2","1");
INSERT INTO asignaciones VALUES("11","9","1","3","1");
INSERT INTO asignaciones VALUES("12","10","2","3","1");
INSERT INTO asignaciones VALUES("13","11","3","3","1");
INSERT INTO asignaciones VALUES("14","3","4","3","1");
INSERT INTO asignaciones VALUES("15","11","9","3","1");
INSERT INTO asignaciones VALUES("16","5","1","4","1");
INSERT INTO asignaciones VALUES("17","6","2","4","1");
INSERT INTO asignaciones VALUES("18","7","3","4","1");
INSERT INTO asignaciones VALUES("19","11","4","4","1");
INSERT INTO asignaciones VALUES("20","11","9","4","1");
INSERT INTO asignaciones VALUES("21","5","5","5","1");
INSERT INTO asignaciones VALUES("22","6","6","5","1");
INSERT INTO asignaciones VALUES("23","10","7","5","1");
INSERT INTO asignaciones VALUES("24","9","8","5","1");
INSERT INTO asignaciones VALUES("25","5","5","6","1");
INSERT INTO asignaciones VALUES("26","6","6","6","1");
INSERT INTO asignaciones VALUES("27","9","7","6","1");
INSERT INTO asignaciones VALUES("28","11","8","6","1");
INSERT INTO asignaciones VALUES("29","7","6","7","1");
INSERT INTO asignaciones VALUES("30","8","7","7","1");
INSERT INTO asignaciones VALUES("31","9","8","7","1");
INSERT INTO asignaciones VALUES("32","10","9","7","1");
INSERT INTO asignaciones VALUES("33","8","2","1","1");


DROP TABLE IF EXISTS `calificaciones`;
CREATE TABLE `calificaciones` (
  `idCalificacion` int(11) NOT NULL AUTO_INCREMENT,
  `idInscripcion` int(11) NOT NULL,
  `idMateria` int(11) NOT NULL,
  `calificacionParcial1` decimal(5,2) DEFAULT NULL,
  `calificacionParcial2` decimal(5,2) DEFAULT NULL,
  `calificacionParcial3` decimal(5,2) DEFAULT NULL,
  `calificacionFinal` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`idCalificacion`),
  KEY `idInscripcion` (`idInscripcion`),
  KEY `idMateria` (`idMateria`),
  CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`idInscripcion`) REFERENCES `inscripciones` (`idInscripcion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`idMateria`) REFERENCES `materias` (`idMateria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO calificaciones VALUES("1","26","3","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("2","27","3","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("3","28","3","8.00","5.00","5.00","6.00");
INSERT INTO calificaciones VALUES("4","29","3","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("5","30","3","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("6","31","7","7.00","6.00","5.00","6.00");
INSERT INTO calificaciones VALUES("7","32","7","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("8","33","7","9.00","6.00","9.00","8.00");
INSERT INTO calificaciones VALUES("9","34","7","3.00","9.00","9.00","7.00");
INSERT INTO calificaciones VALUES("10","35","7","9.00","4.00","9.00","7.33");
INSERT INTO calificaciones VALUES("11","36","7","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("12","21","3","10.00","10.00","10.00","10.00");
INSERT INTO calificaciones VALUES("13","22","3","9.00","8.00","","");
INSERT INTO calificaciones VALUES("14","23","3","8.00","","","");
INSERT INTO calificaciones VALUES("15","24","3","10.00","","","");
INSERT INTO calificaciones VALUES("16","25","3","5.00","","","");
INSERT INTO calificaciones VALUES("17","26","4","9.00","9.00","","");
INSERT INTO calificaciones VALUES("18","27","4","9.00","9.00","","");
INSERT INTO calificaciones VALUES("19","28","4","7.00","8.00","","");
INSERT INTO calificaciones VALUES("20","29","4","9.00","8.00","","");
INSERT INTO calificaciones VALUES("21","30","4","10.00","9.00","10.00","9.67");
INSERT INTO calificaciones VALUES("22","37","8","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("23","38","8","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("24","39","8","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("25","40","8","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("26","41","8","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("27","42","8","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("28","5","9","8.00","7.00","","");
INSERT INTO calificaciones VALUES("29","6","9","7.00","4.00","7.00","6.00");
INSERT INTO calificaciones VALUES("30","7","9","5.00","10.00","5.00","6.67");
INSERT INTO calificaciones VALUES("31","8","9","2.00","4.00","6.00","4.00");
INSERT INTO calificaciones VALUES("32","9","9","5.00","7.00","8.00","6.67");
INSERT INTO calificaciones VALUES("33","10","9","4.00","7.00","7.00","6.00");
INSERT INTO calificaciones VALUES("34","11","9","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("35","12","9","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("36","13","9","9.00","9.00","10.00","9.33");
INSERT INTO calificaciones VALUES("37","14","9","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("38","15","9","9.00","9.00","9.00","9.00");
INSERT INTO calificaciones VALUES("39","16","9","9.00","8.00","8.00","8.33");
INSERT INTO calificaciones VALUES("40","17","9","8.00","8.00","8.00","8.00");
INSERT INTO calificaciones VALUES("41","18","9","8.00","7.00","7.00","7.33");
INSERT INTO calificaciones VALUES("42","19","9","7.00","7.00","8.00","7.33");
INSERT INTO calificaciones VALUES("43","20","9","9.00","8.00","8.00","8.33");
INSERT INTO calificaciones VALUES("44","21","9","10.00","8.00","8.00","8.67");
INSERT INTO calificaciones VALUES("45","22","9","2.00","","","");
INSERT INTO calificaciones VALUES("46","23","9","6.00","","","");
INSERT INTO calificaciones VALUES("47","24","9","8.00","","","");
INSERT INTO calificaciones VALUES("48","25","9","9.00","","","");
INSERT INTO calificaciones VALUES("49","26","9","10.00","","","");
INSERT INTO calificaciones VALUES("50","27","9","8.00","","","");
INSERT INTO calificaciones VALUES("51","28","9","9.00","","","");
INSERT INTO calificaciones VALUES("52","29","9","9.00","","","");
INSERT INTO calificaciones VALUES("53","30","9","10.00","10.00","10.00","10.00");


DROP TABLE IF EXISTS `carreras`;
CREATE TABLE `carreras` (
  `idCarrera` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCarrera` varchar(100) NOT NULL,
  `claveCarrera` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idCarrera`),
  UNIQUE KEY `nombreCarrera` (`nombreCarrera`),
  UNIQUE KEY `claveCarrera` (`claveCarrera`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO carreras VALUES("1","Ingeniería en Software","ISW","Carrera enfocada en desarrollo de software.");
INSERT INTO carreras VALUES("2","Ingeniería Industrial","IIN","Carrera enfocada en procesos industriales.");
INSERT INTO carreras VALUES("3","Ingeniería en Mecatrónica","IMT","Carrera enfocada en automatización y robótica.");
INSERT INTO carreras VALUES("4","ITI","ITI2025","Informatica");


DROP TABLE IF EXISTS `docentes`;
CREATE TABLE `docentes` (
  `idDocente` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  PRIMARY KEY (`idDocente`),
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO docentes VALUES("1","3");
INSERT INTO docentes VALUES("2","4");
INSERT INTO docentes VALUES("3","5");
INSERT INTO docentes VALUES("4","6");
INSERT INTO docentes VALUES("5","7");
INSERT INTO docentes VALUES("6","8");
INSERT INTO docentes VALUES("7","9");
INSERT INTO docentes VALUES("8","10");
INSERT INTO docentes VALUES("9","11");
INSERT INTO docentes VALUES("10","12");
INSERT INTO docentes VALUES("12","13");
INSERT INTO docentes VALUES("13","14");
INSERT INTO docentes VALUES("14","15");
INSERT INTO docentes VALUES("15","16");
INSERT INTO docentes VALUES("11","17");


DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos` (
  `idGrupo` int(11) NOT NULL AUTO_INCREMENT,
  `nombreGrupo` varchar(50) NOT NULL,
  `idCarrera` int(11) NOT NULL,
  `idPeriodo` int(11) NOT NULL,
  PRIMARY KEY (`idGrupo`),
  KEY `idCarrera` (`idCarrera`),
  KEY `idPeriodo` (`idPeriodo`),
  CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`idCarrera`) REFERENCES `carreras` (`idCarrera`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grupos_ibfk_2` FOREIGN KEY (`idPeriodo`) REFERENCES `periodosescolares` (`idPeriodo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO grupos VALUES("1","ISW-1A","1","1");
INSERT INTO grupos VALUES("2","ISW-1B","1","1");
INSERT INTO grupos VALUES("3","ISW-1C","1","1");
INSERT INTO grupos VALUES("4","ISW-2A","1","1");
INSERT INTO grupos VALUES("5","IIN-1A","2","1");
INSERT INTO grupos VALUES("6","IIN-1B","2","1");
INSERT INTO grupos VALUES("7","IMT-1A","3","1");


DROP TABLE IF EXISTS `inscripciones`;
CREATE TABLE `inscripciones` (
  `idInscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `idAlumno` int(11) NOT NULL,
  `idGrupo` int(11) NOT NULL,
  `fechaInscripcion` date NOT NULL,
  PRIMARY KEY (`idInscripcion`),
  KEY `idAlumno` (`idAlumno`),
  KEY `idGrupo` (`idGrupo`),
  CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`idAlumno`) REFERENCES `alumnos` (`idAlumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`idGrupo`) REFERENCES `grupos` (`idGrupo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO inscripciones VALUES("5","5","1","2025-09-10");
INSERT INTO inscripciones VALUES("6","6","1","2025-09-10");
INSERT INTO inscripciones VALUES("7","7","1","2025-09-10");
INSERT INTO inscripciones VALUES("8","8","1","2025-09-10");
INSERT INTO inscripciones VALUES("9","9","1","2025-09-10");
INSERT INTO inscripciones VALUES("10","10","1","2025-09-10");
INSERT INTO inscripciones VALUES("11","11","2","2025-09-10");
INSERT INTO inscripciones VALUES("12","12","2","2025-09-10");
INSERT INTO inscripciones VALUES("13","13","2","2025-09-10");
INSERT INTO inscripciones VALUES("14","14","2","2025-09-10");
INSERT INTO inscripciones VALUES("15","15","2","2025-09-10");
INSERT INTO inscripciones VALUES("16","16","2","2025-09-10");
INSERT INTO inscripciones VALUES("17","17","2","2025-09-10");
INSERT INTO inscripciones VALUES("18","18","2","2025-09-10");
INSERT INTO inscripciones VALUES("19","19","2","2025-09-10");
INSERT INTO inscripciones VALUES("20","20","2","2025-09-10");
INSERT INTO inscripciones VALUES("21","21","3","2025-09-10");
INSERT INTO inscripciones VALUES("22","22","3","2025-09-10");
INSERT INTO inscripciones VALUES("23","23","3","2025-09-10");
INSERT INTO inscripciones VALUES("24","24","3","2025-09-10");
INSERT INTO inscripciones VALUES("25","25","3","2025-09-10");
INSERT INTO inscripciones VALUES("26","26","4","2025-09-10");
INSERT INTO inscripciones VALUES("27","27","4","2025-09-10");
INSERT INTO inscripciones VALUES("28","28","4","2025-09-10");
INSERT INTO inscripciones VALUES("29","29","4","2025-09-10");
INSERT INTO inscripciones VALUES("30","30","4","2025-09-10");
INSERT INTO inscripciones VALUES("31","31","5","2025-09-10");
INSERT INTO inscripciones VALUES("32","32","5","2025-09-10");
INSERT INTO inscripciones VALUES("33","33","5","2025-09-10");
INSERT INTO inscripciones VALUES("34","34","5","2025-09-10");
INSERT INTO inscripciones VALUES("35","35","5","2025-09-10");
INSERT INTO inscripciones VALUES("36","36","5","2025-09-10");
INSERT INTO inscripciones VALUES("37","37","6","2025-09-10");
INSERT INTO inscripciones VALUES("38","38","6","2025-09-10");
INSERT INTO inscripciones VALUES("39","39","6","2025-09-10");
INSERT INTO inscripciones VALUES("40","40","6","2025-09-10");
INSERT INTO inscripciones VALUES("41","41","6","2025-09-10");
INSERT INTO inscripciones VALUES("42","42","6","2025-09-10");
INSERT INTO inscripciones VALUES("43","43","7","2025-09-10");
INSERT INTO inscripciones VALUES("44","44","7","2025-09-10");
INSERT INTO inscripciones VALUES("45","45","7","2025-09-10");
INSERT INTO inscripciones VALUES("46","46","7","2025-09-10");
INSERT INTO inscripciones VALUES("47","47","7","2025-09-10");
INSERT INTO inscripciones VALUES("48","48","7","2025-09-10");
INSERT INTO inscripciones VALUES("49","49","7","2025-09-10");
INSERT INTO inscripciones VALUES("50","50","7","2025-09-10");


DROP TABLE IF EXISTS `materias`;
CREATE TABLE `materias` (
  `idMateria` int(11) NOT NULL AUTO_INCREMENT,
  `nombreMateria` varchar(100) NOT NULL,
  `claveMateria` varchar(20) NOT NULL,
  `horasSemana` int(11) DEFAULT NULL,
  `idPeriodo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMateria`),
  UNIQUE KEY `claveMateria` (`claveMateria`),
  KEY `idPeriodo` (`idPeriodo`),
  CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`idPeriodo`) REFERENCES `periodosescolares` (`idPeriodo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO materias VALUES("1","Programación Web","PW2025","5","1");
INSERT INTO materias VALUES("2","Bases de Datos","BD2025","5","1");
INSERT INTO materias VALUES("3","Estructuras de Datos","ED2025","4","1");
INSERT INTO materias VALUES("4","Ingeniería de Software","IS2025","5","1");
INSERT INTO materias VALUES("5","Cálculo Diferencial","CD2025","4","1");
INSERT INTO materias VALUES("6","Física Aplicada","FA2025","4","1");
INSERT INTO materias VALUES("7","Administración Industrial","AI2025","4","1");
INSERT INTO materias VALUES("8","Simulación de Procesos","SP2025","4","1");
INSERT INTO materias VALUES("9","Estancia II","EST2025","6","1");


DROP TABLE IF EXISTS `periodosescolares`;
CREATE TABLE `periodosescolares` (
  `idPeriodo` int(11) NOT NULL AUTO_INCREMENT,
  `nombrePeriodo` varchar(50) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  PRIMARY KEY (`idPeriodo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO periodosescolares VALUES("1","Septiembre–Diciembre 2025","2025-09-04","2025-12-14");
INSERT INTO periodosescolares VALUES("2","Enero–Abril 2026","2026-01-10","2026-04-18");


DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `matricula` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apePaterno` varchar(50) NOT NULL,
  `apeMaterno` varchar(50) DEFAULT NULL,
  `sexo` enum('Masculino','Femenino') NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('Administrador','Docente','Alumno','Administrativo') NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `matricula` (`matricula`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios VALUES("1","ADM001","Sandra Elizabeth","León","Sosa","Femenino","1980-02-11","sandra.leon@upemor.edu.mx","$2y$10$7kv9EGuZN9WcT8RxmyRSV.kMeiAIOULra5E1JmvtLlajFWjJdt8jq","Administrador","1");
INSERT INTO usuarios VALUES("2","ADM002","Roberto Enrique","Lopez","Díaz","Masculino","1978-06-22","roberto.enrique@upemor.edu.mx","admin2025","Administrador","1");
INSERT INTO usuarios VALUES("3","ADMT001","Carmen","Vargas","Guzmán","Femenino","1985-03-12","carmen.vargas@upemor.edu.mx","$2y$10$eAmPRyj.ls6DyKkSixdzxeL/5XEjn/d.aUBVZ35ywG.ClKGOgQ1pi","Administrativo","1");
INSERT INTO usuarios VALUES("4","ADMT002","Elena","Torres","Soto","Femenino","1989-11-03","elena.torres@upemor.edu.mx","$2y$10$eb9ZsqOAkzVakPYm/uFF7OgEt9sKzbi0q7fJb3AbNbvonBBZyjU/G","Administrativo","1");
INSERT INTO usuarios VALUES("5","ADMT003","Ricardo","Molina","Pérez","Masculino","1983-05-18","ricardo.molina@upemor.edu.mx","admin03","Administrativo","1");
INSERT INTO usuarios VALUES("6","ADMT004","Gerardo","Acosta","Cruz","Masculino","1990-08-20","gerardo.acosta@upemor.edu.mx","admin04","Administrativo","1");
INSERT INTO usuarios VALUES("7","DOC001","José","Arriaga","Monroy","Masculino","1985-04-10","jose.arriaga@upemor.edu.mx","pass1","Docente","1");
INSERT INTO usuarios VALUES("8","DOC002","Erick","Méndez","Corona","Masculino","1990-02-18","erick.mendez@upemor.edu.mx","pass2","Docente","1");
INSERT INTO usuarios VALUES("9","DOC003","Mariana","Castillo","García","Femenino","1987-07-12","mariana.castillo@upemor.edu.mx","$2y$10$qtsfU2/8f3e0pB3mcAMzzu5ey1ZnguyJCpY5Qhz0wOA6KBBH7m42G","Docente","1");
INSERT INTO usuarios VALUES("10","DOC004","Carlos","Ramírez","López","Masculino","1982-01-15","carlos.ramirez@upemor.edu.mx","pass4","Docente","1");
INSERT INTO usuarios VALUES("11","DOC005","Sofía","Pineda","Rojas","Femenino","1991-03-23","sofia.pineda@upemor.edu.mx","pass5","Docente","1");
INSERT INTO usuarios VALUES("12","DOC006","Luis","Hernández","Santos","Masculino","1989-09-09","luis.hernandez@upemor.edu.mx","pass6","Docente","1");
INSERT INTO usuarios VALUES("13","DOC007","Andrea","Jiménez","Flores","Femenino","1988-12-04","andrea.jimenez@upemor.edu.mx","pass7","Docente","1");
INSERT INTO usuarios VALUES("14","DOC008","Miguel","Gómez","Reyes","Masculino","1984-06-14","miguel.gomez@upemor.edu.mx","$2y$10$39wfGqjI4D11TDJv2fV0ReFGGO2gYYXlFrrK2VxdvYn9YAqbehd.G","Docente","1");
INSERT INTO usuarios VALUES("15","DOC009","Patricia","Serrano","Luna","Femenino","1992-10-21","patricia.serrano@upemor.edu.mx","pass9","Docente","1");
INSERT INTO usuarios VALUES("16","DOC010","Alberto","Cordero","Mejía","Masculino","1986-11-29","alberto.cordero@upemor.edu.mx","pass10","Docente","1");
INSERT INTO usuarios VALUES("17","DOC011","Camila","Solórzano","Velázquez","Femenino","1991-06-14","camila.solorzano@upemor.edu.mx","$2y$10$lNV/BdBIUB1P1sRoAOSY1OBCr7.gqI0TiCCKvykJnGdlsDvN8rYHS","Docente","1");
INSERT INTO usuarios VALUES("18","ALU001","Santiago","Ramírez","López","Masculino","2003-05-12","santiago.ramirez@upemor.edu.mx","pass01","Alumno","1");
INSERT INTO usuarios VALUES("19","ALU002","Valeria","Martínez","Gómez","Femenino","2004-03-22","valeria.martinez@upemor.edu.mx","pass02","Alumno","1");
INSERT INTO usuarios VALUES("20","ALU003","Luis Ángel","Torres","Sánchez","Masculino","2003-08-15","luis.torres@upemor.edu.mx","pass03","Alumno","1");
INSERT INTO usuarios VALUES("21","ALU004","Ximena","Castillo","Rojas","Femenino","2004-01-11","ximena.castillo@upemor.edu.mx","pass04","Alumno","1");
INSERT INTO usuarios VALUES("22","ALU005","Alexis","Mendoza","Flores","Masculino","2003-07-02","alexis.mendoza@upemor.edu.mx","$2y$10$Cl6BroBzMD72QmlapmmgCOs1/PNnhfVNj58NEcyeSxiNa42gLftJW","Alumno","1");
INSERT INTO usuarios VALUES("23","ALU006","Regina","García","Soto","Femenino","2004-10-10","regina.garcia@upemor.edu.mx","$2y$10$OxIAWO/mjogn6F/1z8LacuzWghhL1qYCvVTGoUVCUcBlLb6EZ.3CS","Alumno","1");
INSERT INTO usuarios VALUES("24","ALU007","Diego","Hernández","Pérez","Masculino","2003-12-09","diego.hernandez@upemor.edu.mx","pass07","Alumno","1");
INSERT INTO usuarios VALUES("25","ALU008","María José","Ortega","Serrano","Femenino","2004-05-28","mariaj.ortega@upemor.edu.mx","pass08","Alumno","1");
INSERT INTO usuarios VALUES("26","ALU009","Fernando","Gómez","Acosta","Masculino","2003-09-03","fernando.gomez@upemor.edu.mx","pass09","Alumno","1");
INSERT INTO usuarios VALUES("27","ALU010","Ana Sofía","Navarro","Vega","Femenino","2004-04-14","anasofia.navarro@upemor.edu.mx","pass10","Alumno","1");
INSERT INTO usuarios VALUES("28","ALU011","Andrés","Santos","Castro","Masculino","2003-11-11","andres.santos@upemor.edu.mx","pass11","Alumno","1");
INSERT INTO usuarios VALUES("29","ALU012","Camila","Pineda","Duarte","Femenino","2004-06-07","camila.pineda@upemor.edu.mx","pass12","Alumno","1");
INSERT INTO usuarios VALUES("30","ALU013","Jorge","Flores","Molina","Masculino","2003-07-21","jorge.flores@upemor.edu.mx","pass13","Alumno","1");
INSERT INTO usuarios VALUES("31","ALU014","Natalia","Silva","Ramos","Femenino","2004-12-10","natalia.silva@upemor.edu.mx","pass14","Alumno","1");
INSERT INTO usuarios VALUES("32","ALU015","Ricardo","Reyes","Correa","Masculino","2003-03-26","ricardo.reyes@upemor.edu.mx","pass15","Alumno","1");
INSERT INTO usuarios VALUES("33","ALU016","Danna","Núñez","Solís","Femenino","2004-02-17","danna.nunez@upemor.edu.mx","pass16","Alumno","1");
INSERT INTO usuarios VALUES("34","ALU017","Oscar","Vargas","Pérez","Masculino","2003-10-30","oscar.vargas@upemor.edu.mx","pass17","Alumno","1");
INSERT INTO usuarios VALUES("35","ALU018","Montserrat","Juárez","Linares","Femenino","2004-09-05","montserrat.juarez@upemor.edu.mx","pass18","Alumno","1");
INSERT INTO usuarios VALUES("36","ALU019","Iván","Carmona","Lara","Masculino","2003-02-14","ivan.carmona@upemor.edu.mx","pass19","Alumno","1");
INSERT INTO usuarios VALUES("37","ALU020","Paola","Salazar","Paz","Femenino","2004-07-19","paola.salazar@upemor.edu.mx","pass20","Alumno","1");
INSERT INTO usuarios VALUES("38","ALU021","Marco","Fuentes","Toledo","Masculino","2003-08-22","marco.fuentes@upemor.edu.mx","pass21","Alumno","1");
INSERT INTO usuarios VALUES("39","ALU022","Carolina","Rosales","Ibarra","Femenino","2004-11-03","carolina.rosales@upemor.edu.mx","pass22","Alumno","1");
INSERT INTO usuarios VALUES("40","ALU023","Adrián","Pérez","González","Masculino","2003-04-05","adrian.perez@upemor.edu.mx","$2y$10$boHhz1APVmZ6Q8PnGI7NJO74FY.yyTt1tqinSVREYZ69s7a0FZHh6","Alumno","1");
INSERT INTO usuarios VALUES("41","ALU024","Itzel","Prieto","Sosa","Femenino","2004-01-28","itzel.prieto@upemor.edu.mx","pass24","Alumno","1");
INSERT INTO usuarios VALUES("42","ALU025","Eduardo","López","Castañeda","Masculino","2003-12-18","eduardo.lopez@upemor.edu.mx","pass25","Alumno","1");
INSERT INTO usuarios VALUES("43","ALU026","Samantha","Requena","Trejo","Femenino","2004-02-24","samantha.requena@upemor.edu.mx","pass26","Alumno","1");
INSERT INTO usuarios VALUES("44","ALU027","Brandon","Campos","Valdez","Masculino","2003-09-09","brandon.campos@upemor.edu.mx","$2y$10$VXvLXJFNUKyR5WEGyOz8NOi62lVddbpLB8sEA54c6D5K81i74Q1k2","Alumno","1");
INSERT INTO usuarios VALUES("45","ALU028","Ariana","Sánchez","Guerrero","Femenino","2004-06-11","ariana.sanchez@upemor.edu.mx","pass28","Alumno","1");
INSERT INTO usuarios VALUES("46","ALU029","Héctor","Ceballos","Mena","Masculino","2003-10-16","hector.ceballos@upemor.edu.mx","$2y$10$LGHzRSxO.n.9g/zOV0/hzOlo7XTY/JhfJurkDi66tJU/hE9vDBF8q","Alumno","1");
INSERT INTO usuarios VALUES("47","ALU030","Julieta","Morales","Quiroz","Femenino","2004-05-09","julieta.morales@upemor.edu.mx","pass30","Alumno","1");
INSERT INTO usuarios VALUES("48","ALU031","Emiliano","Vera","Loyola","Masculino","2003-01-17","emiliano.vera@upemor.edu.mx","pass31","Alumno","1");
INSERT INTO usuarios VALUES("49","ALU032","Zoe","Tejeda","Aguilar","Femenino","2004-03-01","zoe.tejeda@upemor.edu.mx","pass32","Alumno","1");
INSERT INTO usuarios VALUES("50","ALU033","Rodrigo","Sierra","Blanco","Masculino","2003-07-26","rodrigo.sierra@upemor.edu.mx","pass33","Alumno","1");
INSERT INTO usuarios VALUES("51","ALU034","Aylin","Castaño","Salinas","Femenino","2004-12-01","aylin.castano@upemor.edu.mx","pass34","Alumno","1");
INSERT INTO usuarios VALUES("52","ALU035","Jonathan","Mejía","Rubio","Masculino","2003-04-14","jonathan.mejia@upemor.edu.mx","pass35","Alumno","1");
INSERT INTO usuarios VALUES("53","ALU036","Daniela","Zamora","Rivas","Femenino","2004-08-08","daniela.zamora@upemor.edu.mx","pass36","Alumno","1");
INSERT INTO usuarios VALUES("54","ALU037","Pablo","Solano","Ortega","Masculino","2003-12-30","pablo.solano@upemor.edu.mx","pass37","Alumno","1");
INSERT INTO usuarios VALUES("55","ALU038","Victoria","Galindo","Pérez","Femenino","2004-10-02","victoria.galindo@upemor.edu.mx","pass38","Alumno","1");
INSERT INTO usuarios VALUES("56","ALU039","Mauricio","Roldán","Campos","Masculino","2003-03-11","mauricio.roldan@upemor.edu.mx","pass39","Alumno","1");
INSERT INTO usuarios VALUES("57","ALU040","Nicole","Benítez","Serrano","Femenino","2004-06-18","nicole.benitez@upemor.edu.mx","pass40","Alumno","1");
INSERT INTO usuarios VALUES("58","ALU041","Alan","Arellano","Vidal","Masculino","2003-11-01","alan.arellano@upemor.edu.mx","pass41","Alumno","1");
INSERT INTO usuarios VALUES("59","ALU042","Elena","Zúñiga","Cruz","Femenino","2004-04-23","elena.zuniga@upemor.edu.mx","pass42","Alumno","1");
INSERT INTO usuarios VALUES("60","ALU043","Sebastián","Peña","García","Masculino","2003-09-20","sebastian.pena@upemor.edu.mx","pass43","Alumno","1");
INSERT INTO usuarios VALUES("61","ALU044","Dafne","Lozano","Jiménez","Femenino","2004-02-19","dafne.lozano@upemor.edu.mx","pass44","Alumno","1");
INSERT INTO usuarios VALUES("62","ALU045","Óscar","Valle","Ruiz","Masculino","2003-07-07","oscar.valle@upemor.edu.mx","pass45","Alumno","1");
INSERT INTO usuarios VALUES("63","ALU046","Renata","Quiñones","Haro","Femenino","2004-11-18","renata.quinones@upemor.edu.mx","pass46","Alumno","1");
INSERT INTO usuarios VALUES("64","ALU047","Gael","Ramos","Rosales","Masculino","2003-02-27","gael.ramos@upemor.edu.mx","pass47","Alumno","1");
INSERT INTO usuarios VALUES("65","ALU048","Miranda","Esquivel","Pineda","Femenino","2004-08-14","miranda.esquivel@upemor.edu.mx","pass48","Alumno","1");
INSERT INTO usuarios VALUES("66","ALU049","Leonardo","Barrios","Muñoz","Masculino","2003-05-25","leonardo.barrios@upemor.edu.mx","pass49","Alumno","1");
INSERT INTO usuarios VALUES("67","ALU050","Abril","Delgado","Rey","Femenino","2004-03-30","abril.delgado@upemor.edu.mx","pass50","Alumno","1");



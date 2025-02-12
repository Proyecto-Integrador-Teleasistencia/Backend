DROP DATABASE IF EXISTS bdintegrador;
CREATE DATABASE bdintegrador;
USE bdintegrador;
	
	CREATE TABLE zonas (
		id int AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(100)
	);
	
	CREATE TABLE pacientes (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    nombre VARCHAR(100) NOT NULL,
	    fecha_nacimiento DATE,
	    direccion TEXT,
	    ciudad TEXT,
	    codigo_postal TEXT,
	    dni VARCHAR(20) UNIQUE NOT NULL,
	    tarjeta_sanitaria VARCHAR(50) UNIQUE NOT NULL,
	    telefono VARCHAR(20),
	    email VARCHAR(100),
	    zona_id INT,
	    situacion_personal TEXT,
	    situacion_sanitaria TEXT,
	    situacion_habitacional TEXT,
	    autonomia_personal TEXT,
	    situacion_economica TEXT,
	    FOREIGN KEY (zona_id) REFERENCES zonas(id)
	);
	
	CREATE TABLE contactos (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    paciente_id INT,
	    nombre VARCHAR(100) NOT NULL,
	    apellidos VARCHAR(100) NOT NULL,
	    telefono VARCHAR(20) NOT NULL,
	    relacion VARCHAR(50),
	    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
	);
	
	CREATE TABLE teleoperadores (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    nombre VARCHAR(100) NOT NULL,
	    email VARCHAR(100) UNIQUE NOT NULL,
	    telefono VARCHAR(20),
	    rol ENUM('administrador', 'usuario') NOT NULL,
	    password_hash VARCHAR(255) NOT NULL,
	    fecha_contratacion DATE,
	    fecha_baja DATE NULL,
	    zona_id INT,
	    FOREIGN KEY (zona_id) REFERENCES zonas(id)
	);
	
	CREATE TABLE zonas_gestion (
		id int AUTO_INCREMENT PRIMARY KEY,
		zona_id INT,
		teleoperador_id INT,
		FOREIGN KEY (zona_id) REFERENCES zonas(id) ON DELETE CASCADE,
		FOREIGN KEY (teleoperador_id) REFERENCES teleoperadores(id) ON DELETE CASCADE
	);
	
	CREATE TABLE categorias (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    nombre VARCHAR(100) UNIQUE NOT NULL
	);
	
	
	CREATE TABLE subcategorias (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    nombre VARCHAR(100) UNIQUE NOT NULL,
	    categoria_id INT,
	    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
	);
	
CREATE TABLE avisos (
		id int AUTO_INCREMENT PRIMARY KEY,
		periocidad ENUM ('puntual', 'periódico'),
		fecha_hora DATETIME,
		categoria_id int, 
		FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE 
	);

	CREATE TABLE llamadas (
		id int AUTO_INCREMENT PRIMARY KEY,
		fecha_hora DATETIME,
		descripcion text,
		tipo enum('saliente', 'entrante'),
		planificada boolean,
		teleoperador_id int,
		paciente_id int,
		categoria_id int,
		aviso_id int,
		FOREIGN KEY (teleoperador_id) REFERENCES teleoperadores(id) ON DELETE CASCADE,
		FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
		FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
		FOREIGN KEY (aviso_id) REFERENCES avisos(id) ON DELETE CASCADE
		-- Avisos solo si es llamada saliente
	);
	
-- Insertar Categorías
INSERT INTO categorias (nombre) VALUES 
('Avisos'),
('Seguimiento según protocolos'),
('Agendas de ausencia domiciliaria y retorno'),
('Atención de emergencias'),
('Comunicaciones no urgentes');

-- Insertar Subcategorías asociadas a cada categoría
INSERT INTO subcategorias (nombre, categoria_id) VALUES 
('Avisos de medicación', 1),
('Avisos especiales o por alerta', 1),
('Seguimiento después de emergencias', 2),
('Seguimiento por procesos de duelo', 2),
('Seguimiento por altas hospitalarias', 2),
('Suspensión temporal del servicio', 3),
('Retornos o fin de la ausencia', 3),
('Emergencias sociales', 4),
('Emergencias sanitarias', 4),
('Crisis de soledad o angustia', 4),
('Alarma sin respuesta', 4),
('Notificar ausencias o retornos', 5),
('Modificar datos personales', 5),
('Llamadas accidentales', 5),
('Petición de información', 5),
('Formulación de sugerencias, quejas o reclamaciones', 5),
('Llamadas sociales (para saludar o hablar con el personal)', 5),
('Registrar citas médicas tras una llamada', 5),
('Otros tipos de llamadas', 5);





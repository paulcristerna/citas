-- Creación de la base de datos.

CREATE DATABASE citasUAS;

USE citasUAS;

-- Creación de la tabla de usuarios.

CREATE TABLE usuarios (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- Clave primaria.
	nombre VARCHAR(25) NOT NULL, -- Nombre del usuario.
	apellidoPaterno VARCHAR(25) NOT NULL, -- Apellido paterno del usuario.
	apellidoMaterno VARCHAR(25), -- Apellido materno del usuario.
	email VARCHAR(50) NOT NULL, -- Email del usuario.
	nombreProyecto VARCHAR(50), -- Nombre del proyecto del usuario.
	password VARCHAR(50) NOT NULL, -- Contraseña del usuario.
	tipo CHAR(1) DEFAULT 1, -- Tipo de usuario: 1: Usuario normal, 2: Administrador.
	registro DATETIME DEFAULT CURRENT_TIMESTAMP -- Fecha de registro.
);

-- Creación de la tabla de citas.

CREATE TABLE citas 
(
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- Clave primaria.
	usuario INT(11) UNSIGNED NOT NULL, -- Usuario que reliaza la cita.
	fecha DATETIME NOT NULL, -- Fecha de la cita.
	confirmacion CHAR(1) DEFAULT 1, -- Confirmación de la cita: 0: Rechazada, 1: Sin confirmar, 2: Confirmada.
	registro DATETIME DEFAULT CURRENT_TIMESTAMP, -- Fecha de registro.
	FOREIGN KEY (usuario) -- Clave foranea que relaciona las citas con los usuarios.
    REFERENCES usuarios(id)
    ON DELETE CASCADE
);
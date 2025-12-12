/*base de datos Gustavo Rojas*/
CREATE DATABASE IF NOT EXISTS transporte_app;
USE transporte_app;

/*TABLA DE USUARIOS*/
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol TINYINT NOT NULL COMMENT '1: Dueño, 2: Chofer',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

/*TABLA DE BUSES*/
CREATE TABLE IF NOT EXISTS buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(20) NOT NULL UNIQUE,
    nombre_bus VARCHAR(50) NOT NULL,
    capacidad INT DEFAULT 0,
    chofer_id INT NULL,
    FOREIGN KEY (chofer_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

/*TABLA DE ALUMNOS*/
CREATE TABLE IF NOT EXISTS alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    grado VARCHAR(50) NOT NULL,
    institucion VARCHAR(100) NOT NULL,
    codigo_qr VARCHAR(100) NOT NULL UNIQUE,
    foto VARCHAR(255) NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

/*TABLA DE ASISTENCIAS (El registro histórico)*/
CREATE TABLE IF NOT EXISTS asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    bus_id INT NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo ENUM('ENTRADA', 'SALIDA') NOT NULL,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE
);

/* --- DATOS DE EJEMPLO PARA QUE NO ESTÉ VACÍO --- */

/* Creamos un usuario DUEÑO por defecto (Pass: 12345) */
/* Nota: En el futuro encriptaremos la pass, por ahora va en texto plano para probar */
INSERT INTO usuarios (nombre_completo, usuario, password, rol) 
VALUES ('Administrador Principal', 'admin', 'tavo.2025', 1);

/* Creamos un CHOFER de prueba */
INSERT INTO usuarios (nombre_completo, usuario, password, rol) 
VALUES ('Juan Chofer', 'juan', '12345', 2);

/*Actualizacion de consulta, se agregara para crear mas buses, asignar un chofer y poder activarlos y desactivarlos*/
/* Agregamos teléfono y bus al ALUMNO */
transporte_app
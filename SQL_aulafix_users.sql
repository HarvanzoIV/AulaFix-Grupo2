-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS aulafix
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Seleccionar la base de datos
USE aulafix;

-- Crear tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    usuario VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE incidencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    problema VARCHAR(100) NOT NULL,
    ubicacion VARCHAR(100) NOT NULL,
    prioridad VARCHAR(20) NOT NULL,
    situacion VARCHAR(20) NOT NULL
);



CREATE TABLE historial_incidencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incidencia_id INT NOT NULL,
    campo_modificado VARCHAR(50) NOT NULL,
    valor_anterior VARCHAR(255),
    valor_nuevo VARCHAR(255),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_historial_incidencia
        FOREIGN KEY (incidencia_id)
        REFERENCES incidencias(id)
        ON DELETE CASCADE
);
CREATE DATABASE IF NOT EXISTS getinweb;
USE getinweb;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS plantillas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    categoria VARCHAR(60) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen_preview VARCHAR(255) NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS archivos_plantilla (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_plantilla INT NOT NULL,
    nombre_original VARCHAR(180) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    extension VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_plantilla) REFERENCES plantillas(id) ON DELETE CASCADE
);

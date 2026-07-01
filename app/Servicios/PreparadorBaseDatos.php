<?php

namespace App\Servicios;

use mysqli;

class PreparadorBaseDatos
{
    private static bool $done = false;

    private static function safe(mysqli $conexion, string $sql): void
    {
        try {
            $conexion->query($sql);
        } catch (\Throwable $e) {
            // Si ya existe la columna/índice o el hosting no permite repetirlo, no cortamos la página.
        }
    }

    public static function preparar(mysqli $conexion): void
    {
        if (self::$done) return;
        self::$done = true;

        self::safe($conexion, "CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rol VARCHAR(30) NOT NULL DEFAULT 'usuario',
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        self::safe($conexion, "ALTER TABLE usuarios ADD COLUMN rol VARCHAR(30) NOT NULL DEFAULT 'usuario'");

        self::safe($conexion, "CREATE TABLE IF NOT EXISTS imagenes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            clave VARCHAR(120) UNIQUE NULL,
            nombre VARCHAR(180) NULL,
            mime VARCHAR(120) NULL,
            tipo VARCHAR(20) NOT NULL DEFAULT 'url',
            contenido LONGBLOB NULL,
            url TEXT NULL,
            nombre_original VARCHAR(180) NULL,
            tipo_mime VARCHAR(120) NULL,
            datos LONGBLOB NULL,
            ruta_archivo VARCHAR(1000) NULL,
            fecha_carga TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        foreach ([
            "ALTER TABLE imagenes ADD COLUMN clave VARCHAR(120) UNIQUE NULL",
            "ALTER TABLE imagenes ADD COLUMN nombre VARCHAR(180) NULL",
            "ALTER TABLE imagenes ADD COLUMN mime VARCHAR(120) NULL",
            "ALTER TABLE imagenes ADD COLUMN tipo VARCHAR(20) NOT NULL DEFAULT 'url'",
            "ALTER TABLE imagenes ADD COLUMN contenido LONGBLOB NULL",
            "ALTER TABLE imagenes ADD COLUMN url TEXT NULL",
            "ALTER TABLE imagenes ADD COLUMN nombre_original VARCHAR(180) NULL",
            "ALTER TABLE imagenes ADD COLUMN tipo_mime VARCHAR(120) NULL",
            "ALTER TABLE imagenes ADD COLUMN datos LONGBLOB NULL",
            "ALTER TABLE imagenes ADD COLUMN ruta_archivo VARCHAR(1000) NULL",
            "ALTER TABLE imagenes MODIFY ruta_archivo VARCHAR(1000) NULL",
            "ALTER TABLE imagenes ADD UNIQUE KEY clave_unica (clave)"
        ] as $sql) self::safe($conexion, $sql);

        self::safe($conexion, "CREATE TABLE IF NOT EXISTS plantillas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT NOT NULL,
            nombre VARCHAR(120) NOT NULL,
            categoria VARCHAR(60) NOT NULL,
            descripcion TEXT NOT NULL,
            imagen_preview VARCHAR(255) NULL,
            id_imagen_preview INT NULL,
            carpeta VARCHAR(255) NULL,
            archivo_principal VARCHAR(255) NULL,
            archivo_inicio VARCHAR(255) NULL,
            fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        foreach ([
            "ALTER TABLE plantillas ADD COLUMN imagen_preview VARCHAR(255) NULL",
            "ALTER TABLE plantillas ADD COLUMN id_imagen_preview INT NULL",
            "ALTER TABLE plantillas ADD COLUMN carpeta VARCHAR(255) NULL",
            "ALTER TABLE plantillas ADD COLUMN archivo_principal VARCHAR(255) NULL",
            "ALTER TABLE plantillas ADD COLUMN archivo_inicio VARCHAR(255) NULL"
        ] as $sql) self::safe($conexion, $sql);

        self::safe($conexion, "CREATE TABLE IF NOT EXISTS archivos_plantilla (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_plantilla INT NOT NULL,
            nombre_original VARCHAR(180) NOT NULL,
            ruta_archivo VARCHAR(255) NOT NULL,
            extension VARCHAR(20) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Admin de prueba. Si ya existe, no pisa tus datos.
        $emailAdmin = 'admin@getinweb.com';
        $check = $conexion->prepare('SELECT id FROM usuarios WHERE email = ?');
        $check->bind_param('s', $emailAdmin);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows === 0) {
            $nombre = 'Administrador';
            $password = password_hash('Admin1234', PASSWORD_DEFAULT);
            $rol = 'admin';
            $insert = $conexion->prepare('INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)');
            $insert->bind_param('ssss', $nombre, $emailAdmin, $password, $rol);
            $insert->execute();
        }
    }
}
?>

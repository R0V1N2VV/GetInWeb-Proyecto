<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    DAO de imágenes.
    Registra y busca imágenes guardadas por ruta, URL o archivo local.
*/

namespace App\DAO;

use mysqli;

class ImagenDAO
{
    // Cada DAO recibe la conexión. No la crea, solamente la usa.
    public function __construct(private mysqli $conexion) {}

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT * FROM imagenes WHERE id = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('i', $id);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows === 1 ? $resultado->fetch_assoc() : null;
    }

    public function buscarPorClave(string $clave): ?array
    {
        $sql = 'SELECT * FROM imagenes WHERE clave = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('s', $clave);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows === 1 ? $resultado->fetch_assoc() : null;
    }

    public function guardarOActualizarRuta(string $clave, string $ruta, string $nombre, string $mime): bool
    {
        $tipo = 'url';
        $contenido = null;
        $datos = null;
        $url = $ruta;
        $rutaArchivo = $ruta;

        $sql = 'INSERT INTO imagenes
                (clave, nombre, mime, tipo, contenido, url, nombre_original, tipo_mime, datos, ruta_archivo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    nombre = VALUES(nombre),
                    mime = VALUES(mime),
                    tipo = VALUES(tipo),
                    contenido = NULL,
                    url = VALUES(url),
                    nombre_original = VALUES(nombre_original),
                    tipo_mime = VALUES(tipo_mime),
                    datos = NULL,
                    ruta_archivo = VALUES(ruta_archivo)';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            'ssssssssss',
            $clave,
            $nombre,
            $mime,
            $tipo,
            $contenido,
            $url,
            $nombre,
            $mime,
            $datos,
            $rutaArchivo
        );

        return // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();
    }

    public function insertarPreview(string $clave, string $nombre, string $mime, string $ruta): int
    {
        $tipo = 'url';
        $contenido = null;
        $datos = null;
        $url = $ruta;
        $rutaArchivo = $ruta;

        $sql = 'INSERT INTO imagenes
                (clave, nombre, mime, tipo, contenido, url, nombre_original, tipo_mime, datos, ruta_archivo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            'ssssssssss',
            $clave,
            $nombre,
            $mime,
            $tipo,
            $contenido,
            $url,
            $nombre,
            $mime,
            $datos,
            $rutaArchivo
        );
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        // insert_id devuelve el ID autoincremental que generó MySQL.
        return $this->conexion->insert_id;
    }

    public function borrarPorId(int $id): void
    {
        $sql = 'DELETE FROM imagenes WHERE id = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('i', $id);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();
    }
}

<?php

namespace App\DAO;

use mysqli;

class PlantillaDAO
{
    // Cada DAO recibe la conexión. No la crea, solamente la usa.
    public function __construct(private mysqli $conexion) {}

    public function buscar(string $busqueda = '', string $categoria = 'todas'): array
    {
        $sql = 'SELECT plantillas.*, usuarios.nombre AS nombre_usuario
                FROM plantillas
                INNER JOIN usuarios ON plantillas.id_usuario = usuarios.id
                WHERE 1';

        $parametros = [];
        $tipos = '';

        if ($busqueda !== '') {
            $sql .= ' AND (plantillas.nombre LIKE ? OR plantillas.descripcion LIKE ?)';

            $textoBuscado = '%' . $busqueda . '%';
            $parametros[] = $textoBuscado;
            $parametros[] = $textoBuscado;
            $tipos .= 'ss';
        }

        if ($categoria !== 'todas') {
            $sql .= ' AND plantillas.categoria = ?';

            $parametros[] = $categoria;
            $tipos .= 's';
        }

        $sql .= ' ORDER BY plantillas.fecha_subida DESC';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        if (!empty($parametros)) {
            $stmt->bind_param($tipos, ...$parametros);
        }

        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorIdConUsuario(int $id): ?array
    {
        $sql = 'SELECT plantillas.*, usuarios.nombre AS nombre_usuario
                FROM plantillas
                INNER JOIN usuarios ON plantillas.id_usuario = usuarios.id
                WHERE plantillas.id = ?';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows === 1 ? $resultado->fetch_assoc() : null;
    }

    public function crear(
        int $usuarioId,
        string $nombre,
        string $categoria,
        string $descripcion,
        int $idImagenPreview
    ): int {
        $imagenPreview = '';

        $sql = 'INSERT INTO plantillas
                (id_usuario, nombre, categoria, descripcion, imagen_preview, id_imagen_preview, carpeta, archivo_principal, archivo_inicio)
                VALUES (?, ?, ?, ?, ?, ?, NULL, NULL, NULL)';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('issssi', $usuarioId, $nombre, $categoria, $descripcion, $imagenPreview, $idImagenPreview);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        // insert_id devuelve el ID autoincremental que generó MySQL.
        return $this->conexion->insert_id;
    }

    public function actualizarAlmacenamiento(int $id, string $carpeta, ?string $archivoPrincipal): void
    {
        $sql = 'UPDATE plantillas
                SET carpeta = ?, archivo_principal = ?, archivo_inicio = ?
                WHERE id = ?';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('sssi', $carpeta, $archivoPrincipal, $archivoPrincipal, $id);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();
    }

    public function borrar(int $id): void
    {
        $sql = 'DELETE FROM plantillas WHERE id = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('i', $id);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();
    }
}

<?php


namespace App\DAO;

use mysqli;

class ArchivoPlantillaDAO
{
    // Cada DAO recibe la conexión. No la crea, solamente la usa.
    public function __construct(private mysqli $conexion) {}

    public function agregar(int $idPlantilla, string $nombreOriginal, string $rutaArchivo, string $extension): int
    {
        $sql = 'INSERT INTO archivos_plantilla (id_plantilla, nombre_original, ruta_archivo, extension)
                VALUES (?, ?, ?, ?)';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('isss', $idPlantilla, $nombreOriginal, $rutaArchivo, $extension);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        // insert_id devuelve el ID autoincremental que generó MySQL.
        return $this->conexion->insert_id;
    }

    public function buscarPorPlantilla(int $idPlantilla): array
    {
        $sql = 'SELECT * FROM archivos_plantilla
                WHERE id_plantilla = ?
                ORDER BY nombre_original ASC';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idPlantilla);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorIdYPlantilla(int $idArchivo, int $idPlantilla): ?array
    {
        $sql = 'SELECT * FROM archivos_plantilla
                WHERE id = ? AND id_plantilla = ?';

        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ii', $idArchivo, $idPlantilla);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows === 1 ? $resultado->fetch_assoc() : null;
    }

    public function borrarPorPlantilla(int $idPlantilla): void
    {
        $sql = 'DELETE FROM archivos_plantilla WHERE id_plantilla = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('i', $idPlantilla);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();
    }
}

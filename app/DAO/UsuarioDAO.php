<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    DAO de usuarios.
    Toda consulta SQL relacionada con la tabla usuarios se deja en esta clase.
*/

namespace App\DAO;

use mysqli;

class UsuarioDAO
{
    // Cada DAO recibe la conexión. No la crea, solamente la usa.
    public function __construct(private mysqli $conexion) {}

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT * FROM usuarios WHERE id = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('i', $id);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows === 1 ? $resultado->fetch_assoc() : null;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM usuarios WHERE email = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('s', $email);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->num_rows === 1 ? $resultado->fetch_assoc() : null;
    }

    public function crear(string $nombre, string $email, string $passwordHash, string $rol = 'usuario'): int
    {
        $sql = 'INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('ssss', $nombre, $email, $passwordHash, $rol);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        // insert_id devuelve el ID autoincremental que generó MySQL.
        return $this->conexion->insert_id;
    }

    public function contarPlantillas(int $usuarioId): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM plantillas WHERE id_usuario = ?';
        // prepare() arma una consulta segura antes de cargar los valores.
        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param('i', $usuarioId);
        // execute() ejecuta finalmente la consulta en MySQL.
        $stmt->execute();

        $fila = $stmt->get_result()->fetch_assoc();

        return intval($fila['total'] ?? 0);
    }
}

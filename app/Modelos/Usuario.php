<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    Modelo Usuario.
    Representa los datos principales de un usuario dentro del sistema.
*/

namespace App\Modelos;

class Usuario
{
    public function __construct(
        public int $id,
        public string $nombre,
        public string $email,
        public string $rol = 'usuario'
    ) {}

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }
}
?>

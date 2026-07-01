<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    Modelo Plantilla.
    Representa los datos principales de una plantilla subida por un usuario.
*/

namespace App\Modelos;

class Plantilla
{
    public function __construct(
        public int $id,
        public int $idUsuario,
        public string $nombre,
        public string $categoria,
        public string $descripcion,
        public ?int $idImagenPreview = null,
        public ?string $carpeta = null,
        public ?string $archivoPrincipal = null,
        public ?string $nombreUsuario = null
    ) {}

    public function perteneceA(int $usuarioId): bool
    {
        return $this->idUsuario === $usuarioId;
    }
}
?>

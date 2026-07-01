<?php

namespace App\Servicios;

class ServicioArchivos
{
    public static function nombreSeguro(string $nombre): string
    {
        $nombre = basename($nombre);

        return preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombre);
    }

    public static function asegurarCarpeta(string $ruta): void
    {
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
    }

    public static function borrarArchivoSiEstaEnUploads(?string $ruta): void
    {
        $rutaReal = self::rutaSeguraEnUploads($ruta);

        if ($rutaReal && is_file($rutaReal)) {
            unlink($rutaReal);
        }
    }

    public static function borrarCarpetaSiEstaEnUploads(?string $ruta): void
    {
        $rutaReal = self::rutaSeguraEnUploads($ruta);

        if (!$rutaReal || !is_dir($rutaReal)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rutaReal, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            $item->isDir()
                ? rmdir($item->getPathname())
                : unlink($item->getPathname());
        }

        rmdir($rutaReal);
    }

    private static function rutaSeguraEnUploads(?string $ruta): string|false
    {
        if (!$ruta) {
            return false;
        }

        $ruta = str_replace('\\', '/', $ruta);

        if (str_starts_with($ruta, 'http://') || str_starts_with($ruta, 'https://')) {
            return false;
        }

        $baseUploads = realpath(__DIR__ . '/../../uploads');
        $rutaReal = realpath(__DIR__ . '/../../' . $ruta);

        if (!$baseUploads || !$rutaReal) {
            return false;
        }

        return str_starts_with($rutaReal, $baseUploads) ? $rutaReal : false;
    }
}

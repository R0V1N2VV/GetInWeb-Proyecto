<?php


require_once "conexion.php";

use App\DAO\ImagenDAO;
use App\Servicios\ServicioRegistroImagenes;


// 1. Buscar la imagen por ID o por clave


// Usamos el DAO para buscar el registro de la imagen en la base.
$imagenDAO = new ImagenDAO($conexion);
$imagen = null;

if (isset($_GET['id'])) {
    $imagen = $imagenDAO->buscarPorId(intval($_GET['id']));
}

if ($imagen === null && isset($_GET['clave'])) {
    $clave = $_GET['clave'];
    $imagen = $imagenDAO->buscarPorClave($clave);

    // Si todavía no está registrada, se intenta registrar automáticamente.
    if ($imagen === null) {
        $registroImagenes = new ServicioRegistroImagenes($conexion);
        $registroImagenes->registrarImagenesDelSitio();

        $imagen = $imagenDAO->buscarPorClave($clave);
    }
}

if ($imagen === null) {
    http_response_code(404);
    exit('Imagen no encontrada en la base de datos.');
}


// 2. Preparar datos posibles de imagen


$mime = $imagen['tipo_mime'] ?? $imagen['mime'] ?? 'image/*';
$datos = $imagen['datos'] ?? null;
$contenido = $imagen['contenido'] ?? null;
$ruta = $imagen['ruta_archivo'] ?? '';
$url = $imagen['url'] ?? '';


// 3. Si la imagen está guardada como BLOB, se imprime directo

if (!empty($datos)) {
    header('Content-Type: ' . $mime);
    echo $datos;
    exit();
}

if (!empty($contenido)) {
    header('Content-Type: ' . $mime);
    echo $contenido;
    exit();
}


// 4. Si la imagen está guardada como ruta o URL


$origen = !empty($ruta) ? $ruta : $url;

if ($origen === '') {
    http_response_code(404);
    exit('La imagen existe en la base, pero no tiene datos ni ruta.');
}

if (str_starts_with($origen, 'http://') || str_starts_with($origen, 'https://')) {
    header('Location: ' . $origen);
    exit();
}

$origen = str_replace('\\', '/', $origen);
$rutaCompleta = __DIR__ . '/' . $origen;

if (!file_exists($rutaCompleta)) {
    http_response_code(404);
    exit('El archivo está registrado en la base, pero no existe en la carpeta: ' . $origen);
}

header('Content-Type: ' . $mime);
readfile($rutaCompleta);
exit();

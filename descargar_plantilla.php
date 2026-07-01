<?php


require_once "conexion.php";

use App\DAO\ArchivoPlantillaDAO;
use App\DAO\PlantillaDAO;
use App\Servicios\ServicioAutenticacion;


// 1. Verificar sesión y validar ID


$auth = new ServicioAutenticacion($conexion);
$auth->requireLogin();

if (!isset($_GET['id'])) {
    header('Location: explorador.php');
    exit();
}


// 2. Verificar que ZipArchive esté disponible


// ZipArchive es la extensión de PHP necesaria para crear archivos .zip.
if (!class_exists('ZipArchive')) {
    die('.');
}


// 3. Buscar la plantilla y sus archivos


$idPlantilla = intval($_GET['id']);

$plantillaDAO = new PlantillaDAO($conexion);
$archivoDAO = new ArchivoPlantillaDAO($conexion);

$plantilla = $plantillaDAO->buscarPorIdConUsuario($idPlantilla);

if (!$plantilla) {
    die('Plantilla no encontrada.');
}

$archivos = $archivoDAO->buscarPorPlantilla($idPlantilla);

if (count($archivos) === 0) {
    die('Esta plantilla no tiene archivos para descargar.');
}


// 4. Crear el archivo ZIP temporal


$nombreSeguro = preg_replace('/[^a-zA-Z0-9_-]/', '_', $plantilla['nombre']);
$nombreZip = 'plantilla_' . $idPlantilla . '_' . $nombreSeguro . '.zip';
$rutaTemporal = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $nombreZip;

// Creamos el objeto que arma el ZIP.
$zip = new ZipArchive();

if ($zip->open($rutaTemporal, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die('No se pudo crear el archivo ZIP.');
}

$agregados = 0;

foreach ($archivos as $archivo) {
    $ruta = $archivo['ruta_archivo'];

    if (file_exists($ruta)) {
        $zip->addFile($ruta, basename($archivo['nombre_original']));
        $agregados++;
    }
}

$zip->close();

if ($agregados === 0) {
    die('No se pudo agregar ningún archivo al ZIP. Revisá uploads/plantillas.');
}


// 5. Enviar el ZIP al navegador

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $nombreZip . '"');
header('Content-Length: ' . filesize($rutaTemporal));
header('Pragma: no-cache');
header('Expires: 0');

readfile($rutaTemporal);
unlink($rutaTemporal);
exit();

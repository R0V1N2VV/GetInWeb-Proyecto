<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: explorador.php");
    exit();
}

if (!class_exists("ZipArchive")) {
    die("ZipArchive no está activado en PHP. Activá la extensión zip en XAMPP para descargar el código como .zip.");
}

$idPlantilla = intval($_GET["id"]);

$consulta = $conexion->prepare("SELECT * FROM plantillas WHERE id = ?");
$consulta->bind_param("i", $idPlantilla);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    die("Plantilla no encontrada.");
}

$plantilla = $resultado->fetch_assoc();

$consultaArchivos = $conexion->prepare("SELECT * FROM archivos_plantilla WHERE id_plantilla = ?");
$consultaArchivos->bind_param("i", $idPlantilla);
$consultaArchivos->execute();
$archivos = $consultaArchivos->get_result();

$nombreSeguro = preg_replace('/[^a-zA-Z0-9_-]/', '_', $plantilla["nombre"]);
$nombreZip = "plantilla_" . $idPlantilla . "_" . $nombreSeguro . ".zip";
$rutaTemporal = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $nombreZip;

$zip = new ZipArchive();

if ($zip->open($rutaTemporal, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("No se pudo crear el archivo ZIP.");
}

while ($archivo = $archivos->fetch_assoc()) {
    $ruta = $archivo["ruta_archivo"];

    if (file_exists($ruta)) {
        $zip->addFile($ruta, $archivo["nombre_original"]);
    }
}

$zip->close();

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=\"$nombreZip\"");
header("Content-Length: " . filesize($rutaTemporal));
header("Pragma: no-cache");
header("Expires: 0");

readfile($rutaTemporal);
unlink($rutaTemporal);
exit();
?>

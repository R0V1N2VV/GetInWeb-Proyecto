<?php
include("conexion.php");

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    $consulta = $conexion->prepare("SELECT tipo_mime, datos, ruta_archivo FROM imagenes WHERE id = ?");
    $consulta->bind_param("i", $id);

} elseif (isset($_GET["clave"])) {
    $clave = $_GET["clave"];

    $consulta = $conexion->prepare("SELECT tipo_mime, datos, ruta_archivo FROM imagenes WHERE clave = ?");
    $consulta->bind_param("s", $clave);

} else {
    http_response_code(404);
    exit("Imagen no encontrada");
}

$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    http_response_code(404);
    exit("Imagen no encontrada en la base de datos");
}

$imagen = $resultado->fetch_assoc();

if (!empty($imagen["datos"])) {
    header("Content-Type: " . $imagen["tipo_mime"]);
    echo $imagen["datos"];
    exit();
}

if (!empty($imagen["ruta_archivo"])) {
    $ruta = $imagen["ruta_archivo"];

    if (str_starts_with($ruta, "http://") || str_starts_with($ruta, "https://")) {
        header("Location: " . $ruta);
        exit();
    }

    $rutaCompleta = __DIR__ . "/" . $ruta;

    if (!file_exists($rutaCompleta)) {
        http_response_code(404);
        exit("El archivo está registrado en la base, pero no existe en la carpeta: " . $ruta);
    }

    header("Content-Type: " . $imagen["tipo_mime"]);
    readfile($rutaCompleta);
    exit();
}

http_response_code(404);
exit("La imagen existe en la base, pero no tiene datos ni ruta");
?>
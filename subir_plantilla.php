<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

function nombreSeguro($nombre){
    $nombre = basename($nombre);
    $nombre = preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombre);
    return $nombre;
}

function guardarImagenEnBD($conexion, $archivo, $clave, $nombre){
    $extensionesImagen = ["jpg", "jpeg", "png", "webp", "gif", "svg"];
    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesImagen)) {
        die("La imagen de vista previa debe ser JPG, JPEG, PNG, WEBP, GIF o SVG.");
    }

    $mime = mime_content_type($archivo["tmp_name"]);
    if ($mime === false) {
        $mime = "image/" . $extension;
    }

    $contenido = file_get_contents($archivo["tmp_name"]);
    $tipo = "blob";
    $url = null;

    $consulta = $conexion->prepare("INSERT INTO imagenes (clave, nombre, mime, tipo, contenido, url) VALUES (?, ?, ?, ?, ?, ?)");
    $consulta->bind_param("ssssss", $clave, $nombre, $mime, $tipo, $contenido, $url);
    $consulta->send_long_data(4, $contenido);
    $consulta->execute();

    return $conexion->insert_id;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idUsuario = $_SESSION["usuario_id"];
    $nombre = trim($_POST["nombre"]);
    $categoria = $_POST["categoria"];
    $descripcion = trim($_POST["descripcion"]);

    if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        die("Tenés que subir una imagen de vista previa.");
    }

    if (!isset($_FILES["archivos"]) || count($_FILES["archivos"]["name"]) === 0) {
        die("Tenés que subir al menos un archivo de la plantilla.");
    }

    $claveImagen = "preview_" . time() . "_" . rand(1000, 9999);
    $idImagenPreview = guardarImagenEnBD($conexion, $_FILES["imagen"], $claveImagen, "Preview de " . $nombre);

    $carpetaTemporal = "uploads/plantillas/temp_" . time() . "_" . rand(1000,9999);
    if (!is_dir($carpetaTemporal)) {
        mkdir($carpetaTemporal, 0777, true);
    }

    $archivoPrincipal = null;

    $consultaPlantilla = $conexion->prepare("INSERT INTO plantillas (id_usuario, nombre, categoria, descripcion, id_imagen_preview, carpeta, archivo_principal) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $consultaPlantilla->bind_param("isssiss", $idUsuario, $nombre, $categoria, $descripcion, $idImagenPreview, $carpetaTemporal, $archivoPrincipal);
    $consultaPlantilla->execute();

    $idPlantilla = $conexion->insert_id;
    $carpetaFinal = "uploads/plantillas/plantilla_" . $idPlantilla;

    if (!is_dir($carpetaFinal)) {
        mkdir($carpetaFinal, 0777, true);
    }

    $permitidas = ["html", "htm", "css", "js", "php", "txt", "json", "png", "jpg", "jpeg", "gif", "webp", "svg"];
    $archivosGuardados = [];

    foreach ($_FILES["archivos"]["name"] as $indice => $nombreOriginal) {
        if ($_FILES["archivos"]["error"][$indice] !== UPLOAD_ERR_OK) {
            continue;
        }

        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        if (!in_array($extension, $permitidas)) {
            continue;
        }

        $nombreLimpio = nombreSeguro($nombreOriginal);
        if ($nombreLimpio === "") {
            continue;
        }

        $rutaDestino = $carpetaFinal . "/" . $nombreLimpio;

        $contador = 1;
        $base = pathinfo($nombreLimpio, PATHINFO_FILENAME);
        $ext = pathinfo($nombreLimpio, PATHINFO_EXTENSION);

        while (file_exists($rutaDestino)) {
            $nuevoNombre = $base . "_" . $contador . "." . $ext;
            $rutaDestino = $carpetaFinal . "/" . $nuevoNombre;
            $nombreLimpio = $nuevoNombre;
            $contador++;
        }

        move_uploaded_file($_FILES["archivos"]["tmp_name"][$indice], $rutaDestino);

        $tipoMime = mime_content_type($rutaDestino);
        if ($tipoMime === false) {
            $tipoMime = "application/octet-stream";
        }

        $consultaArchivo = $conexion->prepare("INSERT INTO archivos_plantilla (id_plantilla, nombre_original, ruta_archivo, extension, tipo_mime) VALUES (?, ?, ?, ?, ?)");
        $consultaArchivo->bind_param("issss", $idPlantilla, $nombreLimpio, $rutaDestino, $extension, $tipoMime);
        $consultaArchivo->execute();

        $archivosGuardados[] = $nombreLimpio;

        if ($archivoPrincipal === null && strtolower($nombreLimpio) === "index.html") {
            $archivoPrincipal = $rutaDestino;
        }
    }

    if ($archivoPrincipal === null) {
        foreach ($archivosGuardados as $archivo) {
            $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
            if ($ext === "html" || $ext === "htm" || $ext === "php") {
                $archivoPrincipal = $carpetaFinal . "/" . $archivo;
                break;
            }
        }
    }

    $actualizar = $conexion->prepare("UPDATE plantillas SET carpeta = ?, archivo_principal = ? WHERE id = ?");
    $actualizar->bind_param("ssi", $carpetaFinal, $archivoPrincipal, $idPlantilla);
    $actualizar->execute();

    if (is_dir($carpetaTemporal)) {
        @rmdir($carpetaTemporal);
    }

    header("Location: explorador.php");
    exit();
}
?>

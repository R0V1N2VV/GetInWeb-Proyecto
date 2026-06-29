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

$idPlantilla = intval($_GET["id"]);
$consulta = $conexion->prepare("SELECT * FROM plantillas WHERE id = ?");
$consulta->bind_param("i", $idPlantilla);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    die("Plantilla no encontrada.");
}

$plantilla = $resultado->fetch_assoc();

if ($plantilla["archivo_principal"] === null || !file_exists($plantilla["archivo_principal"])) {
    die("Esta plantilla no tiene un archivo principal para mostrar. Subí un index.html o algún archivo .html/.php.");
}

$ruta = $plantilla["archivo_principal"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista previa - <?php echo htmlspecialchars($plantilla["nombre"]); ?></title>
    <link rel="stylesheet" href="php_extra.css?v=<?php echo filemtime('php_extra.css'); ?>">
</head>
<body class="preview-body">
    <div class="preview-barra">
        <a href="plantilla.php?id=<?php echo $idPlantilla; ?>" class="btn-secundario">Volver</a>
        <strong><?php echo htmlspecialchars($plantilla["nombre"]); ?></strong>
        <a href="<?php echo htmlspecialchars($ruta); ?>" target="_blank" class="btn-header">Abrir en pestaña</a>
    </div>

    <iframe class="preview-iframe" src="<?php echo htmlspecialchars($ruta); ?>"></iframe>
</body>
</html>

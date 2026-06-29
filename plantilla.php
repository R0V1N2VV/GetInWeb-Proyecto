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

$usuarioId = $_SESSION["usuario_id"];
$usuarioNombre = $_SESSION["usuario_nombre"] ?? "Usuario";
$usuarioEmail = $_SESSION["usuario_email"] ?? "";

$idPlantilla = intval($_GET["id"]);

$consulta = $conexion->prepare("SELECT plantillas.*, usuarios.nombre AS nombre_usuario
                                FROM plantillas
                                INNER JOIN usuarios ON plantillas.id_usuario = usuarios.id
                                WHERE plantillas.id = ?");
$consulta->bind_param("i", $idPlantilla);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    die("Plantilla no encontrada.");
}

$plantilla = $resultado->fetch_assoc();

$consultaArchivos = $conexion->prepare("SELECT * FROM archivos_plantilla WHERE id_plantilla = ? ORDER BY nombre_original ASC");
$consultaArchivos->bind_param("i", $idPlantilla);
$consultaArchivos->execute();
$archivos = $consultaArchivos->get_result();

$archivoSeleccionado = null;
$contenidoArchivo = "";
$mensajeArchivo = "Seleccioná un archivo para ver su contenido.";

if (isset($_GET["archivo"])) {
    $idArchivo = intval($_GET["archivo"]);
    $consultaArchivo = $conexion->prepare("SELECT * FROM archivos_plantilla WHERE id = ? AND id_plantilla = ?");
    $consultaArchivo->bind_param("ii", $idArchivo, $idPlantilla);
    $consultaArchivo->execute();
    $resultadoArchivo = $consultaArchivo->get_result();

    if ($resultadoArchivo->num_rows === 1) {
        $archivoSeleccionado = $resultadoArchivo->fetch_assoc();
        $ext = strtolower($archivoSeleccionado["extension"]);
        $esImagen = in_array($ext, ["png", "jpg", "jpeg", "gif", "webp", "svg"]);

        if ($esImagen) {
            $mensajeArchivo = "Este archivo es una imagen. Queda incluida al descargar el código, pero acá se muestran principalmente archivos de código.";
        } elseif (file_exists($archivoSeleccionado["ruta_archivo"])) {
            $contenidoArchivo = file_get_contents($archivoSeleccionado["ruta_archivo"]);
        } else {
            $mensajeArchivo = "No se encontró el archivo en la carpeta de uploads.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($plantilla["nombre"]); ?> - GetInWeb</title>
    <link rel="stylesheet" href="php_extra.css?v=<?php echo filemtime('php_extra.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-contenido">
        <div class="marca">
            <h2 class="logotipo">GetInWeb</h2>
            <span>Código de plantilla</span>
        </div>
        <nav class="menu">
            <ul>
                <ol><a href="explorador.php">Explorador</a></ol>
                <ol><a href="Personalizador/index.html">Generador</a></ol>
            </ul>
        </nav>

        <div class="usuario-menu">
            <button class="btn-usuario" id="btnUsuario">
                <?php echo htmlspecialchars($usuarioNombre); ?> ▾
            </button>

            <div class="usuario-dropdown" id="usuarioDropdown">
                <h4>Mi cuenta</h4>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuarioNombre); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuarioEmail); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($usuarioId); ?></p>
                <a href="usuario.php">Ver información</a>
                <a href="logout.php" class="cerrar-link">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>

<main class="detalle-main">
    <section class="detalle-plantilla">
        <div class="detalle-imagen">
            <?php if ($plantilla["id_imagen_preview"] !== null) { ?>
                <img src="imagen.php?id=<?php echo $plantilla["id_imagen_preview"]; ?>" alt="Vista previa">
            <?php } else { ?>
                <div class="sin-preview">Sin imagen</div>
            <?php } ?>
        </div>
        <div class="detalle-info">
            <span><?php echo htmlspecialchars($plantilla["categoria"]); ?></span>
            <h1><?php echo htmlspecialchars($plantilla["nombre"]); ?></h1>
            <p><?php echo htmlspecialchars($plantilla["descripcion"]); ?></p>
            <small>Subido por: <?php echo htmlspecialchars($plantilla["nombre_usuario"]); ?></small>
            <div class="detalle-botones">
                <a href="explorador.php" class="btn-secundario">Volver</a>
                <a href="descargar_plantilla.php?id=<?php echo $idPlantilla; ?>" class="btn-generador">Descargar código</a>
            </div>
        </div>
    </section>

    <section class="archivos-seccion">
        <h2>Ver código</h2>
        <p class="texto-ayuda-codigo">Elegí un archivo para ver su contenido. Para descargar todo el proyecto, usá el botón “Descargar código”.</p>

        <div class="lista-archivos">
            <?php while ($archivo = $archivos->fetch_assoc()) { ?>
                <a class="archivo-btn" href="plantilla.php?id=<?php echo $idPlantilla; ?>&archivo=<?php echo $archivo["id"]; ?>">
                    <?php echo htmlspecialchars($archivo["nombre_original"]); ?>
                </a>
            <?php } ?>
        </div>

        <pre class="visor-codigo"><?php
            if ($contenidoArchivo !== "") {
                echo htmlspecialchars($contenidoArchivo);
            } else {
                echo htmlspecialchars($mensajeArchivo);
            }
        ?></pre>
    </section>
</main>

<script>
    const btnUsuario = document.getElementById("btnUsuario");
    const usuarioDropdown = document.getElementById("usuarioDropdown");

    btnUsuario.addEventListener("click", function(e){
        e.stopPropagation();
        usuarioDropdown.classList.toggle("mostrar");
    });

    document.addEventListener("click", function(){
        usuarioDropdown.classList.remove("mostrar");
    });
</script>
</body>
</html>

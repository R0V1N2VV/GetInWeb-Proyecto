<?php

require_once "conexion.php";

use App\DAO\ArchivoPlantillaDAO;
use App\DAO\PlantillaDAO;
use App\Servicios\ServicioAutenticacion;
use App\Servicios\ServicioPlantillas;



$auth = new ServicioAutenticacion($conexion);
$usuario = $auth->requireLogin();

$usuarioId = intval($usuario['id']);
$usuarioNombre = $usuario['nombre'] ?? 'Usuario';
$usuarioEmail = $usuario['email'] ?? '';


// 2. ID de plantilla recibido por URL


if (!isset($_GET['id'])) {
    header('Location: explorador.php');
    exit();
}

$idPlantilla = intval($_GET['id']);


// 3. Búsqueda de datos mediante DAO y servicio


$plantillaDAO = new PlantillaDAO($conexion);
$archivoDAO = new ArchivoPlantillaDAO($conexion);
$servicioPlantillas = new ServicioPlantillas($conexion);

$plantilla = $plantillaDAO->buscarPorIdConUsuario($idPlantilla);

if (!$plantilla) {
    die('Plantilla no encontrada.');
}

$archivos = $archivoDAO->buscarPorPlantilla($idPlantilla);
$puedeBorrar = $servicioPlantillas->puedeBorrar($usuario, $plantilla);


// 4. Lectura del archivo seleccionado para mostrar código


$contenidoArchivo = '';
$mensajeArchivo = 'Seleccioná un archivo para ver su contenido.';

if (isset($_GET['archivo'])) {
    $idArchivo = intval($_GET['archivo']);
    $archivoSeleccionado = $archivoDAO->buscarPorIdYPlantilla($idArchivo, $idPlantilla);

    if ($archivoSeleccionado) {
        $extension = strtolower($archivoSeleccionado['extension']);
        $esImagen = in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'avif']);

        if ($esImagen) {
            $mensajeArchivo = 'Este archivo es una imagen. Queda incluida al descargar el código, pero acá se muestran principalmente archivos de código.';
        } elseif (file_exists($archivoSeleccionado['ruta_archivo'])) {
            $contenidoArchivo = file_get_contents($archivoSeleccionado['ruta_archivo']);
        } else {
            $mensajeArchivo = 'No se encontró el archivo en la carpeta de uploads.';
        }
    }
}



function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo e($plantilla['nombre']); ?> - GetInWeb</title>

    <link rel="stylesheet" href="php_extra.css?v=<?php echo filemtime('php_extra.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!--ENCABEZADO-->
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
                <?php echo e($usuarioNombre); ?> ▾
            </button>

            <div class="usuario-dropdown" id="usuarioDropdown">
                <h4>Mi cuenta</h4>

                <p><strong>Nombre:</strong> <?php echo e($usuarioNombre); ?></p>
                <p><strong>Email:</strong> <?php echo e($usuarioEmail); ?></p>
                <p><strong>ID:</strong> <?php echo e($usuarioId); ?></p>

                <a href="usuario.php">Ver información</a>
                <a href="logout.php" class="cerrar-link">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>

<main class="detalle-main">

    <!--INFORMACIÓN DE LA PLANTILLA-->
    <section class="detalle-plantilla">
        <div class="detalle-imagen">
            <?php if (!empty($plantilla['id_imagen_preview'])): ?>
                <img
                    src="imagen.php?id=<?php echo intval($plantilla['id_imagen_preview']); ?>"
                    alt="Vista previa"
                >
            <?php else: ?>
                <div class="sin-preview">Sin imagen</div>
            <?php endif; ?>
        </div>

        <div class="detalle-info">
            <span><?php echo e($plantilla['categoria']); ?></span>

            <h1><?php echo e($plantilla['nombre']); ?></h1>

            <p><?php echo e($plantilla['descripcion']); ?></p>

            <small>Subido por: <?php echo e($plantilla['nombre_usuario']); ?></small>

            <div class="detalle-botones">
                <a href="explorador.php" class="btn-secundario">Volver</a>

                <a href="descargar_plantilla.php?id=<?php echo $idPlantilla; ?>" class="btn-generador">
                    Descargar código
                </a>

                <?php if ($puedeBorrar): ?>
                    <form
                        action="eliminar_plantilla.php"
                        method="POST"
                        class="form-eliminar"
                        onsubmit="return confirm('¿Seguro que querés borrar esta plantilla?');"
                    >
                        <input type="hidden" name="id" value="<?php echo $idPlantilla; ?>">
                        <button type="submit" class="btn-eliminar">Borrar plantilla</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!--VISOR DE ARCHIVOS-->
    <section class="archivos-seccion">
        <h2>Ver código</h2>

        <p class="texto-ayuda-codigo">
            Elegí un archivo para ver su contenido. Para descargar todo el proyecto,
            usá el botón “Descargar código”.
        </p>

        <div class="lista-archivos">
            <?php foreach ($archivos as $archivo): ?>
                <a
                    class="archivo-btn"
                    href="plantilla.php?id=<?php echo $idPlantilla; ?>&archivo=<?php echo intval($archivo['id']); ?>"
                >
                    <?php echo e($archivo['nombre_original']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <pre class="visor-codigo"><?php echo $contenidoArchivo !== '' ? e($contenidoArchivo) : e($mensajeArchivo); ?></pre>
    </section>
</main>

<!--ENÚ DESPLEGABLE DEL USUARIO-->
<script>
    const btnUsuario = document.getElementById('btnUsuario');
    const usuarioDropdown = document.getElementById('usuarioDropdown');

    btnUsuario.addEventListener('click', function (evento) {
        evento.stopPropagation();
        usuarioDropdown.classList.toggle('mostrar');
    });

    document.addEventListener('click', function () {
        usuarioDropdown.classList.remove('mostrar');
    });
</script>

</body>
</html>

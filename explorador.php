<?php


require_once "conexion.php";

use App\DAO\PlantillaDAO;
use App\Servicios\ServicioAutenticacion;


// 1. Sesión y datos del usuario


$auth = new ServicioAutenticacion($conexion);
$usuario = $auth->requireLogin();

$usuarioId = intval($usuario['id']);
$usuarioNombre = $usuario['nombre'] ?? 'Usuario';
$usuarioEmail = $usuario['email'] ?? '';
$usuarioRol = $usuario['rol'] ?? 'usuario';
$esAdmin = $usuarioRol === 'admin';


// 2. Filtros del buscador


$busqueda = trim($_GET['busqueda'] ?? '');
$categoria = $_GET['categoria'] ?? 'todas';

$categorias = [
    'todas' => 'Todas',
    'negocio' => 'Negocio',
    'portfolio' => 'Portfolio',
    'tienda' => 'Tienda online',
    'blog' => 'Blog',
    'servicios' => 'Servicios',
];


// 3. Consulta de plantillas mediante DAO
$plantillaDAO = new PlantillaDAO($conexion);
// El explorador no escribe SQL. Le pide al DAO que traiga las plantillas filtradas.
$plantillas = $plantillaDAO->buscar($busqueda, $categoria);


function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function seleccionado(string $actual, string $valor): string
{
    return $actual === $valor ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Explorador de Plantillas - GetInWeb</title>

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
            <span>Explorador de plantillas</span>
        </div>

        <nav class="menu">
            <ul>
                <ol><a href="index.php">Inicio</a></ol>
                <ol><a href="Personalizador/index.html">Generador</a></ol>
                <ol><a href="#plantillas">Plantillas</a></ol>
                <ol><a href="#subir">Subir</a></ol>
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
                <p><strong>Rol:</strong> <?php echo $esAdmin ? 'Administrador' : 'Usuario'; ?></p>

                <a href="usuario.php">Ver información</a>
                <a href="logout.php" class="cerrar-link">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>

<main>

    <!--HERO DEL EXPLORADOR-->
    <section class="hero-generador">
        <div class="hero-generador-texto">
            <span class="etiqueta-generador">Generador personalizado</span>

            <h1>Probá nuestro generador de plantillas</h1>

            <p>
                Creá una base para tu página web de forma rápida, simple y personalizada.
                También podés explorar plantillas ya creadas por otros usuarios.
            </p>

            <div class="botones-hero">
                <a href="Personalizador/index.html" class="btn-generador">Ir al generador</a>
                <a href="#plantillas" class="btn-secundario">Ver plantillas</a>
            </div>
        </div>

        <div class="hero-generador-visual">
            <div class="ventana-generador">
                <div class="barra-ventana">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="contenido-ventana">
                    <div class="bloque-grande"></div>

                    <div class="bloques-chicos">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <div class="linea-corta"></div>
                    <div class="linea-larga"></div>
                    <div class="boton-falso"></div>
                </div>
            </div>
        </div>
    </section>

    <!--BUSCADOR Y FILTRO-->
    <section class="buscador-seccion" id="plantillas">
        <form method="GET" class="buscador-contenedor">
            <div class="campo-busqueda">
                <label>Buscar plantilla</label>

                <input
                    type="text"
                    name="busqueda"
                    placeholder="Ej: tienda, portfolio, blog..."
                    value="<?php echo e($busqueda); ?>"
                >
            </div>

            <div class="campo-busqueda">
                <label>Categoría</label>

                <select name="categoria">
                    <?php foreach ($categorias as $valor => $texto): ?>
                        <option value="<?php echo e($valor); ?>" <?php echo seleccionado($categoria, $valor); ?>>
                            <?php echo e($texto); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-filtrar">Buscar</button>
        </form>
    </section>

    <!--LISTADO DE PLANTILLAS-->
    <section class="plantillas-seccion">
        <div class="titulo-plantillas">
            <h2>Explorador de plantillas</h2>
            <p>Buscá diseños según el tipo de página que quieras crear.</p>
        </div>

        <div class="grid-plantillas">
            <?php if (count($plantillas) > 0): ?>
                <?php foreach ($plantillas as $plantilla): ?>
                    <?php
                    $plantillaId = intval($plantilla['id']);
                    $duenioPlantillaId = intval($plantilla['id_usuario']);
                    $puedeBorrar = $esAdmin || $duenioPlantillaId === $usuarioId;
                    ?>

                    <article class="plantilla-card">
                        <?php if (!empty($plantilla['id_imagen_preview'])): ?>
                            <img
                                src="imagen.php?id=<?php echo intval($plantilla['id_imagen_preview']); ?>"
                                alt="Vista previa de plantilla"
                            >
                        <?php else: ?>
                            <div class="sin-preview">Sin imagen</div>
                        <?php endif; ?>

                        <div class="plantilla-info">
                            <span><?php echo e($plantilla['categoria']); ?></span>

                            <h3><?php echo e($plantilla['nombre']); ?></h3>

                            <p><?php echo e($plantilla['descripcion']); ?></p>

                            <p class="subido-por">
                                Subido por: <?php echo e($plantilla['nombre_usuario']); ?>
                            </p>

                            <div class="acciones-card">
                                <a href="plantilla.php?id=<?php echo $plantillaId; ?>" class="btn-ver-archivos">
                                    Ver código
                                </a>

                                <a href="descargar_plantilla.php?id=<?php echo $plantillaId; ?>" class="btn-usar">
                                    Descargar código
                                </a>

                                <?php if ($puedeBorrar): ?>
                                    <form
                                        action="eliminar_plantilla.php"
                                        method="POST"
                                        class="form-eliminar"
                                        onsubmit="return confirm('¿Seguro que querés borrar esta plantilla? Esta acción no se puede deshacer.');"
                                    >
                                        <input type="hidden" name="id" value="<?php echo $plantillaId; ?>">
                                        <button type="submit" class="btn-eliminar">Borrar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="sin-resultados visible">No se encontraron plantillas.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- =================================================
         FORMULARIO PARA SUBIR PLANTILLA
         ================================================= -->
    <section class="subir-plantilla-seccion" id="subir">
        <div class="subir-contenedor">
            <div class="subir-texto">
                <span class="etiqueta-subir">Comunidad GetInWeb</span>

                <h2>Subí tu propia plantilla</h2>

                <p>
                    Cargá una imagen de vista previa y los archivos reales de tu plantilla.
                    La imagen queda registrada en la base y los archivos quedan listos para ver o descargar.
                </p>
            </div>

            <!-- enctype=multipart/form-data es obligatorio para que PHP reciba archivos en $_FILES. -->
            <form action="subir_plantilla.php" method="POST" enctype="multipart/form-data" class="form-plantilla">
                <div class="campo-form">
                    <label>Nombre de la plantilla</label>
                    <input type="text" name="nombre" placeholder="Ej: Portfolio moderno" required>
                </div>

                <div class="campo-form">
                    <label>Categoría</label>

                    <select name="categoria" required>
                        <option value="negocio">Negocio</option>
                        <option value="portfolio">Portfolio</option>
                        <option value="tienda">Tienda online</option>
                        <option value="blog">Blog</option>
                        <option value="servicios">Servicios</option>
                    </select>
                </div>

                <div class="campo-form campo-completo">
                    <label>Descripción</label>
                    <textarea name="descripcion" placeholder="Escribí una breve descripción..." required></textarea>
                </div>

                <div class="campo-form campo-completo">
                    <label>Imagen de vista previa</label>
                    <!-- name="imagen" coincide con $files['imagen'] en ServicioPlantillas. -->
                    <input type="file" name="imagen" accept="image/*" required>
                    <small>Esta imagen se va a ver en la tarjeta de tu plantilla.</small>
                </div>

                <div class="campo-form campo-completo">
                    <label>Archivos de la plantilla</label>
                    <!-- Los corchetes permiten subir varios archivos y recibirlos como array. -->
                    <input
                        type="file"
                        name="archivos[]"
                        multiple
                        accept=".html,.htm,.css,.js,.php,.txt,.json,.png,.jpg,.jpeg,.gif,.webp,.svg,.avif,.ico"
                        required
                    >
                    <small>Subí index.html, estilos.css, script.js e imágenes si tu código las usa.</small>
                </div>

                <button type="submit" class="btn-subir">Subir plantilla</button>
            </form>
        </div>
    </section>
</main>

<!-- =====================================================
     MENÚ DESPLEGABLE DEL USUARIO
     ===================================================== -->
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

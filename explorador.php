<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuarioId = $_SESSION["usuario_id"];
$usuarioNombre = $_SESSION["usuario_nombre"] ?? "Usuario";
$usuarioEmail = $_SESSION["usuario_email"] ?? "";

$busqueda = isset($_GET["busqueda"]) ? trim($_GET["busqueda"]) : "";
$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : "todas";

$sql = "SELECT plantillas.*, usuarios.nombre AS nombre_usuario
        FROM plantillas
        INNER JOIN usuarios ON plantillas.id_usuario = usuarios.id
        WHERE 1";

$parametros = [];
$tipos = "";

if ($busqueda !== "") {
    $sql .= " AND (plantillas.nombre LIKE ? OR plantillas.descripcion LIKE ?)";
    $busquedaSQL = "%" . $busqueda . "%";
    $parametros[] = $busquedaSQL;
    $parametros[] = $busquedaSQL;
    $tipos .= "ss";
}

if ($categoria !== "todas") {
    $sql .= " AND plantillas.categoria = ?";
    $parametros[] = $categoria;
    $tipos .= "s";
}

$sql .= " ORDER BY plantillas.fecha_subida DESC";
$consulta = $conexion->prepare($sql);

if (count($parametros) > 0) {
    $consulta->bind_param($tipos, ...$parametros);
}

$consulta->execute();
$plantillas = $consulta->get_result();
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

<main>
    <section class="hero-generador">
        <div class="hero-generador-texto">
            <span class="etiqueta-generador">Generador personalizado</span>
            <h1>Probá nuestro generador de plantillas</h1>
            <p>Creá una base para tu página web de forma rápida, simple y personalizada. También podés explorar plantillas ya creadas por otros usuarios.</p>
            <div class="botones-hero">
                <a href="Personalizador/index.html" class="btn-generador">Ir al generador</a>
                <a href="#plantillas" class="btn-secundario">Ver plantillas</a>
            </div>
        </div>

        <div class="hero-generador-visual">
            <div class="ventana-generador">
                <div class="barra-ventana"><span></span><span></span><span></span></div>
                <div class="contenido-ventana">
                    <div class="bloque-grande"></div>
                    <div class="bloques-chicos"><div></div><div></div><div></div></div>
                    <div class="linea-corta"></div>
                    <div class="linea-larga"></div>
                    <div class="boton-falso"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="buscador-seccion" id="plantillas">
        <form method="GET" class="buscador-contenedor">
            <div class="campo-busqueda">
                <label>Buscar plantilla</label>
                <input type="text" name="busqueda" placeholder="Ej: tienda, portfolio, blog..." value="<?php echo htmlspecialchars($busqueda); ?>">
            </div>
            <div class="campo-busqueda">
                <label>Categoría</label>
                <select name="categoria">
                    <option value="todas" <?php if ($categoria === "todas") echo "selected"; ?>>Todas</option>
                    <option value="negocio" <?php if ($categoria === "negocio") echo "selected"; ?>>Negocio</option>
                    <option value="portfolio" <?php if ($categoria === "portfolio") echo "selected"; ?>>Portfolio</option>
                    <option value="tienda" <?php if ($categoria === "tienda") echo "selected"; ?>>Tienda online</option>
                    <option value="blog" <?php if ($categoria === "blog") echo "selected"; ?>>Blog</option>
                    <option value="servicios" <?php if ($categoria === "servicios") echo "selected"; ?>>Servicios</option>
                </select>
            </div>
            <button type="submit" class="btn-filtrar">Buscar</button>
        </form>
    </section>

    <section class="plantillas-seccion">
        <div class="titulo-plantillas">
            <h2>Explorador de plantillas</h2>
            <p>Buscá diseños según el tipo de página que quieras crear.</p>
        </div>

        <div class="grid-plantillas">
            <?php if ($plantillas->num_rows > 0) { ?>
                <?php while ($plantilla = $plantillas->fetch_assoc()) { ?>
                    <div class="plantilla-card">
                        <?php if ($plantilla["id_imagen_preview"] !== null) { ?>
                            <img src="imagen.php?id=<?php echo $plantilla["id_imagen_preview"]; ?>" alt="Vista previa de plantilla">
                        <?php } else { ?>
                            <div class="sin-preview">Sin imagen</div>
                        <?php } ?>

                        <div class="plantilla-info">
                            <span><?php echo htmlspecialchars($plantilla["categoria"]); ?></span>
                            <h3><?php echo htmlspecialchars($plantilla["nombre"]); ?></h3>
                            <p><?php echo htmlspecialchars($plantilla["descripcion"]); ?></p>
                            <p class="subido-por">Subido por: <?php echo htmlspecialchars($plantilla["nombre_usuario"]); ?></p>

                            <div class="acciones-card">
                                <a href="plantilla.php?id=<?php echo $plantilla["id"]; ?>" class="btn-ver-archivos">Ver código</a>
                                <a href="descargar_plantilla.php?id=<?php echo $plantilla["id"]; ?>" class="btn-usar">Descargar código</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="sin-resultados visible">No se encontraron plantillas.</p>
            <?php } ?>
        </div>
    </section>

    <section class="subir-plantilla-seccion" id="subir">
        <div class="subir-contenedor">
            <div class="subir-texto">
                <span class="etiqueta-subir">Comunidad GetInWeb</span>
                <h2>Subí tu propia plantilla</h2>
                <p>Cargá una imagen de vista previa y los archivos reales de tu plantilla. La imagen queda guardada en la base de datos y los archivos quedan listos para ver o descargar.</p>
            </div>

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
                    <input type="file" name="imagen" accept="image/*" required>
                    <small>Esta imagen se guarda en la base de datos y es la única vista previa que se muestra en el explorador.</small>
                </div>
                <div class="campo-form campo-completo">
                    <label>Archivos de la plantilla</label>
                    <input type="file" name="archivos[]" multiple accept=".html,.htm,.css,.js,.php,.txt,.json,.png,.jpg,.jpeg,.gif,.webp,.svg" required>
                    <small>Subí index.html, style.css, script.js e imágenes si tu código las usa.</small>
                </div>
                <button type="submit" class="btn-subir">Subir plantilla</button>
            </form>
        </div>
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

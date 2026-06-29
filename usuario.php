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

$consulta = $conexion->prepare("SELECT fecha_registro FROM usuarios WHERE id = ?");
$consulta->bind_param("i", $usuarioId);
$consulta->execute();
$resultado = $consulta->get_result();
$usuario = $resultado->fetch_assoc();
$fechaRegistro = $usuario["fecha_registro"] ?? "2026";

$consultaPlantillas = $conexion->prepare("SELECT COUNT(*) AS total FROM plantillas WHERE id_usuario = ?");
$consultaPlantillas->bind_param("i", $usuarioId);
$consultaPlantillas->execute();
$resultadoPlantillas = $consultaPlantillas->get_result();
$datosPlantillas = $resultadoPlantillas->fetch_assoc();
$totalPlantillas = $datosPlantillas["total"] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - GetInWeb</title>
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
            <span>Web Generator</span>
        </div>

        <nav class="menu">
            <ul>
                <ol><a href="index.php">Inicio</a></ol>
                <ol><a href="explorador.php">Explorador</a></ol>
                <ol><a href="Personalizador/index.html">Generador</a></ol>
            </ul>
        </nav>

        <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
    </div>
</header>

<main class="perfil-main">
    <section class="perfil-container">
        <div class="avatar-simulado">
            <?php echo strtoupper(substr(htmlspecialchars($usuarioNombre), 0, 1)); ?>
        </div>

        <h1>Perfil de Usuario</h1>
        <p class="perfil-subtitulo">Gestioná la información de tu cuenta corporativa.</p>

        <div class="perfil-info">
            <div class="info-item">
                <label>Nombre de usuario</label>
                <p><?php echo htmlspecialchars($usuarioNombre); ?></p>
            </div>

            <div class="info-item">
                <label>Correo electrónico</label>
                <p><?php echo htmlspecialchars($usuarioEmail); ?></p>
            </div>

            <div class="info-item">
                <label>ID de usuario</label>
                <p><?php echo htmlspecialchars($usuarioId); ?></p>
            </div>

            <div class="info-item">
                <label>Miembro desde</label>
                <p><?php echo htmlspecialchars($fechaRegistro); ?></p>
            </div>

            <div class="info-item">
                <label>Plantillas subidas</label>
                <p><?php echo htmlspecialchars($totalPlantillas); ?></p>
            </div>
        </div>

        <div class="perfil-acciones">
            <a href="explorador.php" class="btn-generador">Ir al Explorador</a>
            <a href="Personalizador/index.html" class="btn-secundario">Ir al Generador</a>
            <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
        </div>
    </section>
</main>

</body>
</html>

<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
$email_usuario = isset($_SESSION['email']) ? $_SESSION['email'] : 'usuario@correo.com';
$fecha_registro = isset($_SESSION['fecha_registro']) ? $_SESSION['fecha_registro'] : '2026';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - GetInWeb</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            </ul>
        </nav>
        <a href="logout.php" class="btn-header btn-logout">Cerrar Sesión</a>
    </div>
</header>

<main>
    <div class="perfil-container">
        <div class="avatar-simulado">
            <?php echo substr(htmlspecialchars($nombre_usuario), 0, 1); ?>
        </div>
        <h1>Perfil de Usuario</h1>
        <p class="perfil-subtitulo">Gestioná la información de tu cuenta corporativa</p>

        <div class="perfil-info">
            <div class="info-item">
                <label>Nombre de usuario</label>
                <p><?php echo htmlspecialchars($nombre_usuario); ?></p>
            </div>
            <div class="info-item">
                <label>Correo electrónico</label>
                <p><?php echo htmlspecialchars($email_usuario); ?></p>
            </div>
            <div class="info-item">
                <label>Miembro desde</label>
                <p><?php echo htmlspecialchars($fecha_registro); ?></p>
            </div>
        </div>

        <div class="perfil-acciones">
            <a href="explorador.php" class="btn-header btn-secundario">Ir al Explorador</a>
            <a href="logout.php" class="btn-header btn-logout">Cerrar Sesión</a>
        </div>
    </div>
</main>

</body>
</html>
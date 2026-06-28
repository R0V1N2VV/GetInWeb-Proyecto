<?php
// Forzar a PHP a mostrar errores si algo falla
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("conexion.php"); // Conexión a la base de datos

// Validamos que el usuario esté logueado mediante el ID real
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Asignamos las variables de sesión que guardamos en el login
$nombre_usuario = $_SESSION['usuario'] ?? 'Usuario'; 
$email_usuario = $_SESSION['email'] ?? 'No disponible'; 
$fecha_registro = $_SESSION['fecha_registro'] ?? 'No disponible';

// --- LÓGICA PARA CONTAR LAS PLANTILLAS DEL USUARIO ---
$cantidad_plantillas = 0;
$id_usuario = $_SESSION["usuario_id"];

$stmt_count = $conexion->prepare("SELECT COUNT(*) AS total FROM plantillas WHERE id_usuario = ?");
$stmt_count->bind_param("i", $id_usuario);
$stmt_count->execute();
$res_count = $stmt_count->get_result();

if ($row_count = $res_count->fetch_assoc()) {
    $cantidad_plantillas = $row_count["total"];
}
$stmt_count->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - GetInWeb</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="php_extra.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght=300;400;500;600;700&display=swap" rel="stylesheet">
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
                <ol><a href="explorador.php">Plantillas</a></ol>
                <ol><a href="Personalizador/index.html">Generador</a></ol>
            </ul>
        </nav>

        <div class="usuario-nav">
            <div class="user-profile">
                <a href="usuario.php" class="user-name" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong> 👤
                </a>
            </div>
        </div>

    </div>
</header>

<main class="auth-main">
    <section class="auth-card" style="max-width: 500px;">
        <div class="auth-titulo">
            <h1>Mi Perfil</h1>
            <p>Gestioná la información de tu cuenta en GetInWeb.</p>
        </div>

        <div class="info-perfil" style="text-align: left; margin: 20px 0; font-size: 16px; line-height: 2;">
            <p style="margin-bottom: 12px;">
                <strong> Nombre de usuario:</strong> 
                <span style="color: #555;"><?php echo htmlspecialchars($nombre_usuario); ?></span>
            </p>
            <p style="margin-bottom: 12px;">
                <strong> Correo electrónico:</strong> 
                <span style="color: #555;"><?php echo htmlspecialchars($email_usuario); ?></span>
            </p>
            <p style="margin-bottom: 12px;">
                <strong> Miembro desde:</strong> 
                <span style="color: #555;"><?php echo htmlspecialchars($fecha_registro); ?></span>
            </p>
            <p style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                <strong> Plantillas subitdas:</strong> 
                <span class="badge-cantidad" style="background-color: #7b61ff; color: #ffffff; font-weight: bold; padding: 2px 10px; border-radius: 12px; font-size: 14px;">
                    <?php echo $cantidad_plantillas; ?>
                </span>
            </p>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
            <a href="expl
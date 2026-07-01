<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    Perfil del usuario.
    Muestra datos básicos de la cuenta y un resumen de sus plantillas.
*/

require_once "conexion.php";

use App\DAO\UsuarioDAO;
use App\Servicios\ServicioAutenticacion;

// =========================================================
// 1. Sesión y datos del usuario
// =========================================================

$auth = new ServicioAutenticacion($conexion);
$usuario = $auth->requireLogin();

$usuarioDAO = new UsuarioDAO($conexion);
$totalPlantillas = $usuarioDAO->contarPlantillas(intval($usuario['id']));

$fechaRegistro = $usuario['fecha_registro'] ?? '2026';
$inicialUsuario = strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1));

// =========================================================
// 2. Función auxiliar para imprimir HTML seguro
// =========================================================

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
        <div class="avatar-simulado"><?php echo e($inicialUsuario); ?></div>

        <h1>Perfil de Usuario</h1>
        <p class="perfil-subtitulo">Gestioná la información de tu cuenta.</p>

        <div class="perfil-info">
            <div class="info-item">
                <label>Nombre de usuario</label>
                <p><?php echo e($usuario['nombre']); ?></p>
            </div>

            <div class="info-item">
                <label>Correo electrónico</label>
                <p><?php echo e($usuario['email']); ?></p>
            </div>

            <div class="info-item">
                <label>Rol</label>
                <p><?php echo e($usuario['rol'] ?? 'usuario'); ?></p>
            </div>

            <div class="info-item">
                <label>Miembro desde</label>
                <p><?php echo e($fechaRegistro); ?></p>
            </div>

            <div class="info-item">
                <label>Plantillas subidas</label>
                <p><?php echo intval($totalPlantillas); ?></p>
            </div>
        </div>

        <div class="perfil-acciones">
            <a href="explorador.php" class="btn-header btn-secundario">Ir al Explorador</a>
            <a href="Personalizador/index.html" class="btn-header">Ir al Generador</a>
            <a href="logout.php" class="btn-header btn-logout">Cerrar sesión</a>
        </div>
    </section>
</main>

</body>
</html>

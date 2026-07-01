<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    Pantalla de registro.
    Toma los datos del formulario y delega la creación del usuario
    al servicio de autenticación.
*/

require_once "conexion.php";

use App\Servicios\ServicioAutenticacion;

// =========================================================
// 1. Inicio de sesión y variables
// =========================================================

ServicioAutenticacion::iniciarSesionSiHaceFalta();

$error = '';

// =========================================================
// 2. Procesamiento del formulario
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nombre === '' || $email === '' || $password === '') {
        $error = 'Completá todos los campos.';
    } else {
        $auth = new ServicioAutenticacion($conexion);
        $resultado = $auth->registrar($nombre, $email, $password);

        if ($resultado['ok']) {
            header('Location: explorador.php');
            exit();
        }

        $error = $resultado['error'];
    }
}

// =========================================================
// 3. Función auxiliar para imprimir HTML seguro
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

    <title>Registro - GetInWeb</title>

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

        <a href="login.php" class="btn-header">Iniciar sesión</a>
    </div>
</header>

<main class="auth-main">
    <section class="auth-card">
        <div class="auth-titulo">
            <h1>Crear cuenta</h1>
            <p>Registrate para acceder al explorador de plantillas.</p>
        </div>

        <?php if ($error !== ''): ?>
            <p class="mensaje-error"><?php echo e($error); ?></p>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="campo-auth">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Tu nombre" required>
            </div>

            <div class="campo-auth">
                <label>Email</label>
                <input type="email" name="email" placeholder="tu@email.com" required>
            </div>

            <div class="campo-auth">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Creá una contraseña" required>
            </div>

            <button type="submit" class="btn-auth">Crear cuenta</button>
        </form>

        <div class="auth-extra">
            <p>¿Ya tenés cuenta?</p>
            <a href="login.php">Iniciar sesión</a>
        </div>
    </section>
</main>

</body>
</html>

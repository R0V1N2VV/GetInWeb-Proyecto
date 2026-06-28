<?php
// Forzar a PHP a mostrar el error real en pantalla en vez de quedarse en blanco
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("conexion.php");

// Verificamos si realmente hay una sesión firme
if (isset($_SESSION["usuario_id"]) && !empty($_SESSION["usuario_id"])) { 
    header("Location: explorador.php"); 
    exit(); 
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    // Consulta limpia y segura con todos los campos de tu SQL
    $consulta = $conexion->prepare("SELECT id, nombre, email, password, fecha_registro FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($password, $usuario["password"])) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario"] = $usuario["nombre"];
            $_SESSION["email"] = $usuario["email"];
            $_SESSION["fecha_registro"] = $usuario["fecha_registro"];
            
            header("Location: explorador.php"); 
            exit();
        } else { 
            $error = "La contraseña es incorrecta."; 
        }
    } else { 
        $error = "No existe una cuenta con ese email."; 
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - GetInWeb</title>
    <link rel="stylesheet" href="php_extra.css">
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
        <a href="index.php" class="btn-header">Volver al inicio</a>
    </div>
</header>
<main class="auth-main">
    <section class="auth-card">
        <div class="auth-titulo">
            <h1>Iniciar sesión</h1>
            <p>Ingresá a tu cuenta para explorar y subir plantillas.</p>
        </div>
        <?php if ($error !== "") { ?>
            <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>
        <form method="POST" class="auth-form">
            <div class="campo-auth">
                <label>Email</label>
                <input type="email" name="email" placeholder="Ingresá tu email" required>
            </div>
            <div class="campo-auth">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Ingresá tu contraseña" required>
            </div>
            <button type="submit" class="btn-auth">Ingresar</button>
        </form>
        <div class="auth-extra">
            <p>¿No tenés cuenta?</p>
            <a href="registro.php">Registrarse</a>
        </div>
    </section>
</main>
</body>
</html>
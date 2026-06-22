<?php
session_start();
include("conexion.php");
if (isset($_SESSION["usuario_id"])) { header("Location: explorador.php"); exit(); }
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $passwordPlano = $_POST["password"];
    if (strlen($passwordPlano) < 4) { $error = "La contraseña debe tener al menos 4 caracteres."; }
    else {
        $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verificar->bind_param("s", $email);
        $verificar->execute();
        $resultado = $verificar->get_result();
        if ($resultado->num_rows > 0) { $error = "Ya existe una cuenta con ese email."; }
        else {
            $password = password_hash($passwordPlano, PASSWORD_DEFAULT);
            $consulta = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $consulta->bind_param("sss", $nombre, $email, $password);
            if ($consulta->execute()) {
                $_SESSION["usuario_id"] = $conexion->insert_id;
                $_SESSION["usuario_nombre"] = $nombre;
                $_SESSION["usuario_email"] = $email;
                header("Location: explorador.php"); exit();
            } else { $error = "No se pudo crear la cuenta."; }
        }
    }
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Registro - GetInWeb</title><link rel="stylesheet" href="php_extra.css"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet"></head><body><header><div class="header-contenido"><div class="marca"><h2 class="logotipo">GetInWeb</h2><span>Web Generator</span></div><a href="login.php" class="btn-header">Iniciar sesión</a></div></header><main class="auth-main"><section class="auth-card"><div class="auth-titulo"><h1>Crear cuenta</h1><p>Registrate para acceder al explorador de plantillas.</p></div><?php if ($error !== "") { ?><p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p><?php } ?><form method="POST" class="auth-form"><div class="campo-auth"><label>Nombre</label><input type="text" name="nombre" placeholder="Ingresá tu nombre" required></div><div class="campo-auth"><label>Email</label><input type="email" name="email" placeholder="Ingresá tu email" required></div><div class="campo-auth"><label>Contraseña</label><input type="password" name="password" placeholder="Creá una contraseña" required></div><button type="submit" class="btn-auth">Registrarse</button></form><div class="auth-extra"><p>¿Ya tenés cuenta?</p><a href="login.php">Iniciar sesión</a></div></section></main></body></html>

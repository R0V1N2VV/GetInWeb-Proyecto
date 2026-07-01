<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    Punto de entrada para subir plantillas.
    El formulario de explorador.php llega acá por POST.
    Este archivo no guarda todo directamente: llama a ServicioPlantillas.
*/

require_once "conexion.php";

use App\Servicios\ServicioAutenticacion;
use App\Servicios\ServicioPlantillas;

// =========================================================
// 1. Verificar sesión
// =========================================================

$auth = new ServicioAutenticacion($conexion);
$usuario = $auth->requireLogin();

// =========================================================
// 2. Solo se permite entrar por POST desde el formulario
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: explorador.php');
    exit();
}

// =========================================================
// 3. Delegar la subida a ServicioPlantillas
// =========================================================

try {
    // Creamos el servicio principal de plantillas y le pasamos la conexión.
$servicioPlantillas = new ServicioPlantillas($conexion);
    $idPlantilla = $servicioPlantillas->subir($_POST, $_FILES, intval($usuario['id']));

    header('Location: plantilla.php?id=' . $idPlantilla);
    exit();
} catch (Throwable $error) {
    http_response_code(400);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error al subir plantilla</title>
    </head>
    <body>
        <h2>Error al subir plantilla</h2>
        <p><?php echo htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
        <p><a href="explorador.php#subir">Volver</a></p>
    </body>
    </html>
    <?php
}

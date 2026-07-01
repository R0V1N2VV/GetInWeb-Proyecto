<?php

require_once "conexion.php";

use App\Servicios\ServicioAutenticacion;
use App\Servicios\ServicioPlantillas;


// 1. Verificar sesión

$auth = new ServicioAutenticacion($conexion);
$usuario = $auth->requireLogin();


// 2. Solo se permite borrar por POST


if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: explorador.php');
    exit();
}


// 3. Delegar la eliminación a ServicioPlantillas

try {
    $idPlantilla = intval($_POST['id']);
    $servicioPlantillas = new ServicioPlantillas($conexion);

    $servicioPlantillas->eliminar($idPlantilla, $usuario);

    header('Location: explorador.php');
    exit();
} catch (Throwable $error) {
    http_response_code(403);
    echo '<h2>No se pudo borrar la plantilla</h2>';
    echo '<p>' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p><a href="explorador.php">Volver</a></p>';
}

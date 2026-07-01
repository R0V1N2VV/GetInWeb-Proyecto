
<?php


require_once "conexion.php";

use App\Servicios\ServicioAutenticacion;

// Cierra la sesión y vuelve al inicio.
ServicioAutenticacion::cerrarSesion();

header('Location: index.php');
exit();

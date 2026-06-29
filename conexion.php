<?php
$host = "localhost";
$usuario = "root";
$password = "";
$baseDeDatos = "getinweb";

$conexion = new mysqli($host, $usuario, $password, $baseDeDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>

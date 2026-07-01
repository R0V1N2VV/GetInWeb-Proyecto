<?php

require_once __DIR__ . '/app/Configuracion/Autoload.php';

use App\Configuracion\BaseDatos;
use App\Servicios\PreparadorBaseDatos;


// Este archivo deja disponible la variable $conexion.
// También prepara la base de datos si faltan tablas o columnas.


$conexion = BaseDatos::obtenerConexion();

PreparadorBaseDatos::preparar($conexion);

<?php
// VERSIÓN COMENTADA PARA ESTUDIO.

/*
    Clase de configuración de base de datos.
    Centraliza los datos de conexión y devuelve un objeto mysqli listo para usar.
*/

namespace App\Configuracion;

use mysqli;

class BaseDatos
{
    private static ?mysqli $connection = null;

    public static function obtenerConexion(): mysqli
    {
        if (self::$connection === null) {
            $host = getenv('DB_HOST') ?: 'pma.torga.com.ar';
            $user = getenv('DB_USER') ?: 'u7_NZjbVv4557';
            $password = getenv('DB_PASS') ?: 'l@p7vGRx60m0=@i2fREriPZp';
            $database = getenv('DB_NAME') ?: 's7_getinweb';
            $port = intval(getenv('DB_PORT') ?: 3306);

            self::$connection = new mysqli($host, $user, $password, $database, $port);

            if (self::$connection->connect_error) {
                die('Error de conexión a la base de datos: ' . self::$connection->connect_error);
            }

            self::$connection->set_charset('utf8mb4');
        }

        return self::$connection;
    }
}
?>

<?php

namespace App\Servicios;

use App\DAO\UsuarioDAO;
use mysqli;

class ServicioAutenticacion
{
    public function __construct(private mysqli $conexion) {}

    public static function iniciarSesionSiHaceFalta(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(string $email, string $password): bool
    {
        $usuarioDAO = new UsuarioDAO($this->conexion);
        $usuario = $usuarioDAO->buscarPorEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            return false;
        }

        self::iniciarSesionSiHaceFalta();
        $this->guardarUsuarioEnSesion($usuario);

        return true;
    }

    public function registrar(string $nombre, string $email, string $password): array
    {
        $usuarioDAO = new UsuarioDAO($this->conexion);

        if ($usuarioDAO->buscarPorEmail($email)) {
            return [
                'ok' => false,
                'error' => 'Ya existe una cuenta con ese email.',
            ];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $idUsuario = $usuarioDAO->crear($nombre, $email, $passwordHash, 'usuario');

        self::iniciarSesionSiHaceFalta();

        $_SESSION['usuario_id'] = $idUsuario;
        $_SESSION['usuario_nombre'] = $nombre;
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario_rol'] = 'usuario';

        return [
            'ok' => true,
            'id' => $idUsuario,
        ];
    }

    public function requireLogin(): array
    {
        self::iniciarSesionSiHaceFalta();

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login.php');
            exit();
        }

        $usuarioDAO = new UsuarioDAO($this->conexion);
        $usuario = $usuarioDAO->buscarPorId(intval($_SESSION['usuario_id']));

        if (!$usuario) {
            session_destroy();
            header('Location: login.php');
            exit();
        }

        $this->guardarUsuarioEnSesion($usuario);

        return $usuario;
    }

    public static function cerrarSesion(): void
    {
        self::iniciarSesionSiHaceFalta();
        session_destroy();
    }

    public static function estaLogueado(): bool
    {
        self::iniciarSesionSiHaceFalta();

        return isset($_SESSION['usuario_id']);
    }

    private function guardarUsuarioEnSesion(array $usuario): void
    {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_rol'] = $usuario['rol'] ?? 'usuario';
    }
}

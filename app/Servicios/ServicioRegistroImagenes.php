<?php


namespace App\Servicios;

use App\DAO\ImagenDAO;
use mysqli;

class ServicioRegistroImagenes
{
    public function __construct(private mysqli $conexion) {}

    public function registrarImagen(string $clave, string $ruta, ?string $nombre = null, ?string $mime = null): bool
    {
        $ruta = str_replace('\\', '/', $ruta);
        $esUrl = str_starts_with($ruta, 'http://') || str_starts_with($ruta, 'https://');

        if (!$esUrl) {
            $rutaCompleta = __DIR__ . '/../../' . $ruta;
            if (!file_exists($rutaCompleta)) {
                return false;
            }
            $mime = $mime ?: (mime_content_type($rutaCompleta) ?: 'image/*');
        } else {
            $mime = $mime ?: 'image/jpeg';
        }

        if ($nombre === null || $nombre === '') {
            $path = parse_url($ruta, PHP_URL_PATH);
            $nombre = basename($path ?: $ruta) ?: $clave;
        }

        return (new ImagenDAO($this->conexion))->guardarOActualizarRuta($clave, $ruta, $nombre, $mime);
    }

    public function registrarImagenesDelSitio(): void
    {
        $imagenes = [
            'inicio_wix' => 'https://static.wixstatic.com/media/0784b1_636e025454fa4d40a2374266c91e07b8~mv2.jpg/v1/fill/w_728,h_455,al_c,q_80,enc_avif,quality_auto/cans-reduced.jpg',
            'elegirnos_graffica' => 'https://graffica.info/wp-content/uploads/2017/10/03-970x599.jpg',
            'plantilla_tienda' => 'https://images01.nicepagecdn.com/page/31/62/es/plantilla-html-31627.jpg',
            'generador' => 'imgs/generador.png',
            'plantilla_eso' => 'imgs/eso.png',
            'plantilla_tour' => 'imgs/tour.jpg',
            'plantilla_desig' => 'imgs/desig.png',
            'inicio' => 'imgs/inicio.png',
            'elegirnos' => 'imgs/elegirnos.png',
            'info3_1' => 'imgs/info3_1.png',
            'info3_2' => 'imgs/info3_2.png',
            'info3_3' => 'imgs/info3_3.png'
        ];

        foreach ($imagenes as $clave => $ruta) {
            $this->registrarImagen($clave, $ruta);
        }
    }
}
?>

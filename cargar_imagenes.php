<?php
include("conexion.php");

function ejecutarSinCortar($conexion, $sql) {
    try {
        $conexion->query($sql);
    } catch (Exception $e) {
        // Si ya existe, no corta el programa.
    }
}

ejecutarSinCortar($conexion, "ALTER TABLE imagenes ADD COLUMN nombre_original VARCHAR(180) NULL");
ejecutarSinCortar($conexion, "ALTER TABLE imagenes ADD COLUMN tipo_mime VARCHAR(80) NULL");
ejecutarSinCortar($conexion, "ALTER TABLE imagenes ADD COLUMN datos LONGBLOB NULL");
ejecutarSinCortar($conexion, "ALTER TABLE imagenes ADD COLUMN ruta_archivo VARCHAR(1000) NULL");
ejecutarSinCortar($conexion, "ALTER TABLE imagenes MODIFY ruta_archivo VARCHAR(1000) NULL");
ejecutarSinCortar($conexion, "ALTER TABLE imagenes ADD UNIQUE KEY clave_unica (clave)");

function cargarImagenPorRuta($conexion, $clave, $ruta, $tipoMime = null) {
    $ruta = str_replace("\\", "/", $ruta);

    if (str_starts_with($ruta, "http://") || str_starts_with($ruta, "https://")) {
        $nombreOriginal = basename(parse_url($ruta, PHP_URL_PATH));

        if ($nombreOriginal === "" || $nombreOriginal === false) {
            $nombreOriginal = $clave;
        }

        if ($tipoMime === null) {
            $tipoMime = "image/jpeg";
        }

        $sql = "INSERT INTO imagenes (clave, nombre_original, tipo_mime, datos, ruta_archivo)
                VALUES (?, ?, ?, NULL, ?)
                ON DUPLICATE KEY UPDATE
                    nombre_original = VALUES(nombre_original),
                    tipo_mime = VALUES(tipo_mime),
                    datos = NULL,
                    ruta_archivo = VALUES(ruta_archivo)";

        $consulta = $conexion->prepare($sql);
        $consulta->bind_param("ssss", $clave, $nombreOriginal, $tipoMime, $ruta);

        if ($consulta->execute()) {
            echo "Imagen URL cargada o actualizada: " . $clave . "<br>";
        } else {
            echo "Error cargando URL " . $clave . ": " . $conexion->error . "<br>";
        }

        return;
    }

    $rutaCompleta = __DIR__ . "/" . $ruta;

    if (!file_exists($rutaCompleta)) {
        echo "No se encontró: " . $ruta . "<br>";
        return;
    }

    $nombreOriginal = basename($ruta);
    $tipoMime = mime_content_type($rutaCompleta);

    $sql = "INSERT INTO imagenes (clave, nombre_original, tipo_mime, datos, ruta_archivo)
            VALUES (?, ?, ?, NULL, ?)
            ON DUPLICATE KEY UPDATE
                nombre_original = VALUES(nombre_original),
                tipo_mime = VALUES(tipo_mime),
                datos = NULL,
                ruta_archivo = VALUES(ruta_archivo)";

    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("ssss", $clave, $nombreOriginal, $tipoMime, $ruta);

    if ($consulta->execute()) {
        echo "Imagen local cargada o actualizada: " . $clave . " → " . $ruta . "<br>";
    } else {
        echo "Error cargando " . $clave . ": " . $conexion->error . "<br>";
    }
}

/*
    Imágenes locales.
*/

$imagenesLocales = [
    "generador" => "imgs/generador.png",
    "plantilla_eso" => "imgs/eso.png",
    "plantilla_tour" => "imgs/tour.jpg",
    "plantilla_desig" => "imgs/desig.png",
    "plantilla_tienda" => "imgs/tienda.png",
    "inicio" => "imgs/inicio.png",
    "elegirnos" => "imgs/elegirnos.png",
    "info3_1" => "imgs/info3_1.png",
    "info3_2" => "imgs/info3_2.png",
    "info3_3" => "imgs/info3_3.png"
];

foreach ($imagenesLocales as $clave => $ruta) {
    cargarImagenPorRuta($conexion, $clave, $ruta);
}

/*
    Imágenes externas.
    Acá tenés que pegar las URLs reales que estaban en tu index.
*/

$imagenesExternas = [
    "inicio_wix" => "https://static.wixstatic.com/media/0784b1_636e025454fa4d40a2374266c91e07b8~mv2.jpg/v1/fill/w_728,h_455,al_c,q_80,enc_avif,quality_auto/cans-reduced.jpg",
    "elegirnos_graffica" => "https://graffica.info/wp-content/uploads/2017/10/03-970x599.jpg",
    "plantilla_tienda" => "https://images01.nicepagecdn.com/page/31/62/es/plantilla-html-31627.jpg",
    "info3_1" => "https://static.wixstatic.com/media/110ec7_29c3c950c65d44c9a3106b9bba6a0d98~mv2.png/v1/fill/w_924,h_2019,al_c,q_90,enc_avif,quality_auto/110ec7_29c3c950c65d44c9a3106b9bba6a0d98~mv2.png",
    "info3_2" => "https://static.wixstatic.com/media/110ec7_10be33f8cf9c40feb0a0f89a993f5dac~mv2.png/v1/fill/w_924,h_2411,al_c,q_95,enc_avif,quality_auto/110ec7_10be33f8cf9c40feb0a0f89a993f5dac~mv2.png",
    "info3_3" => "https://static.wixstatic.com/media/110ec7_44bfb3739bd24c2bab5fbf852fc54800~mv2.png/v1/fill/w_924,h_2288,al_c,q_95,enc_avif,quality_auto/110ec7_44bfb3739bd24c2bab5fbf852fc54800~mv2.png"
 ];

foreach ($imagenesExternas as $clave => $url) {
    if ($url !== "" && !str_contains($url, "PEGAR_ACA")) {
        cargarImagenPorRuta($conexion, $clave, $url, "image/jpeg");
    }
}

echo "<br><strong>Listo. Imágenes cargadas o actualizadas.</strong>";
?>
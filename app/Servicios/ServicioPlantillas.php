<?php

namespace App\Servicios;

use App\DAO\ArchivoPlantillaDAO;
use App\DAO\ImagenDAO;
use App\DAO\PlantillaDAO;
use mysqli;

class ServicioPlantillas
{
    // Esta propiedad guarda la conexión a MySQL.
    // Se recibe desde afuera para que el servicio no tenga que crear la conexión.
    public function __construct(private mysqli $conexion) {}


    public function subir(array $post, array $files, int $usuarioId): int
    {
        // 1. Tomamos los datos principales del formulario.
        $nombre = trim($post['nombre'] ?? '');
        $categoria = trim($post['categoria'] ?? '');
        $descripcion = trim($post['descripcion'] ?? '');

        // 2. Validamos que la información obligatoria exista.
        $this->validarDatosDePlantilla($nombre, $categoria, $descripcion, $files);

        // 3. Nos aseguramos de que existan las carpetas donde se guardan archivos.
        ServicioArchivos::asegurarCarpeta('uploads/previews');
        ServicioArchivos::asegurarCarpeta('uploads/plantillas');

        // 4. Guardamos la imagen preview y la registramos en la tabla imagenes.
        // guardarImagenPreview() mueve el archivo a uploads/previews y adentro usa ImagenDAO.
        // Por eso esta línea termina devolviendo el ID generado en la tabla imagenes.
        $idImagenPreview = $this->guardarImagenPreview($files['imagen'], $nombre);

        // 5. Creamos el registro principal de la plantilla en la tabla plantillas.
        // El servicio crea el DAO y le pasa la conexión.
        // El INSERT real está dentro de PlantillaDAO::crear().
        $plantillaDAO = new PlantillaDAO($this->conexion);
        $idPlantilla = $plantillaDAO->crear(
            $usuarioId,
            $nombre,
            $categoria,
            $descripcion,
            $idImagenPreview
        );

        // 6. Creamos la carpeta específica de esa plantilla.
        // Usamos el ID de la base para nombrar la carpeta.
        // Así cada plantilla tiene una carpeta distinta y no se pisan archivos.
        $carpetaPlantilla = 'uploads/plantillas/plantilla_' . $idPlantilla;
        ServicioArchivos::asegurarCarpeta($carpetaPlantilla);

        // 7. Guardamos todos los archivos HTML, CSS, JS e imágenes de la plantilla.
        $archivoPrincipal = $this->guardarArchivosDePlantilla(
            $idPlantilla,
            $files['archivos'],
            $carpetaPlantilla
        );

        // 8. Actualizamos la tabla plantillas con la carpeta y el archivo principal.
        $plantillaDAO->actualizarAlmacenamiento(
            $idPlantilla,
            $carpetaPlantilla,
            $archivoPrincipal
        );

        return $idPlantilla;
    }

    /*Revisa si el usuario tiene permiso para borrar una plantilla.*/
    public function puedeBorrar(array $usuario, array $plantilla): bool
    {
        $esAdmin = ($usuario['rol'] ?? 'usuario') === 'admin';
        $esDuenio = intval($plantilla['id_usuario']) === intval($usuario['id']);

        return $esAdmin || $esDuenio;
    }

    /*Elimina una plantilla.*/
    public function eliminar(int $idPlantilla, array $usuario): void
    {
        $plantillaDAO = new PlantillaDAO($this->conexion);
        // DAO que registra cada archivo en la tabla archivos_plantilla.
        $archivoDAO = new ArchivoPlantillaDAO($this->conexion);
        $imagenDAO = new ImagenDAO($this->conexion);

        $plantilla = $plantillaDAO->buscarPorIdConUsuario($idPlantilla);

        if (!$plantilla) {
            throw new \Exception('Plantilla no encontrada.');
        }

        if (!$this->puedeBorrar($usuario, $plantilla)) {
            throw new \Exception('No tenés permiso para borrar esta plantilla.');
        }

        // 1. Borramos cada archivo real de la plantilla.
        foreach ($archivoDAO->buscarPorPlantilla($idPlantilla) as $archivo) {
            ServicioArchivos::borrarArchivoSiEstaEnUploads($archivo['ruta_archivo']);
        }

        // 2. Borramos la imagen preview si existe.
        $idImagenPreview = intval($plantilla['id_imagen_preview'] ?? 0);

        if ($idImagenPreview > 0) {
            $imagen = $imagenDAO->buscarPorId($idImagenPreview);

            if ($imagen) {
                $rutaImagen = $imagen['ruta_archivo'] ?: ($imagen['url'] ?? '');
                ServicioArchivos::borrarArchivoSiEstaEnUploads($rutaImagen);
            }

            $imagenDAO->borrarPorId($idImagenPreview);
        }

        // 3. Borramos la carpeta completa si quedó vacía o con restos.
        if (!empty($plantilla['carpeta'])) {
            ServicioArchivos::borrarCarpetaSiEstaEnUploads($plantilla['carpeta']);
        }

        // 4. Borramos registros de la base.
        $archivoDAO->borrarPorPlantilla($idPlantilla);
        $plantillaDAO->borrar($idPlantilla);
    }

    private function validarDatosDePlantilla(
        string $nombre,
        string $categoria,
        string $descripcion,
        array $files
    ): void {
        if ($nombre === '' || $categoria === '' || $descripcion === '') {
            throw new \Exception('Completá nombre, categoría y descripción.');
        }

        if (!isset($files['imagen']) || $files['imagen']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Tenés que subir una imagen de vista previa.');
        }

        if (!isset($files['archivos']) || empty($files['archivos']['name'][0])) {
            throw new \Exception('Tenés que subir al menos un archivo de la plantilla.');
        }
    }

    /**
     * Guarda la imagen de vista previa.
     *
     * Parte física:
     * - mueve la imagen desde la carpeta temporal de PHP a uploads/previews.
     *
     * Parte de base de datos:
     * - registra la ruta de esa imagen usando ImagenDAO.
     * - devuelve el ID insertado para relacionarlo con la plantilla.
     */
    private function guardarImagenPreview(array $imagen, string $nombrePlantilla): int
    {
        $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif'];

        if (!in_array($extension, $extensionesPermitidas)) {
            throw new \Exception('La imagen de vista previa debe ser JPG, PNG, WEBP, GIF, SVG o AVIF.');
        }

        $claveImagen = 'preview_' . time() . '_' . rand(1000, 9999);
        $nombreImagen = $claveImagen . '.' . $extension;
        $rutaPreview = 'uploads/previews/' . $nombreImagen;

        if (!move_uploaded_file($imagen['tmp_name'], $rutaPreview)) {
            throw new \Exception('No se pudo guardar la imagen preview.');
        }

        $mime = mime_content_type($rutaPreview) ?: 'image/' . $extension;

        // Acá recién aparece el DAO.
        // El servicio no escribe el SQL: le pide a ImagenDAO que inserte el registro.
        $imagenDAO = new ImagenDAO($this->conexion);

        return $imagenDAO->insertarPreview(
            $claveImagen,
            'Preview de ' . $nombrePlantilla,
            $mime,
            $rutaPreview
        );
    }

    /**
     * Guarda los archivos reales de la plantilla.
     *
     * Por cada archivo subido:
     * - controla que no haya error,
     * - limpia el nombre para evitar rutas raras,
     * - lo mueve a la carpeta de la plantilla,
     * - registra su ruta en archivos_plantilla mediante ArchivoPlantillaDAO.
     */
    private function guardarArchivosDePlantilla(
        int $idPlantilla,
        array $archivos,
        string $carpetaPlantilla
    ): ?string {
        // DAO que registra cada archivo en la tabla archivos_plantilla.
        $archivoDAO = new ArchivoPlantillaDAO($this->conexion);
        $archivoPrincipal = null;

        $extensionesPermitidas = [
            'html', 'htm', 'css', 'js', 'php', 'txt', 'json',
            'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'avif', 'ico'
        ];

        $cantidadArchivos = count($archivos['name']);

        for ($i = 0; $i < $cantidadArchivos; $i++) {
            if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            // basename() evita que el nombre del archivo traiga carpetas del equipo del usuario.
            $nombreOriginal = basename($archivos['name'][$i]);
            $nombreSeguro = ServicioArchivos::nombreSeguro($nombreOriginal);
            $extension = strtolower(pathinfo($nombreSeguro, PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionesPermitidas)) {
                continue;
            }

            // Ruta final donde queda guardado el archivo real en el servidor.
            $rutaDestino = $carpetaPlantilla . '/' . $nombreSeguro;

            // PHP primero deja el archivo en una ruta temporal.
            // move_uploaded_file() lo mueve a la carpeta definitiva.
            if (!move_uploaded_file($archivos['tmp_name'][$i], $rutaDestino)) {
                continue;
            }

            // Registramos el archivo en la tabla archivos_plantilla.
            $archivoDAO->agregar(
                $idPlantilla,
                $nombreOriginal,
                $rutaDestino,
                $extension
            );

            // Elegimos un archivo principal para saber cuál sería la entrada del proyecto.
            if (in_array($extension, ['html', 'htm', 'php'])) {
                if ($nombreSeguro === 'index.html' || $nombreSeguro === 'index.php' || $archivoPrincipal === null) {
                    $archivoPrincipal = $rutaDestino;
                }
            }
        }

        return $archivoPrincipal;
    }
}
?>

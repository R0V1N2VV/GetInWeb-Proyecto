<?php
session_start(); include("conexion.php");
if (!isset($_SESSION["usuario_id"])) { header("Location: login.php"); exit(); }
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario=$_SESSION["usuario_id"]; $nombre=trim($_POST["nombre"]); $categoria=$_POST["categoria"]; $descripcion=trim($_POST["descripcion"]);
    $extensionesPermitidas=["html","css","js","php","txt"]; $extensionesImagen=["jpg","jpeg","png","webp","avif"];
    if(!is_dir("uploads/previews")){mkdir("uploads/previews",0777,true);} if(!is_dir("uploads/plantillas")){mkdir("uploads/plantillas",0777,true);}
    if(!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"]!==UPLOAD_ERR_OK){die("No se pudo subir la imagen de vista previa.");}
    $imagen=$_FILES["imagen"]; $extensionImagen=strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION)); if(!in_array($extensionImagen,$extensionesImagen)){die("La imagen debe ser JPG, JPEG, PNG, WEBP o AVIF.");}
    $rutaImagen="uploads/previews/preview_".time()."_".rand(1000,9999).".".$extensionImagen; if(!move_uploaded_file($imagen["tmp_name"],$rutaImagen)){die("No se pudo guardar la imagen.");}
    $consulta=$conexion->prepare("INSERT INTO plantillas (id_usuario, nombre, categoria, descripcion, imagen_preview) VALUES (?, ?, ?, ?, ?)"); $consulta->bind_param("issss",$idUsuario,$nombre,$categoria,$descripcion,$rutaImagen); $consulta->execute(); $idPlantilla=$conexion->insert_id;
    foreach($_FILES["archivos"]["name"] as $indice=>$nombreArchivoOriginal){ if($_FILES["archivos"]["error"][$indice]!==UPLOAD_ERR_OK){continue;} $tmpArchivo=$_FILES["archivos"]["tmp_name"][$indice]; $extension=strtolower(pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION)); if(!in_array($extension,$extensionesPermitidas)){continue;} $rutaArchivo="uploads/plantillas/plantilla_".$idPlantilla."_".time()."_".rand(1000,9999).".".$extension; if(move_uploaded_file($tmpArchivo,$rutaArchivo)){ $consultaArchivo=$conexion->prepare("INSERT INTO archivos_plantilla (id_plantilla, nombre_original, ruta_archivo, extension) VALUES (?, ?, ?, ?)"); $consultaArchivo->bind_param("isss",$idPlantilla,$nombreArchivoOriginal,$rutaArchivo,$extension); $consultaArchivo->execute(); }}
    header("Location: explorador.php"); exit();
}
header("Location: explorador.php"); exit();
?>

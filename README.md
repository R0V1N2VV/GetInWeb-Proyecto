GETINWEB - VERSION PHP CON IMAGENES EN BASE DE DATOS

1) Copiar la carpeta completa en:
   C:\xampp\htdocs\

2) Abrir XAMPP y activar:
   - Apache
   - MySQL

3) Entrar a phpMyAdmin:
   http://localhost/phpmyadmin

4) Importar el archivo:
   getinweb.sql

5) Cargar las imagenes principales en la base de datos abriendo una vez:
   http://localhost/GetInWeb_COMPLETO_BD_FUNCIONAL/cargar_imagenes.php

   Si cambias el nombre de la carpeta, cambia la URL segun corresponda.

6) Abrir la pagina:
   http://localhost/GetInWeb_COMPLETO_BD_FUNCIONAL/index.php

FUNCIONAMIENTO

- El index conserva el diseño original y usa estilos.css.
- Las imagenes principales del index se cargan desde imagen.php, por clave.
- Si la base de datos no esta conectada o no ejecutaste cargar_imagenes.php, esas imagenes no aparecen.
- El usuario se registra o inicia sesion.
- El explorador permite subir plantillas.
- La imagen de vista previa de cada plantilla se guarda como BLOB en la tabla imagenes.
- Los archivos de la plantilla se guardan en uploads/plantillas/plantilla_ID/ conservando sus nombres originales.
- Si subis index.html y style.css, el HTML puede encontrar su CSS porque no se renombran raro.
- Para ver la pagina subida, usar el boton "Ver pagina".

IMPORTANTE PARA PROBAR SUBIDA DE PLANTILLAS

Cuando subas una pagina, selecciona todos los archivos que necesita:
- index.html
- style.css
- script.js si tiene
- imagenes que use ese HTML/CSS

Si el HTML llama a una imagen como imgs/foto.png pero vos subis foto.png suelta, no la va a encontrar. Para esta version simple conviene que los archivos esten en la misma carpeta y que el HTML use rutas simples como:

<img src="foto.png">
<link rel="stylesheet" href="style.css">

DATOS DE CONEXION

conexion.php usa:
- host: localhost
- usuario: root
- password: vacio
- base: getinweb

Eso es lo normal en XAMPP.

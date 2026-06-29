<?php
session_start();

if (isset($_SESSION["usuario_id"])) {
    $linkExplorador = "explorador.php";
} else {
    $linkExplorador = "login.php";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetInWeb</title>
    <link rel="stylesheet" href="estilos.css?v=<?php echo filemtime('estilos.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
 <div class="contenedor">

    
 <header>
    <div class="header-contenido">

        <div class="marca">
            <h2 class="logotipo">GetInWeb</h2>
            <span>Web Generator</span>
        </div>

        <nav class="menu">
            <ul>
                <ol><a href="">Inicio</a></ol>
                <ol><a href="">Servicios</a></ol>
                <ol><a href="">Portafolio</a></ol>
                <ol><a href="">Contacto</a></ol>
            </ul>
        </nav>

        <a href="<?php echo $linkExplorador; ?>" class="btn-header">Crear ahora</a>

    </div>
</header>
  

    <main>
    <!-- bienvenida -->
    <div class="inicio-color">
    
        <div class="bienvenida">
            <h1>Crea una página web de una forma nueva</h1>
            <h3>Aquí te damos una experiencia única para crear tu página web de manera fácil y rápida, junto a nuestro generador de planillas personalizadas</h3>
  
            <a href="<?php echo $linkExplorador; ?>" class="boton"><strong>Comenzar Ya</strong></a>
        </div>
    
        <div class="imagen-inicio">
            <img src="imagen.php?clave=inicio_wix" alt="Imagen de bienvenida"> 
        </div>
    </div>

    <!--division-->
  <section class="seccion-elegirnos aparecer">

    <div class="titulo-elegirnos">
        <span class="decoracion-titulo"></span>
        <h1>¿Por qué elegirnos?</h1>
        <div class="linea-titulo"></div>
    </div>

    <div class="elegirnos-contenido">

        <div class="elegirnos-texto">

            <div class="subtitulo-elegirnos">
                <div class="icono-principal"></div>
                <h2>Obten nuevas ideas</h2>
            </div>

            <div class="beneficio-card">
                <span>✓</span>
                <p>Variedad de plantillas personalizadas para tus proyectos</p>
            </div>

            <div class="beneficio-card">
                <span>✓</span>
                <p>Nuevos estilos creativos con gran desarrollo</p>
            </div>

            <div class="beneficio-card">
                <span>✓</span>
                <p>Estilos únicos creados por usuarios diversos</p>
            </div>

            <div class="beneficio-card">
                <span>✓</span>
                <p>Genera plantillas a tu gusto en base a nuestro generador personalizado</p>
            </div>

        </div>

        <div class="elegirnos-imagen">
            <img src="imagen.php?clave=elegirnos_graffica" alt="Plantillas web">
        </div>

    </div>

</section>


    <section class="plantillas-carrusel aparecer">

    <div class="titulo-info2">
        <h1>Plantillas Web creadas para tu uso y lograr tus mayores ambiciones</h1>
        <br>
        <p>Explora nuestro catálogo de plantillas personalizadas y creadas para usar en tus proyectos diariamente</p>
    </div>

    <div class="carrusel-contenedor carrusel-simple">
        <div class="carrusel-ventana" id="carruselPlantillas">

            <div class="slide-plantilla">
                <img src="imagen.php?clave=plantilla_eso" alt="Plantilla web">
            </div>

            <div class="slide-plantilla">
                <img src="imagen.php?clave=plantilla_tour" alt="Plantilla web">
            </div>

            <div class="slide-plantilla">
                <img src="imagen.php?clave=plantilla_desig" alt="Plantilla web">
            </div>

            <div class="slide-plantilla">
                <img src="imagen.php?clave=plantilla_tienda" alt="Plantilla web">
            </div>

        </div>
    </div>

    </section>



    <div class="informacion3 aparecer">
        
        <div class="izquierda3">
            
            <div class="pri-izquierda3">
                <img src="imagen.php?clave=info3_1" alt="Plantilla ejemplo">
            </div>
            
            <div class="seg-izquierda3"> 
                <img src="imagen.php?clave=info3_2" alt="Plantilla ejemplo">
            </div>

            <div class="ter-izquierda3">
                <img src="imagen.php?clave=info3_3" alt="Plantilla ejemplo">
            </div>

        </div>

    
        <div class="derecha3">
            <h1>Ve nuestro extenso catalogo de plantillas ya diseñadas</h1>
            <br>
            <p>Explora nuestra amplia colección de plantillas profesionales diseñadas para distintos tipos de proyectos. Ya sea para un negocio, una tienda online, un portafolio o un blog, encontrarás opciones modernas, personalizables y listas para usar. Elige la que mejor se adapte a tu estilo y comienza a construir tu presencia online en minutos.</p>
            <br><br>
            <p>Descubre una gran variedad de plantillas listas para usar. Diseños modernos, personalizables y adaptados a diferentes necesidades para que puedas crear tu página web de forma rápida y sencilla.</p>
            <br><br>
            <p>Encuentra la plantilla perfecta para tu proyecto. Navega entre cientos de diseños profesionales, personalízalos a tu gusto y crea una página web única que refleje tu marca y tus objetivos.</p>
            <br><br><br>
            <a href="<?php echo $linkExplorador; ?>" class="boton"><strong>Explorar Plantillas</strong></a>
        </div>
    
    </div>

    <div class="divisions aparecer">
        <h1>Nuestra invencion</h1>
    </div>

    <div class="generador-contenido aparecer">


        <div class="imagen-generador">
            <img src="imagen.php?clave=generador" alt="Generador personalizado">
        </div>

        <div class="derecha4">
            <h1>Genera tu propia plantilla personalizada</h1>
            <br>
            <p>Con nuestro generador de plantillas, puedes crear diseños personalizados para tu página web de manera fácil y rápida. Elige entre una amplia variedad de opciones de diseño, colores, fuentes y estilos para crear una plantilla única que se adapte a tus necesidades y refleje tu estilo personal. ¡Comienza a diseñar tu página web hoy mismo con nuestro generador de plantillas!</p>
            <br><br>
            <a href="Personalizador/index.html" class="boton1"><strong>Generar Plantilla</strong></a>
        </div>
    </div>


   

<section class="seccion-planes aparecer">

    <div class="divisionss">
        <div class="titulo-planes">
            <div>
                <h1>Planes de suscripción</h1>
                <p>Elegí la opción que mejor se adapte a tu proyecto.</p>
            </div>

            <button class="btn-ver-planes" id="btnPlanes">
                Ver planes ↓
            </button>
        </div>
    </div>

    <div class="planes-contenido" id="planesContenido">

        <div class="card-plan gratis">
            <div class="plan-top">
                <h2>Gratuito</h2>

                <div class="precio">
                    <span class="simbolo">$</span>
                    <span class="numero">0</span>
                    <span class="periodo">/ mes</span>
                </div>

                <p class="descripcion-plan">
                    Ideal para empezar a explorar la plataforma.
                </p>
            </div>

            <button class="boton-plan boton-outline">Tu plan actual</button>

            <div class="plan-info">
                <p>✦ Acceso a plantillas básicas</p>
                <p>✦ Soporte por correo electrónico</p>
                <p>✦ Actualizaciones limitadas</p>
                <p>✦ Personalización inicial</p>
            </div>
        </div>

        <div class="card-plan basico destacada">
            <div class="plan-top">
                <h2>Básico</h2>

                <div class="precio">
                    <span class="simbolo">$</span>
                    <span class="numero">10</span>
                    <span class="periodo">/ mes</span>
                </div>

                <p class="descripcion-plan">
                    Más herramientas para crear un sitio más completo.
                </p>
            </div>

            <button class="boton-plan boton-violeta">Elegir plan</button>

            <div class="plan-info">
                <p>✦ Más plantillas disponibles</p>
                <p>✦ Personalización avanzada</p>
                <p>✦ Soporte por correo electrónico</p>
                <p>✦ Recursos extra de diseño</p>
                <p>✦ Mejor experiencia de edición</p>
            </div>
        </div>

        <div class="card-plan premium">
            <div class="plan-top">
                <h2>Premium</h2>

                <div class="precio">
                    <span class="simbolo">$</span>
                    <span class="numero">20</span>
                    <span class="periodo">/ mes</span>
                </div>

                <p class="descripcion-plan">
                    Para quienes buscan acceso total y herramientas premium.
                </p>
            </div>

            <button class="boton-plan boton-claro">Elegir plan</button>

            <div class="plan-info">
                <p>✦ Acceso a todas las plantillas</p>
                <p>✦ Soporte prioritario 24/7</p>
                <p>✦ Actualizaciones ilimitadas</p>
                <p>✦ Herramientas premium</p>
                <p>✦ Exportación avanzada</p>
            </div>
        </div>

        <div class="card-plan empresarial">
            <div class="plan-top">
                <h2>Empresarial</h2>

                <div class="precio precio-texto">
                    <span class="texto-precio">Precio según uso</span>
                </div>

                <p class="descripcion-plan">
                    Soluciones pensadas para equipos y negocios.
                </p>
            </div>

            <button class="boton-plan boton-claro">Contactar</button>

            <div class="plan-info">
                <p>✦ Gestión para múltiples usuarios</p>
                <p>✦ Soporte dedicado 24/7</p>
                <p>✦ Herramientas avanzadas</p>
                <p>✦ Escalabilidad para empresas</p>
                <p>✦ Administración centralizada</p>
            </div>
        </div>

    </div>

</section>

<section class="mensaje-final aparecer">

    <h1>Comenzá a construir tu presencia digital</h1>

    <p>
        GetInWeb te ayuda a crear páginas modernas, rápidas y personalizadas
        sin complicarte con procesos técnicos innecesarios.
    </p>

    <a href="Personalizador/index.html" class="boton">
        Crear mi página
    </a>

</section>

<section class="faq-seccion aparecer">

    <div class="faq-titulo">
        <h1>Preguntas frecuentes</h1>
        <p>Resolvé tus dudas antes de empezar a crear tu página web.</p>
    </div>

    <div class="faq-contenido">

        <details class="faq-item">
            <summary>¿Necesito saber programar?</summary>
            <p>
                No. GetInWeb está pensado para que puedas crear una página web
                sin tener conocimientos avanzados de programación.
            </p>
        </details>

        <details class="faq-item">
            <summary>¿Puedo personalizar las plantillas?</summary>
            <p>
                Sí. Podés modificar colores, estilos, secciones, textos e imágenes
                para adaptar la página a tu proyecto.
            </p>
        </details>

        <details class="faq-item">
            <summary>¿El generador crea una página completa?</summary>
            <p>
                El generador te ayuda a crear una estructura inicial personalizada,
                que después podés editar y mejorar según tus necesidades.
            </p>
        </details>

        <details class="faq-item">
            <summary>¿Las páginas se ven bien en celular?</summary>
            <p>
                La idea de GetInWeb es trabajar con diseños adaptables para que
                la página pueda verse correctamente en distintos dispositivos.
            </p>
        </details>

    </div>

</section>


    </main>  


    <footer>

    <div class="footer-contenido">

        <div class="footer-marca">
            <h2>GetInWeb</h2>
            <p>
                Crea páginas web modernas, rápidas y personalizadas con herramientas simples e intuitivas.
            </p>
        </div>

        <div class="footer-links">
            <h3>Navegación</h3>
            <a href="">Inicio</a>
        </div>

        <div class="footer-links">
            <h3>Proyecto</h3>
            <a href="">Plantillas</a>
            <a href="">Generador Web</a>
            <a href="">Planes</a>
            <a href="">Preguntas frecuentes</a>
        </div>

    </div>

    <div class="footer-final">
        <p>© 2026 GetInWeb - Todos los derechos reservados</p>
    </div>

</footer>

 </div>

 <script>
    const elementos = document.querySelectorAll('.aparecer');

    const mostrarElemento = new IntersectionObserver((entradas) => {
        entradas.forEach((entrada) => {
            if (entrada.isIntersecting) {
                entrada.target.classList.add('visible');
            }
        });
    });

    elementos.forEach((elemento) => {
        mostrarElemento.observe(elemento);
    });
 </script>
 <script>
    const btnPlanes = document.getElementById("btnPlanes");
    const planesContenido = document.getElementById("planesContenido");

    btnPlanes.addEventListener("click", function(){
        planesContenido.classList.toggle("mostrar");

        if(planesContenido.classList.contains("mostrar")){
            btnPlanes.textContent = "Ocultar planes ↑";
        } else {
            btnPlanes.textContent = "Ver planes ↓";
        }
    });
</script>
<script>
    const carruselPlantillas = document.getElementById("carruselPlantillas");

    if (carruselPlantillas) {
        const slides = Array.from(carruselPlantillas.querySelectorAll(".slide-plantilla"));
        const contenedor = carruselPlantillas.closest(".carrusel-contenedor");
        let indiceActivo = 0;
        let intervaloCarrusel = null;
        let temporizadorScroll = null;

        const dots = document.createElement("div");
        dots.className = "carrusel-dots";

        slides.forEach((slide, indice) => {
            const dot = document.createElement("button");
            dot.className = "carrusel-dot";
            dot.type = "button";
            dot.setAttribute("aria-label", "Ver plantilla " + (indice + 1));
            dot.addEventListener("click", () => irASlide(indice));
            dots.appendChild(dot);
        });

        if (contenedor && slides.length > 1) {
            contenedor.appendChild(dots);
        }

        function actualizarDots() {
            dots.querySelectorAll(".carrusel-dot").forEach((dot, indice) => {
                dot.classList.toggle("activo", indice === indiceActivo);
            });
        }

        function irASlide(indice) {
            if (!slides[indice]) return;

            indiceActivo = indice;
            carruselPlantillas.scrollTo({
                left: slides[indice].offsetLeft - carruselPlantillas.offsetLeft,
                behavior: "smooth"
            });
            actualizarDots();
        }

        function detectarSlideActivo() {
            let masCercano = 0;
            let menorDistancia = Infinity;

            slides.forEach((slide, indice) => {
                const distancia = Math.abs(
                    carruselPlantillas.scrollLeft - (slide.offsetLeft - carruselPlantillas.offsetLeft)
                );

                if (distancia < menorDistancia) {
                    menorDistancia = distancia;
                    masCercano = indice;
                }
            });

            indiceActivo = masCercano;
            actualizarDots();
        }

        function avanzarCarrusel() {
            if (slides.length <= 1) return;
            irASlide((indiceActivo + 1) % slides.length);
        }

        function iniciarCarrusel() {
            detenerCarrusel();
            intervaloCarrusel = setInterval(avanzarCarrusel, 3300);
        }

        function detenerCarrusel() {
            if (intervaloCarrusel) {
                clearInterval(intervaloCarrusel);
                intervaloCarrusel = null;
            }
        }

        carruselPlantillas.addEventListener("scroll", () => {
            clearTimeout(temporizadorScroll);
            temporizadorScroll = setTimeout(detectarSlideActivo, 80);
        }, { passive: true });

        carruselPlantillas.addEventListener("mouseenter", detenerCarrusel);
        carruselPlantillas.addEventListener("mouseleave", iniciarCarrusel);
        carruselPlantillas.addEventListener("touchstart", detenerCarrusel, { passive: true });
        carruselPlantillas.addEventListener("touchend", iniciarCarrusel, { passive: true });

        actualizarDots();
        iniciarCarrusel();
    }
</script>
</body>
</html>
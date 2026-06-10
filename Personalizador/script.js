let carrito = 0;

function agregarCarrito() {
    carrito++;

    const contador = document.getElementById("cart-count");

    if(contador) {
        contador.textContent = carrito;
    }
}

function generarPagina() {

    const tipo = document.getElementById("tipo").value;
    const tema = document.getElementById("tema").value;
    const color = document.getElementById("color").value;

    const carritoAtivo = document.getElementById("carrito").checked;
    const galeriaActiva = document.getElementById("galeria").checked;
    const contactoActivo = document.getElementById("contacto").checked;
    const modoActivo = document.getElementById("modo").checked;

    const preview = document.getElementById("preview");

    preview.className = "preview " + tema + " " + color;

    if(tipo === "ecommerce") {
        preview.innerHTML = `
            <h2>Tienda online</h2>
            <p>Productos modernos y diseño simple.</p>
        `;
    }

    if(tipo === "portfolio") {
        preview.innerHTML = `
            <h2>Mi Portfolio</h2>
            <p>Hola, soy desarrollador web.</p>
        `;
    }

    if(tipo === "proyectos") {
        preview.innerHTML = `
            <h2>Mis proyectos</h2>
            <p>Una colección de ideas y trabajos personales.</p>
        `;
    }

    if(tipo === "fotografia") {
        preview.innerHTML = `
            <h2>Galería fotográfica</h2>
            <p>Capturando momentos únicos.</p>
        `;
    }

    if(carritoAtivo) {
        preview.innerHTML += `
            <div class="extra-section">
                <div class="shop-header">
                    <h3>Carrito</h3>
                    <div class="cart">
                        🛒 <span id="cart-count">0</span>
                    </div>
                </div>

                <button onclick="agregarCarrito()">Agregar producto</button>
            </div>
        `;
    }

    if(galeriaActiva) {
        preview.innerHTML += `
            <div class="extra-section">
                <h3>Galería</h3>

                <div class="gallery">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        `;
    }

    if(contactoActivo) {
        preview.innerHTML += `
            <div class="extra-section">
                <h3>Contacto</h3>

                <div class="contact-form">
                    <input type="text" placeholder="Nombre">
                    <textarea placeholder="Mensaje"></textarea>
                    <button>Enviar</button>
                </div>
            </div>
        `;
    }

    if(modoActivo) {
        preview.innerHTML += `
            <div class="extra-section">
                <button onclick="toggleModo()">Cambiar modo</button>
            </div>
        `;
    }


    if(tipo === "landing") {
        preview.innerHTML = `
            <h2>Bienvenido</h2>
            <p>Esta es una landing page moderna.</p>
        `;
    }

    function toggleModo() {
    const preview = document.getElementById("preview");

    if(preview.classList.contains("dark")) {
        preview.classList.remove("dark");
        preview.classList.add("light");
    } else {
        preview.classList.remove("light");
        preview.classList.add("dark");
    }
}

}
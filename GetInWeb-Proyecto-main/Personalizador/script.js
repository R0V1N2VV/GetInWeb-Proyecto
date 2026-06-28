document
const configuracion = {
    tipo: "",
    estilo: "tecnologico",
    funcionalidades: []
};

const steps = document.querySelectorAll(".step");
const preview = document.getElementById("preview");
let pasoActual = 1;

const tipos = {
    portfolio: {
        titulo: "Portfolio Profesional",
        subtitulo: "Presentación personal y proyectos.",
        badge: "Marca personal",
        tarjetas: ["Sobre mí", "Proyectos destacados", "Contacto directo"]
    },
    fotografia: {
        titulo: "Galería Fotográfica",
        subtitulo: "Exhibición visual de trabajos.",
        badge: "Portfolio visual",
        tarjetas: ["Series editoriales", "Eventos", "Sesiones premium"]
    },
    ecommerce: {
        titulo: "Tienda Online",
        subtitulo: "Venta de productos digitales o físicos.",
        badge: "Catálogo activo",
        tarjetas: ["Productos destacados", "Carrito rápido", "Promociones"]
    },
    blog: {
        titulo: "Blog Personal",
        subtitulo: "Ideas, artículos y contenido editorial.",
        badge: "Contenido constante",
        tarjetas: ["Artículos recientes", "Categorías", "Suscripción"]
    },
    landing: {
        titulo: "Landing de Servicios",
        subtitulo: "Captación de leads y presentación comercial.",
        badge: "Conversión alta",
        tarjetas: ["Beneficios", "Testimonios", "Llamado a la acción"]
    }
};

const estilos = {
    tecnologico: {
        titulo: "Tecnológico",
        descripcion: "Brillos fríos, contraste fuerte y look moderno.",
        acento: "#3b82f6"
    },
    natural: {
        titulo: "Natural",
        descripcion: "Paleta suave, orgánica y equilibrada.",
        acento: "#22c55e"
    },
    rustico: {
        titulo: "Rústico",
        descripcion: "Texturas cálidas y sensación artesanal.",
        acento: "#d97706"
    },
    minimalista: {
        titulo: "Minimalista",
        descripcion: "Espacios amplios y foco total en el contenido.",
        acento: "#475569"
    },
    cyberpunk: {
        titulo: "Cyberpunk",
        descripcion: "Neón, energía alta y estética futurista.",
        acento: "#ec4899"
    }
};

const funcionalidades = {
    chat: {
        icono: "💬",
        titulo: "Chat de soporte",
        descripcion: "Respuesta inmediata para consultas de clientes."
    },
    maps: {
        icono: "🗺️",
        titulo: "Google Maps",
        descripcion: "Ubicación visible para visitas y envíos."
    },
    contacto: {
        icono: "✉️",
        titulo: "Contacto avanzado",
        descripcion: "Campos extra y validaciones para captar mejor los datos."
    },
    newsletter: {
        icono: "📬",
        titulo: "Newsletter",
        descripcion: "Suscripción para campañas, noticias y novedades."
    },
    modo: {
        icono: "🌓",
        titulo: "Modo automático",
        descripcion: "Ajuste claro/oscuro según el sistema del usuario."
    }
};

function mostrarPaso(numero) {
    pasoActual = numero;

    steps.forEach(step => {
        step.classList.remove("active");
    });

    const stepActivo = document.querySelector(`[data-step="${numero}"]`);

    if (stepActivo) {
        stepActivo.classList.add("active");
    }

    resaltarSeleccion();
}

function resaltarSeleccion() {
    document.querySelectorAll("[data-type]").forEach(btn => {
        btn.classList.toggle("selected", configuracion.tipo === btn.dataset.type);
    });

    document.querySelectorAll("[data-style]").forEach(btn => {
        btn.classList.toggle("selected", configuracion.estilo === btn.dataset.style);
    });

    document.querySelectorAll("[data-feature]").forEach(btn => {
        btn.classList.toggle("selected", configuracion.funcionalidades.includes(btn.dataset.feature));
    });
}

function seleccionarTipo(tipo) {
    configuracion.tipo = tipo;
    actualizarPreview();
    resaltarSeleccion();
}

function seleccionarEstilo(estilo) {
    configuracion.estilo = estilo;
    actualizarPreview();
    resaltarSeleccion();
}

function alternarFuncionalidad(feature) {
    if (configuracion.funcionalidades.includes(feature)) {
        configuracion.funcionalidades = configuracion.funcionalidades.filter(item => item !== feature);
    } else {
        configuracion.funcionalidades = [...configuracion.funcionalidades, feature];
    }

    actualizarPreview();
    resaltarSeleccion();
}

document.querySelectorAll("[data-type]").forEach(btn => {
    btn.addEventListener("click", () => {
        seleccionarTipo(btn.dataset.type);
    });
});

document.querySelectorAll("[data-style]").forEach(btn => {
    btn.addEventListener("click", () => {
        seleccionarEstilo(btn.dataset.style);
    });
});

document.querySelectorAll("[data-feature]").forEach(btn => {
    btn.addEventListener("click", () => {
        alternarFuncionalidad(btn.dataset.feature);
    });
});

document.querySelectorAll("[data-next]").forEach(btn => {
    btn.addEventListener("click", () => {
        mostrarPaso(Number(btn.dataset.next));
    });
});

document.querySelectorAll("[data-prev]").forEach(btn => {
    btn.addEventListener("click", () => {
        mostrarPaso(Number(btn.dataset.prev));
    });
});

function crearTarjetasTipo(info) {
    return info.tarjetas
        .map((tarjeta, index) => `
            <div class="preview-card">
                <span class="preview-card-index">0${index + 1}</span>
                <strong>${tarjeta}</strong>
                <p>Bloque visual adaptado a la narrativa del proyecto.</p>
            </div>
        `)
        .join("");
}

function crearBloquesFuncionalidades() {
    if (!configuracion.funcionalidades.length) {
        return `
            <div class="preview-empty">
                <strong>Aún no agregaste funcionalidades.</strong>
                <span>Podés volver al paso 3 y combinar los módulos que necesites.</span>
            </div>
        `;
    }

    return configuracion.funcionalidades
        .map(feature => {
            const detalle = funcionalidades[feature];

            return `
                <div class="feature-box">
                    <span class="feature-icon">${detalle.icono}</span>
                    <div>
                        <strong>${detalle.titulo}</strong>
                        <p>${detalle.descripcion}</p>
                    </div>
                </div>
            `;
        })
        .join("");
}

function crearBloquesFuncionalidadesExportados() {
    if (!configuracion.funcionalidades.length) {
        return "";
    }

    return configuracion.funcionalidades
        .map(feature => {
            if (feature === "chat") {
                return `
                    <section class="export-feature">
                        <div>
                            <span class="export-kicker">${funcionalidades.chat.icono} ${funcionalidades.chat.titulo}</span>
                            <h3>Atención en vivo</h3>
                            <p>${funcionalidades.chat.descripcion}</p>
                        </div>
                        <div class="export-chat" data-chat-widget hidden>
                            <div class="export-chat-log" data-chat-log>
                                <div class="export-chat-message bot">Hola, soy el asistente. Escribí tu consulta y te respondo al instante.</div>
                            </div>
                            <form class="export-chat-form" data-chat-form>
                                <input type="text" name="mensaje" placeholder="Escribí tu mensaje" autocomplete="off" required>
                                <button class="export-button" type="submit">Enviar</button>
                            </form>
                        </div>
                        <button class="export-button" type="button" data-chat-launcher>Abrir chat</button>
                    </section>
                `;
            }

            if (feature === "maps") {
                return `
                    <section class="export-feature">
                        <div>
                            <span class="export-kicker">${funcionalidades.maps.icono} ${funcionalidades.maps.titulo}</span>
                            <h3>Buscá y mostrás la ubicación</h3>
                            <p>${funcionalidades.maps.descripcion}</p>
                        </div>
                        <div class="export-maps-tools">
                            <input type="text" value="Buenos Aires, Argentina" data-maps-query placeholder="Ciudad, dirección o lugar">
                            <button class="export-button" type="button" data-open-maps>Buscar en Maps</button>
                        </div>
                        <iframe class="export-maps-frame" data-maps-frame title="Mapa" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Buenos%20Aires%2C%20Argentina&output=embed"></iframe>
                    </section>
                `;
            }

            if (feature === "contacto") {
                return `
                    <section class="export-feature">
                        <div>
                            <span class="export-kicker">${funcionalidades.contacto.icono} ${funcionalidades.contacto.titulo}</span>
                            <h3>Formulario con envío real</h3>
                            <p>${funcionalidades.contacto.descripcion}</p>
                        </div>
                        <form class="export-form" data-contact-form>
                            <input type="text" name="nombre" placeholder="Tu nombre" required>
                            <input type="email" name="email" placeholder="tu@email.com" required>
                            <input type="text" name="asunto" placeholder="Asunto" required>
                            <textarea name="mensaje" rows="4" placeholder="Contanos lo que necesitás" required></textarea>
                            <button class="export-button" type="submit">Enviar mensaje</button>
                            <span class="export-status" data-contact-status></span>
                        </form>
                    </section>
                `;
            }

            if (feature === "newsletter") {
                return `
                    <section class="export-feature">
                        <div>
                            <span class="export-kicker">${funcionalidades.newsletter.icono} ${funcionalidades.newsletter.titulo}</span>
                            <h3>Suscripción persistente</h3>
                            <p>${funcionalidades.newsletter.descripcion}</p>
                        </div>
                        <form class="export-form export-inline-form" data-newsletter-form>
                            <input type="email" name="newsletter" placeholder="Email para suscribirse" required>
                            <button class="export-button" type="submit">Suscribirme</button>
                            <span class="export-status" data-newsletter-status></span>
                        </form>
                    </section>
                `;
            }

            if (feature === "modo") {
                return `
                    <section class="export-feature" data-theme-surface>
                        <div>
                            <span class="export-kicker">${funcionalidades.modo.icono} ${funcionalidades.modo.titulo}</span>
                            <h3>Alternar tema visual</h3>
                            <p>${funcionalidades.modo.descripcion}</p>
                        </div>
                        <button class="export-button" type="button" data-theme-toggle>Activar modo oscuro</button>
                    </section>
                `;
            }

            return "";
        })
        .join("");
}

function crearScriptExportado() {
    const tieneModo = configuracion.funcionalidades.includes("modo");

    return `
        <script>
            (function () {
                const chat = document.querySelector("[data-chat-launcher]");
                const chatWidget = document.querySelector("[data-chat-widget]");
                const chatLog = document.querySelector("[data-chat-log]");
                const chatForm = document.querySelector("[data-chat-form]");
                if (chat) {
                    chat.addEventListener("click", () => {
                        chatWidget.hidden = !chatWidget.hidden;
                        if (!chatWidget.hidden) {
                            const input = chatForm ? chatForm.querySelector("input") : null;
                            if (input) {
                                input.focus();
                            }
                        }
                    });
                }

                function responderChat(mensaje) {
                    const texto = mensaje.toLowerCase();

                    if (texto.includes("precio") || texto.includes("costo") || texto.includes("valor")) {
                        return "Puedo ayudarte con precios y planes. Decime qué necesitás y lo vemos juntos.";
                    }

                    if (texto.includes("horario") || texto.includes("atención")) {
                        return "Nuestro horario de atención se adapta al proyecto. Si querés, dejá tu consulta y te respondemos pronto.";
                    }

                    if (texto.includes("contacto") || texto.includes("email") || texto.includes("correo")) {
                        return "También podés usar el formulario de contacto para enviarnos un mensaje directo.";
                    }

                    return "Recibí tu consulta. Contanos un poco más y te guiamos con la mejor opción.";
                }

                if (chatForm && chatLog) {
                    chatForm.addEventListener("submit", (event) => {
                        event.preventDefault();

                        const input = chatForm.querySelector("input[name='mensaje']");
                        const mensaje = input ? input.value.trim() : "";

                        if (!mensaje) {
                            return;
                        }

                        const userMessage = document.createElement("div");
                        userMessage.className = "export-chat-message user";
                        userMessage.textContent = mensaje;
                        chatLog.appendChild(userMessage);

                        const botMessage = document.createElement("div");
                        botMessage.className = "export-chat-message bot";
                        botMessage.textContent = responderChat(mensaje);

                        window.setTimeout(() => {
                            chatLog.appendChild(botMessage);
                            chatLog.scrollTop = chatLog.scrollHeight;
                        }, 250);

                        chatForm.reset();
                        chatLog.scrollTop = chatLog.scrollHeight;
                    });
                }

                const maps = document.querySelector("[data-open-maps]");
                const mapsQuery = document.querySelector("[data-maps-query]");
                const mapsFrame = document.querySelector("[data-maps-frame]");
                if (maps) {
                    maps.addEventListener("click", () => {
                        const query = mapsQuery && mapsQuery.value.trim() ? mapsQuery.value.trim() : "Buenos Aires, Argentina";
                        const mapsUrl = "https://www.google.com/maps?q=" + encodeURIComponent(query) + "&output=embed";

                        if (mapsFrame) {
                            mapsFrame.src = mapsUrl;
                        }

                        window.open("https://www.google.com/maps/search/?api=1&query=" + encodeURIComponent(query), "_blank", "noopener,noreferrer");
                    });
                }

                const contactForm = document.querySelector("[data-contact-form]");
                const contactStatus = document.querySelector("[data-contact-status]");
                if (contactForm) {
                    contactForm.addEventListener("submit", (event) => {
                        event.preventDefault();

                        const data = new FormData(contactForm);
                        const nombre = String(data.get("nombre") || "").trim();
                        const email = String(data.get("email") || "").trim();
                        const asunto = String(data.get("asunto") || "Consulta").trim();
                        const mensaje = String(data.get("mensaje") || "").trim();
                        const destino = "hola@tusitio.com";
                        const mailto = "mailto:" + destino + "?subject=" + encodeURIComponent(asunto) + "&body=" + encodeURIComponent(
                            "Nombre: " + nombre + "\nEmail: " + email + "\n\n" + mensaje
                        );

                        if (contactStatus) {
                            contactStatus.textContent = "Abriendo tu cliente de correo...";
                        }

                        window.location.href = mailto;
                        contactForm.reset();
                    });
                }

                const newsletterForm = document.querySelector("[data-newsletter-form]");
                const newsletterStatus = document.querySelector("[data-newsletter-status]");
                if (newsletterForm) {
                    newsletterForm.addEventListener("submit", (event) => {
                        event.preventDefault();

                        const input = newsletterForm.querySelector("input[name='newsletter']");
                        const email = input ? input.value.trim().toLowerCase() : "";

                        if (!email) {
                            return;
                        }

                        const storageKey = "newsletterSubscribers";
                        const current = JSON.parse(window.localStorage.getItem(storageKey) || "[]");

                        if (!current.includes(email)) {
                            current.push(email);
                            window.localStorage.setItem(storageKey, JSON.stringify(current));
                        }

                        if (newsletterStatus) {
                            newsletterStatus.textContent = "Suscripción guardada en este navegador.";
                        }

                        newsletterForm.reset();
                    });
                }

                const themeToggle = document.querySelector("[data-theme-toggle]");
                const themeSurface = document.querySelector("[data-theme-surface]");
                const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
                const savedTheme = window.localStorage.getItem("exportTheme") || (prefersDark ? "dark" : "light");

                if (savedTheme === "dark") {
                    document.body.classList.add("theme-dark");
                    if (themeToggle) {
                        themeToggle.textContent = "Volver a modo claro";
                    }
                }

                if (${tieneModo ? "true" : "false"} && themeToggle && themeSurface) {
                    themeToggle.addEventListener("click", () => {
                        const dark = !document.body.classList.contains("theme-dark");
                        document.body.classList.toggle("theme-dark", dark);
                        window.localStorage.setItem("exportTheme", dark ? "dark" : "light");
                        themeToggle.textContent = dark ? "Volver a modo claro" : "Activar modo oscuro";
                    });
                }
            })();
        <\/script>
    `;
}

function actualizarPreview() {
    const tipo = tipos[configuracion.tipo];
    const estilo = estilos[configuracion.estilo] || estilos.tecnologico;

    preview.className = `preview ${configuracion.estilo || "tecnologico"}`;

    if (!tipo) {
        preview.innerHTML = `
            <div class="preview-shell">
                <div class="preview-hero">
                    <span class="preview-badge">Elegí una base</span>
                    <h2>Vista previa</h2>
                    <p>Configurá opciones y presioná generar.</p>
                </div>
            </div>
        `;

        return;
    }

    preview.innerHTML = `
        <div class="preview-shell">
            <div class="preview-hero">
                <span class="preview-badge">${tipo.badge}</span>
                <h2>${tipo.titulo}</h2>
                <p>${tipo.subtitulo}</p>

                <div class="preview-tags">
                    <span class="preview-tag">Estilo ${estilo.titulo}</span>
                    <span class="preview-tag">${configuracion.funcionalidades.length} módulos</span>
                </div>
            </div>

            <div class="preview-layout">
                <section class="preview-main">
                    <div class="preview-topbar"></div>
                    <div class="preview-grid">
                        ${crearTarjetasTipo(tipo)}
                    </div>
                </section>

                <aside class="preview-aside">
                    <h3>Funcionalidades</h3>
                    ${crearBloquesFuncionalidades()}
                </aside>
            </div>
        </div>
    `;
}

function obtenerCssExportado() {
    return `
        :root{
            color-scheme: light;
        }

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            padding:0;
            font-family:'Quicksand',sans-serif;
            background:#f7f9ff;
            color:#1f2a44;
        }

        .preview{
            min-height:100vh;
            padding:32px;
            transition:.3s;
        }

        .preview-shell{
            max-width:1100px;
            margin:0 auto;
        }

        .preview-hero{
            padding:28px;
            border-radius:24px;
            background:rgba(255,255,255,.92);
            box-shadow:0 20px 45px rgba(15,23,42,.08);
            margin-bottom:24px;
        }

        .preview-badge,
        .preview-tag,
        .feature-icon,
        .preview-card-index{
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .preview-badge{
            padding:8px 14px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
            letter-spacing:.08em;
            text-transform:uppercase;
            background:rgba(59,130,246,.12);
            color:#2563eb;
        }

        .preview-hero h2{
            margin:16px 0 8px;
            font-size:40px;
            line-height:1.05;
        }

        .preview-hero p,
        .preview-card p,
        .feature-box p,
        .preview-empty span{
            margin:0;
            color:#516079;
            line-height:1.6;
        }

        .preview-tags{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-top:18px;
        }

        .preview-tag{
            padding:10px 14px;
            border-radius:999px;
            background:#eef4ff;
            color:#1f2a44;
            font-weight:700;
            font-size:14px;
        }

        .preview-layout{
            display:grid;
            grid-template-columns:1.35fr .85fr;
            gap:20px;
        }

        .preview-main,
        .preview-aside{
            border-radius:24px;
            background:rgba(255,255,255,.92);
            box-shadow:0 20px 45px rgba(15,23,42,.08);
            padding:22px;
        }

        .preview-topbar{
            height:12px;
            border-radius:999px;
            background:linear-gradient(90deg,rgba(37,99,235,.2),rgba(37,99,235,.05));
            margin-bottom:20px;
        }

        .preview-grid{
            display:grid;
            grid-template-columns:repeat(3,minmax(0,1fr));
            gap:14px;
        }

        .preview-card{
            padding:18px;
            border-radius:20px;
            background:#f8fbff;
            border:1px solid rgba(37,99,235,.1);
            min-height:150px;
        }

        .preview-card-index{
            width:40px;
            height:40px;
            border-radius:12px;
            margin-bottom:16px;
            font-weight:800;
            background:rgba(37,99,235,.12);
            color:#2563eb;
        }

        .preview-card strong,
        .feature-box strong,
        .preview-empty strong{
            display:block;
            margin-bottom:8px;
            font-size:18px;
            color:#1f2a44;
        }

        .preview-aside h3{
            margin:0 0 16px;
            font-size:22px;
        }

        .feature-box{
            display:flex;
            gap:14px;
            align-items:flex-start;
            padding:16px;
            border-radius:18px;
            background:#f8fbff;
            border:1px solid rgba(37,99,235,.1);
        }

        .feature-icon{
            width:42px;
            height:42px;
            flex:0 0 42px;
            border-radius:14px;
            background:rgba(37,99,235,.12);
            font-size:20px;
        }

        .preview-empty{
            padding:18px;
            border-radius:18px;
            background:#f8fbff;
            border:1px dashed rgba(37,99,235,.18);
        }

        .technologico .preview-badge,
        .technologico .feature-icon,
        .technologico .preview-card-index{ background:rgba(59,130,246,.12); color:#3b82f6; }
        .natural .preview-badge,
        .natural .feature-icon,
        .natural .preview-card-index{ background:rgba(34,197,94,.12); color:#22c55e; }
        .rustico .preview-badge,
        .rustico .feature-icon,
        .rustico .preview-card-index{ background:rgba(217,119,6,.12); color:#d97706; }
        .minimalista .preview-badge,
        .minimalista .feature-icon,
        .minimalista .preview-card-index{ background:rgba(71,85,105,.12); color:#475569; }
        .cyberpunk .preview-badge,
        .cyberpunk .feature-icon,
        .cyberpunk .preview-card-index{ background:rgba(236,72,153,.12); color:#ec4899; }

        .natural{ background:linear-gradient(180deg,#f5fff5,#edfdf0); }
        .rustico{ background:linear-gradient(180deg,#fff8f0,#fff1de); }
        .minimalista{ background:linear-gradient(180deg,#f8fafc,#eef2f7); }
        .cyberpunk{ background:linear-gradient(180deg,#14051f,#1f1034); color:#f8f9ff; }
        .cyberpunk .preview-hero,
        .cyberpunk .preview-main,
        .cyberpunk .preview-aside{ background:rgba(15,23,42,.92); color:#f8f9ff; }
        .cyberpunk .preview-hero p,
        .cyberpunk .preview-card p,
        .cyberpunk .feature-box p,
        .cyberpunk .preview-empty span{ color:#cbd5e1; }
        .cyberpunk .preview-tag,
        .cyberpunk .preview-card,
        .cyberpunk .feature-box,
        .cyberpunk .preview-empty{ background:rgba(15,23,42,.7); border-color:rgba(236,72,153,.22); }

        .export-feature{
            display:grid;
            gap:14px;
            margin-top:20px;
            padding:22px;
            border-radius:24px;
            background:rgba(255,255,255,.95);
            box-shadow:0 20px 45px rgba(15,23,42,.08);
        }

        .export-kicker{
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-size:13px;
            font-weight:800;
            letter-spacing:.04em;
            text-transform:uppercase;
            color:#2563eb;
        }

        .export-button{
            border:none;
            cursor:pointer;
            padding:14px 18px;
            border-radius:999px;
            background:#2563eb;
            color:#fff;
            font:inherit;
            font-weight:700;
        }

        .export-form{
            display:grid;
            gap:12px;
        }

        .export-status{
            min-height:20px;
            font-size:14px;
            color:#2563eb;
            font-weight:700;
        }

        .export-chat{
            display:grid;
            gap:12px;
            padding:16px;
            border-radius:18px;
            background:#f8fbff;
            border:1px solid rgba(37,99,235,.12);
        }

        .export-chat-log{
            display:grid;
            gap:10px;
            max-height:220px;
            overflow:auto;
            padding-right:4px;
        }

        .export-chat-message{
            padding:12px 14px;
            border-radius:16px;
            line-height:1.5;
            max-width:90%;
        }

        .export-chat-message.bot{
            background:#fff;
            border:1px solid rgba(37,99,235,.12);
        }

        .export-chat-message.user{
            justify-self:end;
            background:#2563eb;
            color:#fff;
        }

        .export-chat-form,
        .export-maps-tools{
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:10px;
            align-items:center;
        }

        .export-chat-form input,
        .export-maps-tools input{
            width:100%;
            padding:14px 16px;
            border-radius:16px;
            border:1px solid rgba(37,99,235,.14);
            background:#fff;
            color:#1f2a44;
            font:inherit;
        }

        .export-maps-frame{
            width:100%;
            min-height:260px;
            border:0;
            border-radius:18px;
            background:#fff;
        }

        .export-form input,
        .export-form textarea{
            width:100%;
            padding:14px 16px;
            border-radius:16px;
            border:1px solid rgba(37,99,235,.14);
            background:#fff;
            color:#1f2a44;
            font:inherit;
        }

        .export-inline-form{
            grid-template-columns:minmax(0,1fr) auto;
            align-items:center;
        }

        .export-dark{
            background:#111827;
            color:#f8fafc;
        }

        .theme-dark .preview-hero,
        .theme-dark .preview-main,
        .theme-dark .preview-aside,
        .theme-dark .export-feature,
        .theme-dark .export-chat,
        .theme-dark .export-maps-frame{
            background:rgba(15,23,42,.92);
            color:#f8fafc;
        }

        .theme-dark .export-chat-message.bot,
        .theme-dark .export-form input,
        .theme-dark .export-form textarea,
        .theme-dark .export-chat-form input,
        .theme-dark .export-maps-tools input{
            background:#0f172a;
            color:#f8fafc;
            border-color:rgba(56,189,248,.18);
        }

        .theme-dark .export-kicker,
        .theme-dark .export-status{
            color:#38bdf8;
        }

        .theme-dark .export-chat{
            border-color:rgba(56,189,248,.16);
        }

        .theme-dark .export-chat-message.user,
        .theme-dark .export-button{
            background:#38bdf8;
            color:#06243a;
        }

        @media (max-width: 900px){
            .preview-layout,
            .preview-grid{
                grid-template-columns:1fr;
            }

            .export-inline-form{
                grid-template-columns:1fr;
            }

            .preview-hero h2{
                font-size:32px;
            }
        }
    `;
}

document.getElementById("descargar").addEventListener("click", descargarProyecto);

function descargarProyecto() {
    const claseEstilo = configuracion.estilo || "tecnologico";
    const contenido = `
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>${tipos[configuracion.tipo]?.titulo || "mi-proyecto"}</title>
<style>
${obtenerCssExportado()}
</style>
</head>
<body>
<main class="preview ${claseEstilo}">
${preview.innerHTML}
${crearBloquesFuncionalidadesExportados()}
</main>
${crearScriptExportado()}
</body>
</html>
`;

    const blob = new Blob([contenido], { type: "text/html" });
    const enlace = document.createElement("a");

    enlace.href = URL.createObjectURL(blob);
    enlace.download = "mi-proyecto.html";
    enlace.click();

    URL.revokeObjectURL(enlace.href);
}

actualizarPreview();
resaltarSeleccion();
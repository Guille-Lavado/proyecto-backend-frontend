/**
 * TrainBike - Frontend Application Entry Point
 * V4: Manejador Global de Errores y Feedback Visual (Toasts)
 */

const API_BASE_URL = '/api'; 
const appContainer = document.getElementById('app-container');
const mainNav = document.getElementById('mainNav');
const btnLogout = document.getElementById('btnLogout');

/* ==========================================
 * MÓDULO: UI FEEDBACK (TOASTS NATIVOS DOM)
 * ========================================== */

/**
 * Muestra una notificación Toast utilizando estrictamente la API del DOM
 * sin usar innerHTML para evitar inyecciones XSS.
 * @param {string} message - El mensaje a mostrar
 * @param {string} type - 'success', 'danger', 'warning', 'info'
 */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Crear elementos
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${type} border-0 mb-2 fade-in`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');

    const flexDiv = document.createElement('div');
    flexDiv.className = 'd-flex';

    const bodyDiv = document.createElement('div');
    bodyDiv.className = 'toast-body';
    bodyDiv.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'btn-close btn-close-white me-2 m-auto';
    closeBtn.setAttribute('data-bs-dismiss', 'toast');
    closeBtn.setAttribute('aria-label', 'Close');

    // Ensamblar DOM de forma segura
    flexDiv.appendChild(bodyDiv);
    flexDiv.appendChild(closeBtn);
    toastEl.appendChild(flexDiv);
    container.appendChild(toastEl);

    // Inicializar el Toast con Bootstrap
    // Se requiere tener el script de Bootstrap bundle cargado en el HTML
    const bsToast = new bootstrap.Toast(toastEl, { delay: 3500 });
    bsToast.show();

    // Limpiar el DOM cuando se oculte
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}

/* ==========================================
 * MÓDULO: FETCH WRAPPER (INTERCEPTOR)
 * ========================================== */

async function fetchAPI(endpoint, options = {}) {
    const token = localStorage.getItem('auth_token');
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
    };

    if (token) headers['Authorization'] = `Bearer ${token}`;

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, { ...options, headers });
        
        if (response.status === 401 && endpoint !== '/login' && endpoint !== '/register') {
            showToast("Sesión expirada. Por favor, vuelve a iniciar sesión.", "warning");
            forceLogout();
            throw new Error("Sesión expirada");
        }

        return response;
    } catch (error) {
        console.error(`Error en fetchAPI [${endpoint}]:`, error);
        throw error; 
    }
}

/* ==========================================
 * MÓDULO: INICIALIZACIÓN Y ESTADO CENTRAL
 * ========================================== */

function initApp() {
    const token = localStorage.getItem('auth_token');

    if (!token) {
        mainNav.classList.add('d-none');
        renderLogin();
    } else {
        mainNav.classList.remove('d-none');
        setupNavigation();
        renderPlaceholder("Bienvenido a TrainBike. Selecciona una opción del menú.");
    }
}

function clearAppContainer() {
    appContainer.replaceChildren(); 
}

/* ==========================================
 * MÓDULO: AUTENTICACIÓN (LOGIN, REGISTER, LOGOUT)
 * ========================================== */

btnLogout.replaceWith(btnLogout.cloneNode(true)); 
document.getElementById('btnLogout').addEventListener('click', handleLogout);

async function handleLogout() {
    try {
        await fetchAPI('/logout', { method: 'POST' });
        showToast("Has cerrado sesión correctamente.", "info");
    } catch (error) {
        console.warn("Logout local ejecutado.");
    } finally {
        forceLogout();
    }
}

function forceLogout() {
    localStorage.removeItem('auth_token');
    initApp();
}

function renderLogin() {
    clearAppContainer();
    const template = document.getElementById('tpl-login');
    const clone = template.content.cloneNode(true);

    clone.getElementById('link-register').addEventListener('click', (e) => {
        e.preventDefault();
        renderRegister();
    });

    clone.getElementById('form-login').addEventListener('submit', handleLoginSubmit);
    appContainer.appendChild(clone);
}

function renderRegister() {
    clearAppContainer();
    const template = document.getElementById('tpl-register');
    const clone = template.content.cloneNode(true);

    clone.getElementById('link-login').addEventListener('click', (e) => {
        e.preventDefault();
        renderLogin();
    });

    clone.getElementById('form-register').addEventListener('submit', handleRegisterSubmit);
    appContainer.appendChild(clone);
}

async function handleLoginSubmit(e) {
    e.preventDefault();
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();

        if (response.ok && data.access_token) {
            localStorage.setItem('auth_token', data.access_token);
            showToast(`¡Bienvenido de nuevo!`, "success");
            initApp();
        } else {
            showToast(data.message || 'Error en credenciales.', "danger");
        }
    } catch (error) {
        showToast("Error de conexión con el servidor.", "danger");
    }
}

async function handleRegisterSubmit(e) {
    e.preventDefault();
    const payload = {
        nombre: document.getElementById('reg-nombre').value,
        apellidos: document.getElementById('reg-apellidos').value,
        email: document.getElementById('reg-email').value,
        password: document.getElementById('reg-password').value,
        fecha_nacimiento: document.getElementById('reg-fecha').value,
        peso: parseFloat(document.getElementById('reg-peso').value),
        altura: parseFloat(document.getElementById('reg-altura').value)
    };

    try {
        const response = await fetch(`${API_BASE_URL}/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();

        if (response.ok && data.access_token) {
            localStorage.setItem('auth_token', data.access_token);
            showToast("¡Cuenta creada con éxito!", "success");
            initApp();
        } else {
            showToast(data.message || 'Error en el registro. Revisa los datos.', "danger");
        }
    } catch (error) {
        showToast("Error de conexión con el servidor.", "danger");
    }
}

/* ==========================================
 * MÓDULO: NAVEGACIÓN Y ENRUTAMIENTO SPA
 * ========================================== */

function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const newLink = link.cloneNode(true);
        link.parentNode.replaceChild(newLink, link);
        
        newLink.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            e.target.classList.add('active');
            
            const view = e.target.getAttribute('data-view');
            router(view); // Llamamos a nuestro enrutador básico
        });
    });
}

/**
 * Enrutador básico de la aplicación SPA
 */
function router(view) {
    switch(view) {
        case 'bloques':
            renderBloquesList();
            break;
        case 'planes':
        case 'sesiones':
        case 'resultados':
        case 'sesion-plan':
            renderPlaceholder(`Vista en construcción: ${view.toUpperCase()}`);
            break;
        default:
            renderPlaceholder("Bienvenido a TrainBike. Selecciona una opción del menú.");
    }
}

/* ==========================================
 * MÓDULO: VISTAS DE BLOQUES DE ENTRENAMIENTO
 * ========================================== */

/**
 * Renderiza la interfaz base para el listado de bloques.
 */
function renderBloquesList() {
    clearAppContainer();
    const template = document.getElementById('tpl-bloques-list');
    const clone = template.content.cloneNode(true);

    const btnNuevo = clone.getElementById('btn-nuevo-bloque');
    // CAMBIO AQUÍ: Ahora llama a la nueva función en lugar del Toast
    btnNuevo.addEventListener('click', () => {
        renderBloqueForm(); 
    });

    appContainer.appendChild(clone);
    fetchBloques();
}

/**
 * Petición asíncrona para obtener los bloques del usuario.
 */
async function fetchBloques() {
    try {
        const response = await fetchAPI('/bloque');
        const bloques = await response.json();
        
        // Llamamos a la función encargada de construir los nodos
        renderBloquesRows(bloques);
    } catch (error) {
        const tbody = document.getElementById('bloques-tbody');
        if (tbody) {
            tbody.replaceChildren(); // Vaciamos el estado de carga
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 5;
            td.className = 'text-center text-danger py-4';
            td.textContent = 'Error al cargar los datos del servidor. Inténtalo de nuevo.';
            tr.appendChild(td);
            tbody.appendChild(tr);
        }
    }
}

/**
 * Construye dinámicamente las filas de la tabla de bloques usando la API del DOM.
 * 0% HTML Injection.
 * @param {Array} bloques - Array de objetos de bloques
 */
function renderBloquesRows(bloques) {
    const tbody = document.getElementById('bloques-tbody');
    if (!tbody) return;

    // Limpiamos la fila de "Cargando..."
    tbody.replaceChildren();

    // Caso: No hay datos
    if (!Array.isArray(bloques) || bloques.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 5;
        td.className = 'text-center text-muted py-4';
        td.textContent = 'No tienes bloques de entrenamiento registrados. ¡Crea el primero!';
        tr.appendChild(td);
        tbody.appendChild(tr);
        return;
    }

    // Caso: Hay datos, iteramos para construir las filas
    bloques.forEach(bloque => {
        const tr = document.createElement('tr');

        // Columna 1: Nombre y Descripción
        const tdNombre = document.createElement('td');
        const strongNombre = document.createElement('strong');
        strongNombre.textContent = bloque.nombre;
        tdNombre.appendChild(strongNombre);
        
        if (bloque.descripcion) {
            const br = document.createElement('br');
            const smallDesc = document.createElement('small');
            smallDesc.className = 'text-muted';
            // Truncamos la descripción si es muy larga
            smallDesc.textContent = bloque.descripcion.length > 50 
                ? bloque.descripcion.substring(0, 50) + '...' 
                : bloque.descripcion;
            tdNombre.appendChild(br);
            tdNombre.appendChild(smallDesc);
        }
        tr.appendChild(tdNombre);

        // Columna 2: Tipo
        const tdTipo = document.createElement('td');
        const badgeTipo = document.createElement('span');
        badgeTipo.className = 'badge bg-secondary';
        badgeTipo.textContent = bloque.tipo || 'General';
        tdTipo.appendChild(badgeTipo);
        tr.appendChild(tdTipo);

        // Columna 3: Duración Estimada
        const tdDuracion = document.createElement('td');
        tdDuracion.textContent = bloque.duracion_estimada ? `${bloque.duracion_estimada} min` : '-';
        tr.appendChild(tdDuracion);

        // Columna 4: Zonas (Potencia / Pulso)
        const tdZonas = document.createElement('td');
        const smallZonas = document.createElement('small');
        let txtZonas = '';
        if (bloque.potencia_pct_min && bloque.potencia_pct_max) {
            txtZonas += `${bloque.potencia_pct_min}% - ${bloque.potencia_pct_max}% FTP  `;
        }
        if (bloque.pulso_pct_max) {
            txtZonas += `Max: ${bloque.pulso_pct_max}%`;
        }
        smallZonas.textContent = txtZonas || 'Sin definir';
        tdZonas.appendChild(smallZonas);
        tr.appendChild(tdZonas);

        // Columna 5: Botones de Acción
        const tdAcciones = document.createElement('td');
        tdAcciones.className = 'text-end';
        
        const btnGroup = document.createElement('div');
        btnGroup.className = 'btn-group btn-group-sm';

        const btnVer = document.createElement('button');
        btnVer.className = 'btn btn-outline-info';
        btnVer.textContent = 'Ver';
        btnVer.addEventListener('click', () => {
            showToast(`Detalles del bloque (ID: ${bloque.id}) en construcción.`, "info");
        });

        const btnEliminar = document.createElement('button');
        btnEliminar.className = 'btn btn-outline-danger';
        btnEliminar.textContent = 'Borrar';
        btnEliminar.addEventListener('click', () => {
            showToast(`Lógica de eliminación (ID: ${bloque.id}) en la V9.`, "warning");
        });

        btnGroup.appendChild(btnVer);
        btnGroup.appendChild(btnEliminar);
        tdAcciones.appendChild(btnGroup);
        tr.appendChild(tdAcciones);

        // Finalmente, añadimos la fila completa al tbody
        tbody.appendChild(tr);
    });
}

/**
 * Renderiza el formulario para crear un nuevo Bloque de Entrenamiento.
 */
function renderBloqueForm() {
    clearAppContainer();
    const template = document.getElementById('tpl-bloque-form');
    const clone = template.content.cloneNode(true);

    // Botón para volver al listado sin guardar
    const btnCancelar = clone.getElementById('btn-cancelar-bloque');
    btnCancelar.addEventListener('click', () => {
        renderBloquesList();
    });

    // Capturar el evento Submit del formulario
    const formBloque = clone.getElementById('form-form-bloque') || clone.getElementById('form-bloque');
    formBloque.addEventListener('submit', (e) => {
        e.preventDefault(); // Evitamos que el navegador recargue la página
        
        // El navegador ya ha validado los 'required', 'min' y 'max' por nosotros
        showToast("Formulario validado correctamente. En la V8 lo enviaremos a la API.", "success");
        
        // Aquí llamaremos a handleBloqueSubmit() en la V8
    });

    appContainer.appendChild(clone);
}

/* ==========================================
 * UTILIDADES COMUNES
 * ========================================== */

function renderPlaceholder(text) {
    clearAppContainer();
    const heading = document.createElement('h3');
    heading.className = 'text-center mt-5 text-secondary fade-in';
    heading.textContent = text;
    appContainer.appendChild(heading);
}

document.addEventListener('DOMContentLoaded', initApp);
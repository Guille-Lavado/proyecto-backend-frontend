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

// Variable global para almacenar nuestro observador
let sesionesObserver = null;

function clearAppContainer() {
    // Si hay un observador activo, lo desconectamos antes de cambiar de vista
    if (sesionesObserver) {
        sesionesObserver.disconnect();
        sesionesObserver = null;
    }
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
            renderPlanesList();
            break;
        case 'sesiones':
            renderSesionesList(); // NUEVO: Llamamos a la vista de sesiones
            break;
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
        // CAMBIO: Ahora llama a la nueva función fetchBloqueDetalle
        btnVer.addEventListener('click', () => {
            fetchBloqueDetalle(bloque.id);
        });

        const btnEliminar = document.createElement('button');
        btnEliminar.className = 'btn btn-outline-danger';
        btnEliminar.textContent = 'Borrar';
        // CAMBIO: Ahora llama a la nueva función deleteBloque
        btnEliminar.addEventListener('click', () => {
            deleteBloque(bloque.id, bloque.nombre);
        });
        // ...

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

    // Capturar el evento Submit del formulario y conectarlo a nuestra lógica
    const formBloque = clone.getElementById('form-bloque');
    formBloque.addEventListener('submit', handleBloqueSubmit);

    appContainer.appendChild(clone);
}

/**
 * Procesa el envío del formulario de Bloques, construyendo el JSON
 * y realizando la petición POST a la API.
 */
async function handleBloqueSubmit(e) {
    e.preventDefault(); // Evitamos la recarga

    // Helper para no enviar strings vacíos como si fueran números a la BBDD
    const getIntOrNull = (id) => {
        const val = document.getElementById(id).value;
        return val ? parseInt(val) : null;
    };

    // Construimos el payload (cuerpo de la petición) mapeando los IDs del HTML
    // a los nombres de columna de la base de datos
    const payload = {
        nombre: document.getElementById('bq-nombre').value,
        tipo: document.getElementById('bq-tipo').value,
        descripcion: document.getElementById('bq-descripcion').value || null,
        duracion_estimada: getIntOrNull('bq-duracion'),
        potencia_pct_min: getIntOrNull('bq-pot-min'),
        potencia_pct_max: getIntOrNull('bq-pot-max'),
        pulso_pct_max: getIntOrNull('bq-pulso-max'),
        pulso_reserva_pct: getIntOrNull('bq-pulso-res'),
        comentario: document.getElementById('bq-comentario').value || null
    };

    try {
        // Mostramos feedback de carga visual si queremos (opcional)
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const textOriginal = btnSubmit.textContent;
        btnSubmit.textContent = 'Guardando...';
        btnSubmit.disabled = true;

        // Utilizamos nuestro interceptor para enviar la petición con el token
        const response = await fetchAPI('/bloque/crear', {
            method: 'POST',
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok) {
            showToast("¡Bloque de entrenamiento creado con éxito!", "success");
            // Reactividad: Volvemos automáticamente al listado
            // renderBloquesList() se encargará de hacer el GET y mostrar el nuevo bloque
            renderBloquesList(); 
        } else {
            // Manejo de errores de validación del backend (ej: código 422)
            console.error("Errores de validación:", data.errors);
            showToast(data.message || 'Error al guardar el bloque. Revisa los datos.', "danger");
            
            // Restauramos el botón
            btnSubmit.textContent = textOriginal;
            btnSubmit.disabled = false;
        }
    } catch (error) {
        showToast("Error de red al conectar con el servidor.", "danger");
    }
}

/**
 * Obtiene los detalles de un bloque específico por su ID.
 */
async function fetchBloqueDetalle(id) {
    try {
        const response = await fetchAPI(`/bloque/${id}`);
        const bloque = await response.json();
        
        if (response.ok) {
            renderBloqueDetalle(bloque);
        } else {
            showToast("No se pudo cargar el bloque.", "danger");
        }
    } catch (error) {
        showToast("Error al obtener los detalles del servidor.", "danger");
    }
}

/**
 * Renderiza la vista de detalle mapeando los datos de forma segura.
 */
function renderBloqueDetalle(bloque) {
    clearAppContainer();
    const template = document.getElementById('tpl-bloque-detalle');
    const clone = template.content.cloneNode(true);

    // Botón volver
    clone.getElementById('btn-volver-detalle').addEventListener('click', () => {
        renderBloquesList();
    });

    // Inyección segura de datos con textContent
    clone.getElementById('det-nombre').textContent = bloque.nombre;
    clone.getElementById('det-tipo').textContent = bloque.tipo || 'General';
    clone.getElementById('det-desc').textContent = bloque.descripcion || 'Sin descripción';
    
    clone.getElementById('det-duracion').textContent = bloque.duracion_estimada 
        ? `${bloque.duracion_estimada} minutos` 
        : 'No especificada';

    // Formatear Potencia
    let txtPotencia = 'No definidas';
    if (bloque.potencia_pct_min && bloque.potencia_pct_max) {
        txtPotencia = `Entre ${bloque.potencia_pct_min}% y ${bloque.potencia_pct_max}% del FTP`;
    }
    clone.getElementById('det-potencia').textContent = txtPotencia;

    // Formatear Pulso
    let txtPulso = 'No definidas';
    if (bloque.pulso_pct_max) {
        txtPulso = `Hasta ${bloque.pulso_pct_max}% de la Frecuencia Cardíaca Máxima`;
    }
    clone.getElementById('det-pulso').textContent = txtPulso;

    clone.getElementById('det-comentario').textContent = bloque.comentario || 'Ninguno';

    appContainer.appendChild(clone);
}

/**
 * Lógica para eliminar un bloque tras confirmación del usuario.
 */
async function deleteBloque(id, nombre) {
    // Usamos el confirm nativo del navegador para evitar borrados accidentales
    const seguro = confirm(`¿Estás completamente seguro de que deseas eliminar el bloque "${nombre}"?\nEsta acción no se puede deshacer.`);
    
    if (!seguro) return;

    try {
        // Ejecutamos la petición con el método DELETE a la ruta exigida
        const response = await fetchAPI(`/bloque/${id}/eliminar`, {
            method: 'DELETE'
        });

        if (response.ok) {
            showToast(`Bloque "${nombre}" eliminado.`, "success");
            // Recargamos el listado para que desaparezca de la tabla visualmente
            renderBloquesList();
        } else {
            const data = await response.json();
            showToast(data.message || "Error al intentar eliminar el bloque.", "danger");
        }
    } catch (error) {
        showToast("Error de conexión al intentar eliminar.", "danger");
    }
}

/* ==========================================
 * MÓDULO: VISTAS DE PLANES DE ENTRENAMIENTO
 * ========================================== */

function renderPlanesList() {
    clearAppContainer();
    const template = document.getElementById('tpl-planes-list');
    const clone = template.content.cloneNode(true);

    clone.getElementById('btn-nuevo-plan').addEventListener('click', () => {
        renderPlanForm(); // Llamamos sin argumentos = Modo Creación
    });

    appContainer.appendChild(clone);
    fetchPlanes();
}

async function fetchPlanes() {
    try {
        const response = await fetchAPI('/plan');
        const planes = await response.json();
        renderPlanesRows(planes);
    } catch (error) {
        showToast("Error al obtener los planes.", "danger");
    }
}

function renderPlanesRows(planes) {
    const tbody = document.getElementById('planes-tbody');
    if (!tbody) return;
    tbody.replaceChildren();

    if (!Array.isArray(planes) || planes.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 5;
        td.className = 'text-center text-muted py-4';
        td.textContent = 'No hay planes registrados.';
        tr.appendChild(td);
        tbody.appendChild(tr);
        return;
    }

    planes.forEach(plan => {
        const tr = document.createElement('tr');

        // Nombre
        const tdNombre = document.createElement('td');
        const strongNombre = document.createElement('strong');
        strongNombre.textContent = plan.nombre;
        tdNombre.appendChild(strongNombre);
        tr.appendChild(tdNombre);

        // Fechas
        const tdFechas = document.createElement('td');
        // Extraemos solo la parte YYYY-MM-DD en caso de que vengan con horas
        const fInicio = plan.fecha_inicio ? plan.fecha_inicio.split(' ')[0] : '';
        const fFin = plan.fecha_fin ? plan.fecha_fin.split(' ')[0] : '';
        tdFechas.textContent = `${fInicio} a ${fFin}`;
        tr.appendChild(tdFechas);

        // Objetivo
        const tdObj = document.createElement('td');
        tdObj.textContent = plan.objetivo || '-';
        tr.appendChild(tdObj);

        // Estado
        const tdEstado = document.createElement('td');
        const badge = document.createElement('span');
        const activo = (plan.activo == 1 || plan.activo === true);
        badge.className = activo ? 'badge bg-success' : 'badge bg-secondary';
        badge.textContent = activo ? 'Activo' : 'Inactivo';
        tdEstado.appendChild(badge);
        tr.appendChild(tdEstado);

        // Acciones
        const tdAcciones = document.createElement('td');
        tdAcciones.className = 'text-end';
        
        const btnEditar = document.createElement('button');
        btnEditar.className = 'btn btn-sm btn-outline-warning me-1';
        btnEditar.textContent = 'Editar';
        btnEditar.addEventListener('click', () => {
            renderPlanForm(plan); // Pasamos el objeto plan = Modo Edición
        });

        const btnBorrar = document.createElement('button');
        btnBorrar.className = 'btn btn-sm btn-outline-danger';
        btnBorrar.textContent = 'Borrar';
        btnBorrar.addEventListener('click', () => {
            deletePlan(plan.id, plan.nombre);
        });

        tdAcciones.appendChild(btnEditar);
        tdAcciones.appendChild(btnBorrar);
        tr.appendChild(tdAcciones);

        tbody.appendChild(tr);
    });
}

/**
 * Renderiza el formulario de Planes.
 * @param {Object|null} plan - Si se pasa un plan, rellena los datos (Modo Edición).
 */
function renderPlanForm(plan = null) {
    clearAppContainer();
    const template = document.getElementById('tpl-plan-form');
    const clone = template.content.cloneNode(true);

    // Adaptamos el título dinámicamente
    const title = clone.querySelector('h2');
    title.textContent = plan ? '✏️ Editar Plan' : '📝 Nuevo Plan';

    // Si estamos editando, rellenamos los inputs del DOM
    if (plan) {
        clone.getElementById('pl-nombre').value = plan.nombre;
        clone.getElementById('pl-fecha-inicio').value = plan.fecha_inicio.split(' ')[0];
        clone.getElementById('pl-fecha-fin').value = plan.fecha_fin.split(' ')[0];
        clone.getElementById('pl-objetivo').value = plan.objetivo || '';
        clone.getElementById('pl-descripcion').value = plan.descripcion || '';
        clone.getElementById('pl-activo').checked = (plan.activo == 1 || plan.activo === true);
    }

    clone.getElementById('btn-cancelar-plan').addEventListener('click', () => {
        renderPlanesList();
    });

    const form = clone.getElementById('form-plan');
    // Pasamos el ID al handler. Si es null, será un POST. Si hay ID, será un PUT.
    form.addEventListener('submit', (e) => handlePlanSubmit(e, plan ? plan.id : null));

    appContainer.appendChild(clone);
}

/**
 * Procesa la creación (POST) o actualización (PUT) del plan.
 */
async function handlePlanSubmit(e, planId = null) {
    e.preventDefault();

    const payload = {
        id_ciclista: 1, // Mantenido por si tu validador base lo exige
        nombre: document.getElementById('pl-nombre').value,
        descripcion: document.getElementById('pl-descripcion').value || null,
        fecha_inicio: document.getElementById('pl-fecha-inicio').value,
        fecha_fin: document.getElementById('pl-fecha-fin').value,
        objetivo: document.getElementById('pl-objetivo').value || null,
        activo: document.getElementById('pl-activo').checked ? 1 : 0
    };

    // Lógica dinámica de endpoints según los requisitos del PDF
    const method = planId ? 'PUT' : 'POST';
    const endpoint = planId ? `/plan/${planId}` : '/plan/crear';

    try {
        const response = await fetchAPI(endpoint, {
            method: method,
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok) {
            showToast(planId ? "Plan actualizado con éxito" : "Plan creado correctamente", "success");
            renderPlanesList();
        } else {
            console.error("Errores:", data.errors);
            showToast(data.message || 'Error al guardar el plan.', "danger");
        }
    } catch (error) {
        showToast("Error de conexión.", "danger");
    }
}

/**
 * Elimina un plan tras confirmación.
 */
async function deletePlan(id, nombre) {
    const seguro = confirm(`¿Eliminar definitivamente el plan "${nombre}"?`);
    if (!seguro) return;

    try {
        const response = await fetchAPI(`/plan/${id}`, { method: 'DELETE' });

        if (response.ok) {
            showToast(`Plan "${nombre}" eliminado.`, "success");
            renderPlanesList();
        } else {
            const data = await response.json();
            showToast(data.message || "Error al eliminar el plan.", "danger");
        }
    } catch (error) {
        showToast("Error de conexión.", "danger");
    }
}

/* ==========================================
 * MÓDULO: SESIONES DE ENTRENAMIENTO Y SCROLL INFINITO
 * ========================================== */

// Variables de estado para la paginación
let sesionesOffset = 0;
let sesionesLimit = 10;
let isFetchingSesiones = false;
let hasMoreSesiones = true;

/**
 * Renderiza la interfaz principal y reinicia el estado del scroll infinito.
 */
function renderSesionesList() {
    clearAppContainer();
    const template = document.getElementById('tpl-sesiones-list');
    const clone = template.content.cloneNode(true);

    clone.getElementById('btn-nueva-sesion').addEventListener('click', () => {
        showToast("Formulario en la V15", "info");
    });

    appContainer.appendChild(clone);

    // 1. Reiniciamos el estado cada vez que entramos a la vista
    sesionesOffset = 0;
    isFetchingSesiones = false;
    hasMoreSesiones = true;

    // 2. Inicializamos el observador que vigilará el final de la página
    initIntersectionObserver();

    // 3. Hacemos la primera carga forzada (V14)
    // Por ahora, llamamos a nuestra función simulada
    fetchSesionesPaginadas();
}

/**
 * Configura el IntersectionObserver para vigilar el #scroll-sentinel.
 */
function initIntersectionObserver() {
    const sentinel = document.getElementById('scroll-sentinel');
    if (!sentinel) return;

    // Opciones del observador
    const options = {
        root: null, // null = vigila el viewport (la ventana del navegador)
        rootMargin: '0px', 
        threshold: 0.1 // Se dispara cuando el 10% del centinela es visible
    };

    // Callback que se ejecuta cuando el centinela entra o sale de la pantalla
    const handleIntersect = (entries) => {
        const entry = entries[0];
        
        // Si el centinela es visible, no estamos ya cargando, y quedan sesiones en la BBDD
        if (entry.isIntersecting && !isFetchingSesiones && hasMoreSesiones) {
            console.log("¡Centinela a la vista! Cargando más sesiones...");
            fetchSesionesPaginadas();
        }
    };

    // Instanciamos el observador global
    sesionesObserver = new IntersectionObserver(handleIntersect, options);
    
    // Le decimos que empiece a vigilar nuestro div invisible
    sesionesObserver.observe(sentinel);
}

/**
 * Realiza la petición GET paginada a la API y gestiona el flujo del scroll.
 */
async function fetchSesionesPaginadas() {
    // Evitamos peticiones dobles o si ya hemos llegado al final
    if (isFetchingSesiones || !hasMoreSesiones) return;
    
    isFetchingSesiones = true;

    const sentinel = document.getElementById('scroll-sentinel');
    const endMessage = document.getElementById('no-more-sesiones');

    // Mostramos el spinner mientras esperamos a la red (excepto en la primera carga si queremos que sea limpia)
    if (sesionesOffset > 0 && sentinel) {
        sentinel.classList.remove('d-none');
    }

    try {
        // Ejecutamos la petición GET con los parámetros de consulta (Query Parameters) exigidos
        const endpoint = `/sesion?offset=${sesionesOffset}&limit=${sesionesLimit}`;
        const response = await fetchAPI(endpoint);
        const sesiones = await response.json();

        if (response.ok) {
            // Caso A: El backend nos devuelve un array vacío. Significa que ya no hay más datos.
            if (sesiones.length === 0) {
                hasMoreSesiones = false;
                if (sentinel) sentinel.classList.add('d-none');
                
                // Si el offset es 0, es que el usuario no tiene ninguna sesión en total
                if (sesionesOffset === 0) {
                    const container = document.getElementById('sesiones-container');
                    const div = document.createElement('div');
                    div.className = 'col-12 text-center text-muted py-5 mt-4';
                    div.textContent = 'No tienes ninguna sesión de entrenamiento planificada.';
                    container.appendChild(div);
                } else {
                    // Si ya había cargado algo antes, mostramos el mensaje de "No hay más"
                    if (endMessage) endMessage.classList.remove('d-none');
                }
            } 
            // Caso B: Recibimos datos, los pintamos.
            else {
                renderSesionesCards(sesiones);
                
                // Actualizamos el puntero para el próximo scroll
                sesionesOffset += sesionesLimit;

                // Optimización: Si el servidor nos devolvió menos sesiones del límite que pedimos, 
                // matemáticamente sabemos que es la última página.
                if (sesiones.length < sesionesLimit) {
                    hasMoreSesiones = false;
                    if (sentinel) sentinel.classList.add('d-none');
                    if (endMessage) endMessage.classList.remove('d-none');
                }
            }
        } else {
            showToast("Error al cargar las sesiones desde el servidor.", "danger");
            if (sentinel) sentinel.classList.add('d-none');
        }
    } catch (error) {
        console.error("Error en Scroll Infinito:", error);
        showToast("Error de conexión al intentar cargar más datos.", "danger");
        if (sentinel) sentinel.classList.add('d-none');
    } finally {
        // Liberamos el cerrojo para permitir futuras peticiones
        isFetchingSesiones = false;
    }
}

/**
 * Recibe un array de sesiones y genera tarjetas (Cards) inyectándolas en la cuadrícula.
 * Uso estricto de la API del DOM para evitar XSS.
 */
function renderSesionesCards(sesiones) {
    const container = document.getElementById('sesiones-container');
    if (!container) return;

    sesiones.forEach(sesion => {
        // Columna del Grid de Bootstrap
        const col = document.createElement('div');
        col.className = 'col-12 col-md-6 col-lg-4';

        // Tarjeta principal
        const card = document.createElement('div');
        card.className = 'card h-100 shadow-sm border-0 fade-in';

        // Cuerpo de la tarjeta
        const cardBody = document.createElement('div');
        cardBody.className = 'card-body pb-2';

        // Fila interna para Título y Badge de Estado
        const headerRow = document.createElement('div');
        headerRow.className = 'd-flex justify-content-between align-items-start mb-2';

        const title = document.createElement('h5');
        title.className = 'card-title fw-bold text-dark mb-0';
        title.textContent = sesion.nombre;

        const badge = document.createElement('span');
        const completada = (sesion.completada == 1 || sesion.completada === true);
        badge.className = completada ? 'badge bg-success' : 'badge bg-warning text-dark';
        badge.textContent = completada ? 'Completada' : 'Pendiente';

        headerRow.appendChild(title);
        headerRow.appendChild(badge);

        // Fecha
        const dateSub = document.createElement('h6');
        dateSub.className = 'card-subtitle mb-3 text-primary small fw-bold';
        dateSub.textContent = `📅 ${sesion.fecha ? sesion.fecha.split(' ')[0] : 'Sin fecha'}`;

        // Descripción
        const desc = document.createElement('p');
        desc.className = 'card-text text-muted small mb-0';
        desc.textContent = sesion.descripcion || 'Sin descripción registrada.';

        // Ensamblaje del cuerpo
        cardBody.appendChild(headerRow);
        cardBody.appendChild(dateSub);
        cardBody.appendChild(desc);

        // Footer con acciones (Se implementará Borrado en la V15)
        const cardFooter = document.createElement('div');
        cardFooter.className = 'card-footer bg-transparent border-0 pt-0 text-end';
        
        const btnEliminar = document.createElement('button');
        btnEliminar.className = 'btn btn-sm btn-outline-danger';
        btnEliminar.textContent = 'Eliminar';
        btnEliminar.addEventListener('click', () => {
            showToast(`La eliminación (ID: ${sesion.id}) se programará en la V15`, "warning");
        });

        cardFooter.appendChild(btnEliminar);

        // Ensamblaje final de la tarjeta
        card.appendChild(cardBody);
        card.appendChild(cardFooter);
        col.appendChild(card);
        
        // Inyectamos la columna en el contenedor principal del grid
        container.appendChild(col);
    });
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
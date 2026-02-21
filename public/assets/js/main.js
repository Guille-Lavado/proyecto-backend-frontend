/**
 * TrainBike - Frontend Application Entry Point
 * V3: Interceptor Fetch (Token Auth) y Cierre de Sesión (Logout)
 */

const API_BASE_URL = '/api'; 
const appContainer = document.getElementById('app-container');
const mainNav = document.getElementById('mainNav');
const btnLogout = document.getElementById('btnLogout'); // Referencia al botón del menú

/* ==========================================
 * MÓDULO: FETCH WRAPPER (INTERCEPTOR)
 * ========================================== */

/**
 * Envoltorio para la API fetch que inyecta automáticamente
 * el token de autorización en las cabeceras si existe.
 */
async function fetchAPI(endpoint, options = {}) {
    const token = localStorage.getItem('auth_token');
    
    // Configuramos las cabeceras base requeridas por Laravel
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
    };

    // Si hay token, lo inyectamos en formato Bearer 
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        ...options,
        headers
    };

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        
        // Si el servidor responde 401 (No Autorizado) y no es login/registro, forzamos cierre
        if (response.status === 401 && endpoint !== '/login' && endpoint !== '/register') {
            console.warn("Token expirado o inválido. Cerrando sesión...");
            forceLogout();
            throw new Error("Sesión expirada");
        }

        return response;
    } catch (error) {
        console.error(`Error en fetchAPI [${endpoint}]:`, error);
        throw error; // Propagamos el error para manejarlo en la vista
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

// Event listener global para el botón de cerrar sesión
// Usamos { once: true } en el listener no es necesario si lo definimos una vez fuera, 
// pero como initApp se llama varias veces, mejor asignarlo de forma segura:
btnLogout.replaceWith(btnLogout.cloneNode(true)); // Limpia listeners previos
document.getElementById('btnLogout').addEventListener('click', handleLogout);

async function handleLogout() {
    try {
        // Notificamos al servidor para que destruya el token en la BBDD [cite: 141, 185]
        await fetchAPI('/logout', { method: 'POST' });
    } catch (error) {
        console.warn("El servidor no pudo procesar el logout, limpiando cliente localmente.");
    } finally {
        forceLogout();
    }
}

function forceLogout() {
    // Borramos el token y reiniciamos el estado visual
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

// Fíjate que ahora usamos el `fetch` normal aquí porque estas rutas son públicas
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
            initApp();
        } else {
            alert(data.message || 'Error en credenciales.');
        }
    } catch (error) {
        alert("Error de conexión.");
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
            initApp();
        } else {
            alert(data.message || 'Error en el registro.');
        }
    } catch (error) {
        alert("Error de conexión.");
    }
}

/* ==========================================
 * MÓDULO: NAVEGACIÓN Y UTILIDADES
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
            renderPlaceholder(`Vista cargada: ${e.target.getAttribute('data-view').toUpperCase()}`);
        });
    });
}

function renderPlaceholder(text) {
    clearAppContainer();
    const heading = document.createElement('h3');
    heading.className = 'text-center mt-5 text-secondary fade-in';
    heading.textContent = text;
    appContainer.appendChild(heading);
}

document.addEventListener('DOMContentLoaded', initApp);
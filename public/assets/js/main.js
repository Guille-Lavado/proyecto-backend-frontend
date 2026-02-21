/**
 * TrainBike - Frontend Application Entry Point
 * V2: Lógica de Autenticación (Login / Register con Fetch API)
 */

const API_BASE_URL = '/api'; 
const appContainer = document.getElementById('app-container');
const mainNav = document.getElementById('mainNav');

/**
 * Función inicializadora.
 */
function initApp() {
    const token = localStorage.getItem('auth_token');

    if (!token) {
        // Ocultar menú si venimos de un logout (que haremos más adelante)
        mainNav.classList.add('d-none');
        renderLogin();
    } else {
        // Mostrar menú y cargar vista por defecto
        mainNav.classList.remove('d-none');
        setupNavigation();
        renderPlaceholder("Bienvenido a TrainBike. Selecciona una opción del menú.");
    }
}

function clearAppContainer() {
    appContainer.replaceChildren(); 
}

/* ==========================================
 * MÓDULO: AUTENTICACIÓN (LOGIN & REGISTER)
 * ========================================== */

function renderLogin() {
    clearAppContainer();
    const template = document.getElementById('tpl-login');
    const clone = template.content.cloneNode(true);

    const linkRegister = clone.getElementById('link-register');
    linkRegister.addEventListener('click', (e) => {
        e.preventDefault();
        renderRegister();
    });

    const formLogin = clone.getElementById('form-login');
    // NUEVO: Asignamos el manejador del submit para el Login
    formLogin.addEventListener('submit', handleLoginSubmit);

    appContainer.appendChild(clone);
}

function renderRegister() {
    clearAppContainer();
    const template = document.getElementById('tpl-register');
    const clone = template.content.cloneNode(true);

    const linkLogin = clone.getElementById('link-login');
    linkLogin.addEventListener('click', (e) => {
        e.preventDefault();
        renderLogin();
    });

    const formRegister = clone.getElementById('form-register');
    // NUEVO: Asignamos el manejador del submit para el Registro
    formRegister.addEventListener('submit', handleRegisterSubmit);

    appContainer.appendChild(clone);
}

/**
 * Procesa el envío del formulario de Login usando Fetch
 */
async function handleLoginSubmit(e) {
    e.preventDefault(); // Evitamos la recarga de la página

    // Capturamos los valores del DOM de forma segura
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok && data.access_token) {
            // Guardamos el token para futuras peticiones
            localStorage.setItem('auth_token', data.access_token);
            // Reiniciamos la app para que detecte el token y muestre el menú
            initApp();
        } else {
            // Manejo de errores básico (En la V4 lo mejoraremos con Toasts visuales)
            alert(data.message || 'Error en las credenciales. Revisa tu email y contraseña.');
        }
    } catch (error) {
        console.error("Error en la petición de login:", error);
        alert("Error de conexión con el servidor.");
    }
}

/**
 * Procesa el envío del formulario de Registro usando Fetch
 */
async function handleRegisterSubmit(e) {
    e.preventDefault();

    // Construimos el objeto con los datos del ciclista requeridos en la práctica
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
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok && data.access_token) {
            localStorage.setItem('auth_token', data.access_token);
            initApp();
        } else {
            alert(data.message || 'Error en el registro. Verifica los datos introducidos.');
            console.log("Detalles del error de validación:", data.errors);
        }
    } catch (error) {
        console.error("Error en la petición de registro:", error);
        alert("Error de conexión con el servidor.");
    }
}

/* ==========================================
 * MÓDULO: NAVEGACIÓN Y UTILIDADES
 * ========================================== */

function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        // Prevenir añadir listeners duplicados si setupNavigation se llama varias veces
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
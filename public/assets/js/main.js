/**
 * TrainBike - Frontend Application Entry Point
 * V1: Maquetación y control del DOM para Autenticación
 */

const API_BASE_URL = '/api'; // Como estamos en el mismo servidor Laravel, usamos ruta relativa
const appContainer = document.getElementById('app-container');
const mainNav = document.getElementById('mainNav');

function initApp() {
    const token = localStorage.getItem('auth_token');

    if (!token) {
        // Si no hay token, renderizamos la pantalla de Login
        renderLogin();
    } else {
        mainNav.classList.remove('d-none');
        setupNavigation();
        renderPlaceholder("Bienvenido a TrainBike. (Vistas en desarrollo)");
    }
}

/**
 * Limpia el contenedor principal de forma segura.
 * Alternativa moderna y segura a appContainer.innerHTML = ''
 */
function clearAppContainer() {
    appContainer.replaceChildren(); 
}

/**
 * Renderiza la vista de Inicio de Sesión usando la etiqueta <template>
 */
function renderLogin() {
    clearAppContainer();
    
    const template = document.getElementById('tpl-login');
    // Clonamos el contenido del template en memoria
    const clone = template.content.cloneNode(true);

    // Añadimos los event listeners a los elementos del nodo clonado antes de insertarlo
    const linkRegister = clone.getElementById('link-register');
    linkRegister.addEventListener('click', (e) => {
        e.preventDefault();
        renderRegister();
    });

    const formLogin = clone.getElementById('form-login');
    formLogin.addEventListener('submit', (e) => {
        e.preventDefault();
        // En la V2 añadiremos la petición fetch aquí
        console.log("Formulario de login enviado (Pendiente de lógica fetch)");
    });

    // Finalmente, insertamos el nodo clonado en el DOM
    appContainer.appendChild(clone);
}

/**
 * Renderiza la vista de Registro usando la etiqueta <template>
 */
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
    formRegister.addEventListener('submit', (e) => {
        e.preventDefault();
        // En la V2 añadiremos la petición fetch aquí
        console.log("Formulario de registro enviado (Pendiente de lógica fetch)");
    });

    appContainer.appendChild(clone);
}

// (Mantenemos setupNavigation y renderPlaceholder de la V0 aquí abajo)
function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove('active'));
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
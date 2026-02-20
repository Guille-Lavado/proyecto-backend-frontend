/**
 * TrainBike - Frontend Application Entry Point
 * Desarrollado puramente con Vanilla JS (ES6+) y la API del DOM.
 */

// Constantes de la aplicación
const API_BASE_URL = 'http://localhost:8000/api'; // Ajusta esto según tu entorno local
const appContainer = document.getElementById('app-container');
const mainNav = document.getElementById('mainNav');

/**
 * Función inicializadora de la aplicación.
 * Evalúa el estado de autenticación y carga la vista correspondiente.
 */
function initApp() {
    // Simularemos la verificación de token para esta fase 0
    const token = localStorage.getItem('auth_token');

    if (!token) {
        // Redirigir a lógica de login (V1)
        renderPlaceholder("Por favor, inicia sesión (Pantalla de Auth en V1)");
    } else {
        // Usuario logueado: mostrar menú y cargar vista por defecto
        mainNav.classList.remove('d-none');
        setupNavigation();
        renderPlaceholder("Bienvenido a TrainBike. Selecciona una opción del menú.");
    }
}

/**
 * Configura los event listeners para la navegación SPA.
 */
function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const view = e.target.getAttribute('data-view');
            
            // Actualizar estado activo en la UI
            navLinks.forEach(l => l.classList.remove('active'));
            e.target.classList.add('active');

            // Cargar la vista solicitada (Mockup por ahora)
            renderPlaceholder(`Vista cargada: ${view.toUpperCase()}`);
        });
    });
}

/**
 * Función auxiliar temporal para renderizar texto seguro.
 * Utiliza textContent puro, respetando la regla de 0 HTML injection.
 * @param {string} text - El texto a mostrar.
 */
function renderPlaceholder(text) {
    // Vaciamos el contenedor de forma segura
    while (appContainer.firstChild) {
        appContainer.removeChild(appContainer.firstChild);
    }

    const heading = document.createElement('h3');
    heading.className = 'text-center mt-5 text-secondary fade-in';
    heading.textContent = text;

    appContainer.appendChild(heading);
}

// Iniciar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initApp);
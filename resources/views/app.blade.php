<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainBike - Gestión de Entrenamientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light">

    <nav id="mainNav" class="navbar navbar-expand-lg navbar-dark bg-dark d-none">
        <div class="container">
            <a class="navbar-brand" href="#">🚴 TrainBike</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-view="bloques">Bloques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-view="planes">Planes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-view="sesiones">Sesiones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-view="resultados">Resultados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-view="sesion-plan">Sesión-Plan</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <button id="btnLogout" class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
                </div>
            </div>
        </div>
    </nav>

    <main id="app-container" class="container mt-4">
        <div class="text-center mt-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Inicializando TrainBike...</p>
        </div>
    </main>

    <template id="tpl-login">
        <div class="row justify-content-center fade-in">
            <div class="col-12 col-md-6 col-lg-4 mt-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="h4">🚴 TrainBike</h2>
                            <p class="text-muted">Inicia sesión para entrenar</p>
                        </div>
                        <form id="form-login">
                            <div class="mb-3">
                                <label for="login-email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="login-email" required autocomplete="email">
                            </div>
                            <div class="mb-4">
                                <label for="login-password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="login-password" required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Entrar a la plataforma</button>
                        </form>
                        <div class="text-center">
                            <small class="text-muted">¿Nuevo en TrainBike? <a href="#" id="link-register" class="text-decoration-none">Regístrate aquí</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl-register">
        <div class="row justify-content-center fade-in">
            <div class="col-12 col-md-8 col-lg-6 mt-4 mb-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="h4">🚴 Únete a TrainBike</h2>
                            <p class="text-muted">Crea tu perfil de ciclista</p>
                        </div>
                        <form id="form-register">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="reg-nombre" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reg-apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="reg-apellidos" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="reg-email" required autocomplete="email">
                            </div>
                            <div class="mb-3">
                                <label for="reg-password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="reg-password" required minlength="6">
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="reg-fecha" class="form-label">Nacimiento</label>
                                    <input type="date" class="form-control" id="reg-fecha" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="reg-peso" class="form-label">Peso (kg)</label>
                                    <input type="number" step="0.1" class="form-control" id="reg-peso" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="reg-altura" class="form-label">Altura (cm)</label>
                                    <input type="number" step="0.1" class="form-control" id="reg-altura" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mb-3 mt-2">Crear mi cuenta</button>
                        </form>
                        <div class="text-center">
                            <small class="text-muted">¿Ya tienes cuenta? <a href="#" id="link-login" class="text-decoration-none">Inicia sesión</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="assets/js/main.js"></script>
</body>
</html>
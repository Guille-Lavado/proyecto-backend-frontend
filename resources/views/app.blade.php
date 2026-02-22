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

    <template id="tpl-bloques-list">
        <div class="fade-in">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">🧩 Bloques de Entrenamiento</h2>
                <button id="btn-nuevo-bloque" class="btn btn-primary">
                    + Añadir Bloque
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Duración Est.</th>
                                    <th>Zonas (Potencia / Pulso)</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bloques-tbody">
                                <tr id="bloques-loading">
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                        Cargando bloques...
                                    </td>
                                </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl-bloque-form">
        <div class="row justify-content-center fade-in">
            <div class="col-12 col-lg-8 mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                    <h2 class="h3 mb-0" id="bloque-form-title">⚡ Nuevo Bloque</h2>
                    <button id="btn-cancelar-bloque" class="btn btn-outline-secondary">
                        Volver al listado
                    </button>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form id="form-bloque">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="bq-nombre" class="form-label text-dark fw-bold">Nombre del Bloque *</label>
                                    <input type="text" class="form-control" id="bq-nombre" required placeholder="Ej: Calentamiento progresivo">
                                </div>
                                <div class="col-md-4 mt-3 mt-md-0">
                                    <label for="bq-tipo" class="form-label text-dark fw-bold">Tipo *</label>
                                    <select class="form-select" id="bq-tipo" required>
                                        <option value="" disabled selected>Selecciona...</option>
                                        <option value="Calentamiento">Calentamiento</option>
                                        <option value="Activo">Activo (Series)</option>
                                        <option value="Recuperación">Recuperación</option>
                                        <option value="Enfriamiento">Enfriamiento</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bq-descripcion" class="form-label text-dark fw-bold">Descripción / Instrucciones</label>
                                <textarea class="form-control" id="bq-descripcion" rows="2" placeholder="Ej: Rodaje suave subiendo cadencia poco a poco..."></textarea>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3 text-secondary">Métricas Objetivo (Opcional)</h5>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="bq-duracion" class="form-label">Duración Est. (min)</label>
                                    <input type="number" class="form-control" id="bq-duracion" min="1" max="300">
                                </div>
                                <div class="col-md-4">
                                    <label for="bq-pot-min" class="form-label">% FTP Mínimo</label>
                                    <input type="number" class="form-control" id="bq-pot-min" min="0" max="250" placeholder="Ej: 50">
                                </div>
                                <div class="col-md-4">
                                    <label for="bq-pot-max" class="form-label">% FTP Máximo</label>
                                    <input type="number" class="form-control" id="bq-pot-max" min="0" max="300" placeholder="Ej: 75">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="bq-pulso-max" class="form-label">% Pulso Máximo</label>
                                    <input type="number" class="form-control" id="bq-pulso-max" min="0" max="100" placeholder="Ej: 70">
                                </div>
                                <div class="col-md-6">
                                    <label for="bq-pulso-res" class="form-label">% Pulso de Reserva</label>
                                    <input type="number" class="form-control" id="bq-pulso-res" min="0" max="100">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="bq-comentario" class="form-label">Comentario Privado</label>
                                <input type="text" class="form-control" id="bq-comentario">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Guardar Bloque de Entrenamiento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl-bloque-detalle">
        <div class="row justify-content-center fade-in">
            <div class="col-12 col-lg-8 mb-5 mt-3">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">🔍 Detalles del Bloque</h4>
                        <button id="btn-volver-detalle" class="btn btn-sm btn-outline-light">Volver</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Nombre:</div>
                            <div class="col-sm-8 text-dark" id="det-nombre"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Tipo:</div>
                            <div class="col-sm-8"><span class="badge bg-secondary" id="det-tipo"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Descripción:</div>
                            <div class="col-sm-8" id="det-desc"></div>
                        </div>
                        <hr>
                        <h5 class="text-secondary mb-3">Métricas Estimadas</h5>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Duración:</div>
                            <div class="col-sm-8" id="det-duracion"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Zonas de Potencia:</div>
                            <div class="col-sm-8" id="det-potencia"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Zonas de Pulso:</div>
                            <div class="col-sm-8" id="det-pulso"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Comentario Interno:</div>
                            <div class="col-sm-8 fst-italic" id="det-comentario"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl-planes-list">
        <div class="fade-in">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">📅 Planes de Entrenamiento</h2>
                <button id="btn-nuevo-plan" class="btn btn-primary">
                    + Crear Plan
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Plan</th>
                                    <th>Fechas (Inicio - Fin)</th>
                                    <th>Objetivo</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="planes-tbody">
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                        Cargando planes...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl-plan-form">
        <div class="row justify-content-center fade-in">
            <div class="col-12 col-lg-8 mb-5 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 mb-0">📝 Nuevo Plan</h2>
                    <button id="btn-cancelar-plan" class="btn btn-outline-secondary">Volver</button>
                </div>

                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <form id="form-plan">
                            <div class="mb-3">
                                <label for="pl-nombre" class="form-label fw-bold">Nombre del Plan *</label>
                                <input type="text" class="form-control" id="pl-nombre" required placeholder="Ej: Preparación Quebrantahuesos 2026">
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="pl-fecha-inicio" class="form-label fw-bold">Fecha de Inicio *</label>
                                    <input type="date" class="form-control" id="pl-fecha-inicio" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="pl-fecha-fin" class="form-label fw-bold">Fecha de Fin *</label>
                                    <input type="date" class="form-control" id="pl-fecha-fin" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pl-objetivo" class="form-label fw-bold">Objetivo Principal</label>
                                <input type="text" class="form-control" id="pl-objetivo" placeholder="Ej: Terminar en menos de 6h 30m">
                            </div>

                            <div class="mb-3">
                                <label for="pl-descripcion" class="form-label">Descripción general</label>
                                <textarea class="form-control" id="pl-descripcion" rows="3"></textarea>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="pl-activo" checked>
                                <label class="form-check-label" for="pl-activo">Plan Activo</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Guardar Plan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl-sesiones-list">
        <div class="fade-in pb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">🚴 Sesiones de Entrenamiento</h2>
                <button id="btn-nueva-sesion" class="btn btn-primary">
                    + Planificar Sesión
                </button>
            </div>

            <div class="row g-4" id="sesiones-container">
                </div>

            <div id="scroll-sentinel" class="text-center py-5 mt-3 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-2">Cargando más entrenamientos...</p>
            </div>

            <div id="no-more-sesiones" class="text-center py-4 text-muted d-none">
                <i class="fs-5">🏁 No hay más sesiones en tu historial.</i>
            </div>
        </div>
    </template>

    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="assets/js/main.js"></script>
</body>
</html>
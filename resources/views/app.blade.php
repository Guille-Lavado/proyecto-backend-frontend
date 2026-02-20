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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="assets/js/main.js"></script>
</body>
</html>
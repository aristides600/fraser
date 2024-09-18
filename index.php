<?php
require_once 'api/autenticacion.php';
require_once 'api/permisos.php';

// Verificar si la sesión ya está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Documentos</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .logo-title-container {
            display: flex;
            align-items: center;
            background-color: #dc3545;
            padding: 10px;
            color: white;
        }

        .logo-title-container img {
            height: 50px;
            margin-right: 10px;
        }

        .logout-button,
        .change-password-button {
            background-color: #dc3545;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover,
        .change-password-button:hover {
            background-color: #b02a37;
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .card {
            width: 18rem;
            text-align: center;
        }

        .card-header {
            background-color: #dc3545;
            color: white;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header i {
            margin-right: 10px;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-body .btn {
            margin-top: 10px;
        }

        .navbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .nav-link {
            color: #343a40;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #007bff;
        }

        .user-info {
            margin-right: 10px;
            /* Ajustar este valor según sea necesario */
            display: flex;
            align-items: center;
        }

        /* /// */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        #app {
            min-height: 100vh;
            /* Hace que el contenedor ocupe toda la ventana */
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
            /* Hace que el contenido crezca y empuje el footer hacia abajo */
        }

        footer {
            background-color: #dc3545;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            position: relative;
            width: 100%;
            color: white; /* Cambia el color del texto a blanco */
        }
    </style>
</head>

<body>
    <div id="app" class="container-fluid p-0">
        <!-- Header -->
        <div class="logo-title-container">
            <img src="./img/Logo.png" alt="Logo">
            <h3>Sistema de Gestión de Documentos</h3>
        </div>

        <!-- Menú de navegación -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php if (tienePermiso($user_id, 'documentos')) : ?>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="documentosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Documentos
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="documentosDropdown" data-bs-auto-close="outside">
                                    <li><a class="dropdown-item" href="documentos.php">Documentos</a></li>
                                    <li><a class="dropdown-item" href="tramitados.php">Tramitados</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (tienePermiso($user_id, 'vehiculos')) : ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="vehiculosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Vehículos
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="vehiculosDropdown" data-bs-auto-close="outside">
                                    <li><a class="dropdown-item" href="vehiculos.php">Vehículos</a></li>
                                    <li><a class="dropdown-item" href="marcas.php">Marcas</a></li>
                                    <li><a class="dropdown-item" href="modelos.php">Modelos</a></li>
                                    <li><a class="dropdown-item" href="colores.php">Colores</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (tienePermiso($user_id, 'usuarios')) : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Usuarios
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="usuariosDropdown" data-bs-auto-close="outside">
                                    <li><a class="dropdown-item" href="usuarios.php">Usuarios</a></li>

                                </ul>
                            </li>
                        <?php endif; ?>

                    </ul>
                    <!-- Menú de navegación -->
                    <!-- Información del usuario y botones a la derecha -->
                    <div class="d-flex ms-auto align-items-center">
                        <div class="user-info">
                            <?php
                            echo htmlspecialchars($nombre . ' ' . htmlspecialchars($apellido));
                            ?>
                        </div>

                        <!-- Botón de cerrar sesión -->
                        <a href="logout.php" class="logout-button ms-3">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a>

                        <!-- Botón de cambiar contraseña -->
                        <button class="change-password-button ms-3" type="button" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key-fill"></i>
                        </button>
                    </div>


                </div>
            </div>
        </nav>


        <main class="container">

            <!-- Contenido principal -->
            <div class="card-container">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-car-front"></i> Vehículos
                    </div>
                    <div class="card-body">
                        <p>Gestión de Vehículos.</p>
                        <a href="vehiculos.php" class="btn btn-primary">Ir a Vehículos</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark-text"></i> Documentos
                    </div>
                    <div class="card-body">
                        <p>Gestión de Documentos.</p>
                        <a href="documentos.php" class="btn btn-primary">Ir a Documentos</a>
                    </div>
                </div>
            </div>

            <!-- Modal para cambiar contraseña -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="changePasswordModalLabel">Cambiar Contraseña de: <?php
                                                                                                            echo htmlspecialchars($nombre . ' ' . htmlspecialchars($apellido));
                                                                                                            ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="changePasswordForm">
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" id="currentPassword" v-model="currentPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="newPassword" v-model="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="confirmPassword" v-model="confirmPassword" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" @click="changePassword">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <p>&copy; Copyright 2024 | Transporte Fraser | Desarrollado por InfoSys</p>
                </div>
            </div>
        </div>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./js/index.js"></script>

</body>

</html>
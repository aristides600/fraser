<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Documentos</title>

    <link href="./cdn/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
    <style>
        .logo-title-container {
            display: flex;
            align-items: center;
            background-color: red;
            padding: 10px;
            color: white;
        }

        .logo-title-container img {
            height: 50px;
            /* Tamaño del logo */
            margin-right: 10px;
        }

        .logout-button {
            background-color: transparent;
            border: none;
            color: #007bff;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .logout-button:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="logo-title-container">
            <img src="./img/Logo.png" alt="Logo">
            <h3>Sistema de Gestión de Documentos</h3>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="documentos.php">Documentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="vehiculos.php">Vehículos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="marcas.php">Marcas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="modelos.php">Modelos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="colores.php">Colores</a>
                        </li>
                    </ul>
                    <!-- Botón de cerrar sesión alineado a la derecha -->
                    <form class="d-flex ms-auto" action="logout.php" method="POST">
                        <button class="logout-button" type="submit">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <p class="text-center">Seleccione una opción del menú para comenzar a gestionar los documentos, marcas y modelos.</p>
            </div>
        </div>
    </div>

    <script src="./cdn/popper.min.js"></script>
</body>

</html>

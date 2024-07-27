<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vehiculos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="./css/estilos.css"> -->
</head>

<body>
    <?php include 'header.php'; ?>
    <?php
    require_once 'api/autenticacion.php';
    ?>
    <div id="app" class="container mt-5">
        <h1>Vehiculos</h1>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-sm btn-primary" @click="irNuevoVehiculo">
                    Nuevo Vehiculo
                </button>
            </div>
        </div>
        <label for="patente" class="form-label me-2">Buscar por patente:</label>
        <div class="mb-3 d-flex align-items-center">
            <span class="input-group-text">Buscar...</span>
            <input type="search" class="form-control me-2" id="patente" v-model="patente" maxlength="7" @input="buscarVehiculo">
        </div>
        <!-- <label for="patente" class="form-label me-2">Buscar por patente:</label>
        <div class="mb-3 d-flex align-items-center">
            <span class="input-group-text">Buscar...</span>
            <input type="search" class="form-control me-2" id="patente" v-model="patente" maxlength="7" @keydown.enter="buscarVehiculo">
        </div> -->


        <!-- Tabla para mostrar los vehiculos existentes -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Patente</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Año</th>
                    <th>Apellido Nombre</th>
                    <th>Carroceria</th>
                    <th>Motor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="vehiculo in vehiculos" :key="vehiculo.id">
                    <td>{{ vehiculo.patente }}</td>
                    <td>{{ vehiculo.nombre_marca }}</td>
                    <td>{{ vehiculo.nombre_modelo }}</td>
                    <td>{{ vehiculo.nombre_color }}</td>
                    <td>{{ vehiculo.ano }}</td>
                    <td>{{ vehiculo.cliente_apellido }} {{ vehiculo.cliente_nombre }}</td>
                    <td>{{ vehiculo.carroceria }}</td>
                    <td>{{ vehiculo.motor }}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Acciones">
                            <button class="btn btn-sm btn-outline-primary" @click="irEditarVehiculo(vehiculo.id)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" @click="eliminarVehiculo(vehiculo.id)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="patente && patente.length > 0 && vehiculos && vehiculos.length == 0">
            <h2>No se encontró ningún vehiculo</h2>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <a href="index.php" class="btn btn-danger ms-auto">Salir</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/chequeo_permiso.js"></script>
    <script src="./js/mensajes.js"></script>
    <script src="./js/vehiculos.js"></script>
</body>

</html>
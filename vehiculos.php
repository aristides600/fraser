<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Vehículos</title>
    <link href="./cdn/bootstrap.min.css" rel="stylesheet">
    <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">

</head>

<body>
<?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <h1 class="mb-4">Gestión de Vehículos</h1>
        <button @click="showAddModal" class="btn btn-primary mb-3">Agregar Vehículo</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Patente</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Motor</th>
                    <th>Año</th>
                    <th>Corrocería</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="vehiculo in vehiculos" :key="vehiculo.id">
                    <td>{{ vehiculo.patente }}</td>
                    <td>{{ vehiculo.marca }}</td>
                    <td>{{ vehiculo.modelo }}</td>
                    <td>{{ vehiculo.color }}</td>
                    <td>{{ vehiculo.motor }}</td>
                    <td>{{ vehiculo.anio }}</td>
                    <td>{{ vehiculo.corroceria }}</td>
                    <td>{{ vehiculo.estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <button @click="showEditModal(vehiculo)" class="btn btn-warning">Editar</button>
                        <!-- <button @click="deleteVehiculo(vehiculo.id)" class="btn btn-danger">Eliminar</button> -->
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="vehiculoModal" tabindex="-1" aria-labelledby="vehiculoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vehiculoModalLabel">{{ isEdit ? 'Editar Vehículo' : 'Agregar Vehículo' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="isEdit ? updateVehiculo() : addVehiculo()">
                            <div class="mb-3">
                                <label for="patente" class="form-label">Patente</label>
                                <input type="text" class="form-control" id="patente" v-model="vehiculo.patente" required>
                            </div>
                            <div class="mb-3">
                                <label for="marca_id" class="form-label">Marca</label>
                                <select class="form-control" id="marca_id" v-model="vehiculo.marca_id" required>
                                    <option v-for="marca in marcas" :value="marca.id">{{ marca.nombre }}</option>
                                </select>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="modelo_id" class="form-label">Modelo</label>
                                <select class="form-control" id="modelo_id" v-model="vehiculo.modelo_id" required>
                                    <option v-for="modelo in modelos" :value="modelo.id">{{ modelo.nombre }}</option>
                                </select>
                            </div> -->
                            <div class="mb-3">
                                <label for="modelo_id" class="form-label">Modelo</label>
                                <select class="form-control" id="modelo_id" v-model="vehiculo.modelo_id" required>
                                    <option v-for="modelo in modelosFiltrados" :value="modelo.id">{{ modelo.nombre }}</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="color_id" class="form-label">Color</label>
                                <select class="form-control" id="color_id" v-model="vehiculo.color_id" required>
                                    <option v-for="color in colores" :value="color.id">{{ color.nombre }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="motor" class="form-label">Motor</label>
                                <input type="text" class="form-control" id="motor" v-model="vehiculo.motor" required>
                            </div>
                            <div class="mb-3">
                                <label for="anio" class="form-label">Año</label>
                                <input type="number" class="form-control" id="anio" v-model="vehiculo.anio" required>
                            </div>
                            <div class="mb-3">
                                <label for="corroceria" class="form-label">Corrocería</label>
                                <input type="text" class="form-control" id="corroceria" v-model="vehiculo.corroceria" required>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-control" id="estado" v-model="vehiculo.estado" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isEdit ? 'Actualizar' : 'Agregar' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <a href="index.php" class="btn btn-danger">Salir</a>
        </div>

    </div>
    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./cdn/popper.min.js"></script>
    <script src="./js/vehiculos.js"></script>
    <?php include 'footer.php'; ?>

</body>

</html>
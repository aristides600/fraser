<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Modelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <div id="app" class="container mt-5">
        <h1>Gestión de Modelos</h1>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" v-model="modelo.nombre" id="nombre">
        </div>
        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <select class="form-select" v-model="modelo.marca_id">
                <option v-for="marca in marcas" :value="marca.id">{{ marca.nombre }}</option>
            </select>
        </div>
        <button class="btn btn-primary" @click="guardarModelo">{{ editando ? 'Actualizar' : 'Guardar' }}</button>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="modelo in modelos" :key="modelo.id">
                    <td>{{ modelo.id }}</td>
                    <td>{{ modelo.nombre }}</td>
                    <td>{{ modelo.marca_nombre }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" @click="editarModelo(modelo)">Editar</button>
                        <button class="btn btn-danger btn-sm" @click="eliminarModelo(modelo.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.3.4/dist/axios.min.js"></script>
    <script src="./js/modelos.js"></script>
</body>
</html>

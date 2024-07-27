<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Marcas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container mt-5">
        <h1 class="mb-4">Gestión de Marcas</h1>
        <form @submit.prevent="addMarca">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Marca</label>
                <input type="text" class="form-control" id="nombre" v-model="newMarca.nombre" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Marca</button>
        </form>

        <div v-if="errorMessage" class="alert alert-danger mt-3">{{ errorMessage }}</div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="marca in marcas" :key="marca.id">
                    <td>{{ marca.id }}</td>
                    <td>
                        <input type="text" class="form-control" v-model="marca.nombre">
                    </td>
                    <td>
                        <button class="btn btn-success" @click="updateMarca(marca)">Actualizar</button>
                        <button class="btn btn-danger" @click="deleteMarca(marca.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.3.4/dist/axios.min.js"></script>
    <script src="./js/marcas.js"></script>

</body>

</html>

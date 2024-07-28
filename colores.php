<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Colores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div id="app" class="container mt-5">
        <h1 class="mb-4">Gestión de Colores</h1>
        <form @submit.prevent="addColor">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del color</label>
                <input type="text" class="form-control" id="nombre" v-model="newColor.nombre" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar color</button>
        </form>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="color in colores" :key="color.id">
                    <td>{{ color.id }}</td>
                    <td>
                        <input type="text" class="form-control" v-model="color.nombre">
                    </td>
                    <td>
                        <button class="btn btn-success" @click="updateColor(color)">Actualizar</button>
                        <button class="btn btn-danger" @click="deleteColor(color.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end mt-4">
            <a href="index.php" class="btn btn-danger">Salir</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.3.4/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="./js/colores.js"></script>
</body>
</html>

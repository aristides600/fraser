<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Colores</title>
    <link href="./cdn/bootstrap.min.css" rel="stylesheet">
    <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">

</head>

<body>
    <?php include 'header.php'; ?>

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
                    <!-- <th>ID</th> -->
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="color in colores" :key="color.id">
                    <!-- <td>{{ color.id }}</td> -->
                    <td>
                        <input type="text" class="form-control" v-model="color.nombre">
                    </td>
                    <td>
                        <button class="btn btn-warning" @click="updateColor(color)"><i class="bi bi-arrow-clockwise"></i>Actualizar</button>
                       
                        <!-- <button class="btn btn-danger" @click="deleteColor(color.id)">Eliminar</button> -->
                    </td>

                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end mt-4">
            <a href="index.php" class="btn btn-danger">Salir</a>
        </div>
    </div>

    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./js/colores.js"></script>
    <?php include 'footer.php'; ?>

</body>

</html>
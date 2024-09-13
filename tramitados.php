<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Tramitados y Vehículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="./css/estilos.css">

</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-4">
        <h1 class="mb-4">Documentos Tramitados</h1>
        <div class="mb-3">
            <label for="patente" class="form-label">Patente del Vehículo</label>
            <input type="search" v-model="patente" @input="obtenerDocumentos" class="form-control" id="patente" maxlength="7">
        </div>


        <!-- Mensaje si no hay coincidencias -->
        <div v-if="sinCoincidencias" class="alert alert-warning" role="alert">
            No se encontraron vehículos con esa patente.
        </div>

        <!-- Tabla combinada de Documentos y Vehículos -->
        <div v-if="data.length > 0">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th>ID</th> -->
                        <th>Patente</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Documento</th>

                        <th>Fecha Alta</th>
                        <th>Fecha Vencimiento</th>
                        <th>Fecha Trámite</th>
                        <th>Observación</th>
                        <th>Tramitador</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in data" :key="item.id">
                        <!-- <td>{{ item.id }}</td> -->
                        <td>{{ item.patente }}</td>
                        <td>{{ item.marca }}</td>
                        <td>{{ item.modelo }}</td>
                        <td>{{ item.anio }}</td>
                        <td>{{ item.color }}</td>
                        <td>{{ item.tipo_documento }}</td>

                        <td>{{ item.fecha_alta }}</td>
                        <td>{{ item.fecha_vencimiento }}</td>
                        <td>{{ item.fecha_tramite }}</td>

                        <td>{{ item.observacion }}</td>
                        <td>{{ item.nombre }} {{ item.apellido }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-4">
                <a href="index.php" class="btn btn-danger">Salir</a>
            </div>
        </div>
        <div v-else>
            <p>No se encontraron datos.</p>
        </div>
    </div>

    <?php include 'footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.33/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./js/chequeo_permiso.js"></script>

    <script src="./js/tramitados.js"></script>
</body>

</html>
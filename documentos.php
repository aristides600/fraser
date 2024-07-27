<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .resaltar {
            background-color: #FFFF99;
            /* Amarillo claro */
        }
        .vencido {
            background-color: #FFCCCC;
            /* Rojo claro */
        }
    </style>
</head>

<body>
    <div id="app" class="container mt-5">
        <h1>Documentos</h1>
        <!-- <form @submit.prevent="guardarDocumento">
            <div class="mb-3">
                <label for="vehiculo_id" class="form-label">Vehículo ID</label>
                <input type="number" class="form-control" v-model="documento.vehiculo_id" required>
            </div>
            <div class="mb-3">
                <label for="tipo_id" class="form-label">Tipo ID</label>
                <input type="number" class="form-control" v-model="documento.tipo_id" required>
            </div>
            <div class="mb-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" class="form-control" v-model="documento.fecha_vencimiento" required>
            </div>
            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <input type="text" class="form-control" v-model="documento.observacion" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ editando ? 'Actualizar' : 'Crear' }}</button>
        </form> -->

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Patente</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Observación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="doc in documentos" :key="doc.id" :class="getRowClass(doc)">
                    <td>{{ doc.patente }}</td>
                    <td>{{ doc.marca_nombre }}</td>
                    <td>{{ doc.modelo_nombre }}</td>
                    <td>{{ doc.color_nombre }}</td>
                    <td>{{ doc.tipo_nombre }}</td>
                    <td>{{ formatFecha(doc.fecha_vencimiento) }}</td>
                    <td>{{ doc.observacion }}</td>
                    <td>
                        <span v-if="porVencer(doc.fecha_vencimiento) > 0">
                            <i class="bi bi-exclamation-triangle text-warning"></i> Vence en {{ porVencer(doc.fecha_vencimiento) }} días
                        </span>
                        <span v-else-if="porVencer(doc.fecha_vencimiento) < 0">
                            <i class="bi bi-exclamation-triangle text-danger"></i> Vencido hace {{ Math.abs(porVencer(doc.fecha_vencimiento)) }} días
                        </span>
                        <span v-else>No</span>
                    </td>
                    <td>
                        <button class="btn btn-warning" @click="editarDocumento(doc)"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger" @click="eliminarDocumento(doc.id)"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.37/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./js/documentos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
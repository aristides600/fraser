<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación CRUD</title>
    <link href="./cdn/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
    
</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <h1>Gestión de Documentos</h1>
        <div>
            <button class="btn btn-sm btn-primary me-2" @click="nuevoDocumento">
                <i class="bi bi-plus"></i> Nuevo Documento
            </button>
        </div>
       
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Patente</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    <th>Vencimiento</th>
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
                        <span v-if="doc.diasPorVencer > 0">
                            <i class="bi bi-exclamation-triangle text-warning"></i> Vence en {{ doc.diasPorVencer }} días
                        </span>
                        <span v-else-if="doc.diasPorVencer < 0">
                            <i class="bi bi-exclamation-triangle text-danger"></i> Vencido hace {{ Math.abs(doc.diasPorVencer) }} días
                        </span>
                        <span v-else>No</span>
                    </td>
                    <td>
                        <button class="btn btn-warning" @click="editarDocumento(doc.id)"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-ok" @click="tramitarDocumento(doc.id)"><i class="bi bi-check-circle"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end mt-4">
            <a href="index.php" class="btn btn-danger">Salir</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./js/documentos.js"></script>

</body>

</html>
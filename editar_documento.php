<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Documento</title>

  <link href="./cdn/bootstrap.min.css" rel="stylesheet">
  <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/estilos.css">

</head>

<body>
  <?php include 'header.php'; ?>
  <div id="app" class="container mt-5">
    <h2>Editar Documento</h2>
    <form @submit.prevent="grabarDocumento">
      <div class="mb-3">
        <label for="tipo_id" class="form-label">Tipo de Documento</label>
        <select v-model="documento.tipo_id" class="form-control" id="tipo_id" required>
          <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">{{ tipo.nombre }}</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
        <input type="date" v-model="documento.fecha_vencimiento" class="form-control" id="fecha_vencimiento" required>
      </div>
      <div class="mb-3">
        <label for="observacion" class="form-label">Observación</label>
        <input type="text" v-model="documento.observacion" class="form-control" id="observacion" required>
      </div>
      <button type="submit" class="btn btn-primary">Grabar</button>
    </form>
  </div>
  <script src="./cdn/vue.global.js"></script>
  <script src="./cdn/axios.min.js"></script>
  <script src="./cdn/sweetalert2@10.js"></script>
  <script src="./js/chequeo_permiso.js"></script>

  <script src="./js/editar_documento.js"></script>
  <?php include 'footer.php'; ?>
</body>

</html>
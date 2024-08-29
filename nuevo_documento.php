<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Documento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <?php include 'header.php'; ?>
  <div id="app" class="container mt-5">
    <h2>Agregar Documento</h2>
    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="patente" class="form-label">Patente del Vehículo</label>
        <input type="text" v-model="patente" @input="buscarVehiculos" class="form-control" id="patente" required>
      </div>
      <div v-if="vehiculos.length > 0" class="mb-3">
        <h5>Coincidencias</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Patente</th>
              <th>Marca</th>
              <th>Modelo</th>
              <th>Color</th>
              <th>Año</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="vehiculo in vehiculos" :key="vehiculo.id">
              <td>{{ vehiculo.patente }}</td>
              <td>{{ vehiculo.marca }}</td>
              <td>{{ vehiculo.modelo }}</td>
              <td>{{ vehiculo.color }}</td>
              <td>{{ vehiculo.anio }}</td>
              <td>
                <button type="button" class="btn btn-primary" @click="seleccionarVehiculo(vehiculo)">Seleccionar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="vehiculoSeleccionado" class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">Información del Vehículo</h5>
          <p class="card-text">ID: {{ vehiculoSeleccionado.id }}</p>
          <p class="card-text">Marca: {{ vehiculoSeleccionado.marca }}</p>
          <p class="card-text">Modelo: {{ vehiculoSeleccionado.modelo }}</p>
          <p class="card-text">Color: {{ vehiculoSeleccionado.color }}</p>
          <p class="card-text">Año: {{ vehiculoSeleccionado.anio }}</p>
        </div>
      </div>
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
        <textarea v-model="documento.observacion" class="form-control" id="observacion" rows="3" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Agregar Documento</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/vue@3.2.21/dist/vue.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="./js/nuevo_documento.js"></script>
  <?php include 'footer.php'; ?>
</body>

</html>

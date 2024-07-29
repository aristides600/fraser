const { createApp } = Vue;

createApp({
  data() {
    return {
      patente: '',
      vehiculos: [],
      vehiculoSeleccionado: null,
      tipos: [],
      documento: {
        vehiculo_id: '',
        tipo_id: '',
        fecha_vencimiento: '',
        observacion: ''
      }
    };
  },
  methods: {
    buscarVehiculos() {
      if (this.patente.length > 2) {
        axios.get('api/vehiculos_patente.php', { params: { patente: this.patente } })
          .then(response => {
            this.vehiculos = response.data;
          })
          .catch(error => {
            console.error(error);
            this.vehiculos = [];
          });
      } else {
        this.vehiculos = [];
      }
    },
    seleccionarVehiculo(vehiculo) {
      this.vehiculoSeleccionado = vehiculo;
      this.documento.vehiculo_id = vehiculo.id;
    },
    cargarTipos() {
      axios.get('api/tipos.php')
        .then(response => {
          this.tipos = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    submitForm() {
      axios.post('api/documentos.php', this.documento)
        .then(response => {
          Swal.fire({
            icon: 'success',
            title: 'Documento agregado con éxito',
            showConfirmButton: false,
            timer: 1500
          });
          // Limpiar el formulario
          this.patente = '';
          this.vehiculos = [];
          this.vehiculoSeleccionado = null;
          this.documento = {
            vehiculo_id: '',
            tipo_id: '',
            fecha_vencimiento: '',
            observacion: ''
          };
        })
        .catch(error => {
          console.error(error);
          Swal.fire({
            icon: 'error',
            title: 'Hubo un error al agregar el documento',
            text: error.message
          });
        });
    }
  },
  mounted() {
    this.cargarTipos();
  }
}).mount('#app');

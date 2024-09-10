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
      },
      sinCoincidencias: false // Nueva variable para controlar si no hay coincidencias
    };
  },
  methods: {
    buscarVehiculos() {
      if (this.patente.length > 0) {
        axios.get('api/vehiculos_patente.php', { params: { patente: this.patente } })
          .then(response => {
            this.vehiculos = response.data;
            if (this.vehiculos.length === 0) {
              this.sinCoincidencias = true;
            } else {
              this.sinCoincidencias = false;
            }
          })
          .catch(error => {
            console.error(error);
            this.vehiculos = [];
            this.sinCoincidencias = true;
          });
      } else {
        this.vehiculos = [];
        this.sinCoincidencias = false; // Reinicia la variable cuando no hay búsqueda
      }
    },
    seleccionarVehiculo(vehiculo) {
      this.vehiculoSeleccionado = vehiculo;
      this.documento.vehiculo_id = vehiculo.id;
      this.vehiculos = []; // Ocultar tabla de coincidencias al seleccionar un vehículo
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
          if (error.response) {
            if (error.response.status === 409) {
              Swal.fire({
                icon: 'warning',
                title: 'Documento duplicado',
                text: error.response.data.message
              });
            } else if (error.response.status === 400) {
              Swal.fire({
                icon: 'error',
                title: 'Datos incompletos o incorrectos',
                text: error.response.data.message
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Hubo un error al agregar el documento',
                text: error.response.data.message
              });
            }
          } else {
            console.error(error);
            Swal.fire({
              icon: 'error',
              title: 'Error de conexión',
              text: error.message
            });
          }
        });
    }


  },
  mounted() {
    this.cargarTipos();
  }
}).mount('#app');

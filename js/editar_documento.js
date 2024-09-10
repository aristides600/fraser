const { createApp } = Vue;

createApp({
  data() {
    return {
      documento: {
        id: null,
        tipo_id: null,
        fecha_vencimiento: '',
        observacion: ''
      },
      tipos: []
    };
  },
  mounted() {
    const id = new URLSearchParams(window.location.search).get('id');
    if (id) {
      this.documento.id = id;
      this.obtenerDocumento(id);
    }
    this.obtenerTipos();
  },
  methods: {
    obtenerDocumento(id) {
      axios.get(`api/documento_id.php?id=${id}`)
        .then(response => {
          this.documento = response.data;
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al obtener el documento: ' + error,
          });
        });
    },
    obtenerTipos() {
      axios.get('api/tipos.php')
        .then(response => {
          this.tipos = response.data;
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al obtener los tipos de documento: ' + error,
          });
        });
    },
    // grabarDocumento() {
    //   axios.put('api/documentos.php', this.documento)
    //     .then(response => {
    //       Swal.fire({
    //         icon: 'success',
    //         title: 'Éxito',
    //         text: 'Documento editado correctamente',
    //         showConfirmButton: false,
    //         timer: 1500
    //       });
    //     })
    //     .catch(error => {
    //       Swal.fire({
    //         icon: 'error',
    //         title: 'Error',
    //         text: 'Error al editar el documento: ' + error,
    //       });
    //     });
    // }
    grabarDocumento() {
      axios.put('api/documentos.php', this.documento)
        .then(response => {
          Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Documento editado correctamente',
            showConfirmButton: false,
            timer: 1500
          });
          // Puedes limpiar el formulario o realizar otras acciones aquí
        })
        .catch(error => {
          // Manejo detallado del error
          let errorMessage = 'Error al editar el documento.';
          if (error.response) {
            // El servidor respondió con un código de estado fuera del rango 2xx
            if (error.response.status === 409) {
              errorMessage = 'Documento duplicado: ya existe un documento de este tipo para el vehículo seleccionado.';
            } else if (error.response.status === 400) {
              errorMessage = 'Datos incompletos o incorrectos. Por favor, revisa el formulario.';
            } else {
              errorMessage = 'Error del servidor: ' + error.response.data.message || errorMessage;
            }
          } else if (error.request) {
            // La solicitud fue hecha pero no hubo respuesta
            errorMessage = 'No se recibió respuesta del servidor. Por favor, verifica tu conexión.';
          } else {
            // Ocurrió un error al configurar la solicitud
            errorMessage = 'Error al configurar la solicitud: ' + error.message;
          }

          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
          });
        });
    }

  }
}).mount('#app');


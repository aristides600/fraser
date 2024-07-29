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
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al editar el documento: ' + error,
          });
        });
    }
  }
}).mount('#app');


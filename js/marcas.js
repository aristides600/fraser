const { createApp } = Vue;

createApp({
    data() {
        return {
            newMarca: { nombre: '' },
            marcas: [],
            errorMessage: ''
        }
    },
    mounted() {
        this.getMarcas();
        chequeo_permiso('vehiculos');

    },
    methods: {
        getMarcas() {
            axios.get('api/marcas.php')
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron obtener las marcas.', 'error');
                });
        },
        addMarca() {
            axios.post('api/marcas.php', this.newMarca)
                .then(response => {
                    if (response.data.success) {
                        this.getMarcas();
                        this.newMarca.nombre = '';
                        this.errorMessage = '';
                        Swal.fire('Éxito', 'Marca agregada con éxito.', 'success');
                    } else {
                        this.errorMessage = response.data.message;
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Hubo un problema al agregar la marca.', 'error');
                });
        },
        updateMarca(marca) {
            axios.put('api/marcas.php', marca)
                .then(response => {
                    if (response.data.success) {
                        this.getMarcas();
                        this.errorMessage = '';
                        Swal.fire('Éxito', 'Marca actualizada con éxito.', 'success');
                    } else {
                        this.errorMessage = response.data.message;
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Hubo un problema al actualizar la marca.', 'error');
                });
        },
        deleteMarca(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('api/marcas.php', { data: { id: id } })
                        .then(response => {
                            this.getMarcas();
                            Swal.fire('Eliminado', 'La marca ha sido eliminada.', 'success');
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error', 'Hubo un problema al eliminar la marca.', 'error');
                        });
                }
            });
        }
    }
}).mount('#app');

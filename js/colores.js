const { createApp } = Vue;

createApp({
    data() {
        return {
            newColor: { nombre: '' },
            colores: [],
            errorMessage: ''
        }
    },
    mounted() {
        this.getColores();
        chequeo_permiso('vehiculos');

    },
    methods: {
        getColores() {
            axios.get('api/colores.php')
                .then(response => {
                    this.colores = response.data;
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron obtener los colores.', 'error');
                });
        },
        addColor() {
            axios.post('api/colores.php', this.newColor)
                .then(response => {
                    if (response.data.success) {
                        this.getColores();
                        this.newColor.nombre = '';
                        this.errorMessage = '';
                        Swal.fire('Éxito', 'Color agregado con éxito.', 'success');
                    } else {
                        this.errorMessage = response.data.message;
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Hubo un problema al agregar el color.', 'error');
                });
        },
        updateColor(color) {
            axios.put('api/colores.php', color)
                .then(response => {
                    if (response.data.success) {
                        this.getColores();
                        this.errorMessage = '';
                        Swal.fire('Éxito', 'Color actualizado con éxito.', 'success');
                    } else {
                        this.errorMessage = response.data.message;
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Hubo un problema al actualizar el color.', 'error');
                });
        },
        deleteColor(id) {
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
                    axios.delete('api/colores.php', { data: { id: id } })
                        .then(response => {
                            this.getColores();
                            Swal.fire('Eliminado', 'El color ha sido eliminado.', 'success');
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error', 'Hubo un problema al eliminar el color.', 'error');
                        });
                }
            });
        }
    }
}).mount('#app');

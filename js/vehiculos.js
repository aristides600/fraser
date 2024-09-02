const app = Vue.createApp({
    data() {
        return {
            vehiculos: [],
            marcas: [],
            modelos: [],
            colores: [],
            vehiculo: {
                patente: '',
                marca_id: '',
                modelo_id: '',
                color_id: '',
                motor: '',
                anio: '',
                corroceria: '',
                estado: ''
            },
            isEdit: false,
            vehiculoModal: null // Store the modal instance
        }
    },
    mounted() {
        this.fetchVehiculos();
        this.fetchMarcas();
        this.fetchModelos();
        this.fetchColores();

        // Initialize the modal instance after the DOM is fully loaded
        this.vehiculoModal = new bootstrap.Modal(document.getElementById('vehiculoModal'));
    },
    computed: {
        modelosFiltrados() {
            return this.modelos.filter(modelo => modelo.marca_id === this.vehiculo.marca_id);
        }
    },
    methods: {
        fetchVehiculos() {
            axios.get('api/vehiculos.php')
                .then(response => {
                    this.vehiculos = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        fetchMarcas() {
            axios.get('api/marcas.php')
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        fetchModelos() {
            axios.get('api/modelos.php')
                .then(response => {
                    this.modelos = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        fetchColores() {
            axios.get('api/colores.php')
                .then(response => {
                    this.colores = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        showAddModal() {
            this.isEdit = false;
            this.vehiculo = {
                patente: '',
                marca_id: '',
                modelo_id: '',
                color_id: '',
                motor: '',
                anio: '',
                corroceria: '',
                estado: ''
            };
            this.vehiculoModal.show(); // Show the modal
        },
        showEditModal(vehiculo) {
            this.isEdit = true;
            this.vehiculo = Object.assign({}, vehiculo);
            this.vehiculoModal.show(); // Show the modal
        },
        addVehiculo() {
            axios.post('api/vehiculos.php', this.vehiculo)
                .then(response => {
                    if (response.data.success) {
                        this.fetchVehiculos();
                        this.vehiculoModal.hide(); // Hide the modal
                        Swal.fire({
                            title: 'Éxito',
                            text: response.data.message,
                            icon: 'success', // Success icon
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.data.message,
                            icon: 'error', // Error icon with a circle and a cross
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33' // Red color for the error message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: error.response.data.message || 'Error desconocido',
                        icon: 'error', // Error icon with a circle and a cross
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33' // Red color for the error message
                    });
                });
        },

        updateVehiculo() {
            axios.put('api/vehiculos.php', this.vehiculo)
                .then(response => {
                    if (response.data.success) {
                        this.fetchVehiculos();
                        this.vehiculoModal.hide(); // Hide the modal
                        Swal.fire({
                            title: 'Éxito',
                            text: response.data.message,
                            icon: 'success', // Success icon
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.data.message,
                            icon: 'error', // Error icon with a circle and a cross
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33' // Red color for the error message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: error.response.data.message || 'Error desconocido',
                        icon: 'error', // Error icon with a circle and a cross
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33' // Red color for the error message
                    });
                });
        },

        deleteVehiculo(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, bórralo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`api/vehiculos.php?id=${id}`)
                        .then(response => {
                            this.fetchVehiculos();
                            Swal.fire('Eliminado!', response.data.message, 'success');
                        })
                        .catch(error => {
                            Swal.fire('Error', error.response.data.message, 'error');
                        });
                }
            });
        }
    }
});

app.mount('#app');

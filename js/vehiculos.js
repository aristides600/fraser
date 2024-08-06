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
            isEdit: false
        }
    },
    mounted() {
        this.fetchVehiculos();
        this.fetchMarcas();
        this.fetchModelos();
        this.fetchColores();
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
            new bootstrap.Modal(document.getElementById('vehiculoModal')).show();
        },
        showEditModal(vehiculo) {
            this.isEdit = true;
            this.vehiculo = Object.assign({}, vehiculo);
            new bootstrap.Modal(document.getElementById('vehiculoModal')).show();
        },
        addVehiculo() {
            axios.post('api/vehiculos.php', this.vehiculo)
                .then(response => {
                    this.fetchVehiculos();
                    new bootstrap.Modal(document.getElementById('vehiculoModal')).hide();
                    Swal.fire('Éxito', response.data.message, 'success');
                })
                .catch(error => {
                    Swal.fire('Error', error.response.data.message, 'error');
                });
        },
        updateVehiculo() {
            axios.put('api/vehiculos.php', this.vehiculo)
                .then(response => {
                    this.fetchVehiculos();
                    new bootstrap.Modal(document.getElementById('vehiculoModal')).hide();
                    Swal.fire('Éxito', response.data.message, 'success');
                })
                .catch(error => {
                    Swal.fire('Error', error.response.data.message, 'error');
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

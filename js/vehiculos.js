const app = Vue.createApp({
    data() {
        return {
            vehiculos: [],
            clientes: [],
            marcas: [],
            modelos: [],
            colores: [],
            vehiculo: {},
            dni: '',
            clienteSeleccionado: null,
            marcaSinModelos: null
        };
    },
    mounted() {
        Promise.all([
            this.fetchMarcas(),
            // this.fetchModelos(),
            this.fetchColores()
        ]).then(() => {
            this.cargarVehiculos();
        });
        chequeo_permiso('vehiculos');

    },
    methods: {
        buscarCliente() {
            this.clientes = [];
            if (this.dni.length > 0) {
                axios.get('api/cliente_dni.php', {

                    params: {
                        dni: this.dni
                    }
                })
                    .then(response => {
                        this.clientes = response.data;
                        console.log(this.clientes);
                    })
                    .catch(error => {
                        mostrarMensajeError();
                    });
            }

        },
        buscarVehiculo() {
            this.vehiculos = [];
            if (this.patente.length > 0) {
                axios.get('api/vehiculo_patente.php', {

                    params: {
                        patente: this.patente
                    }
                })
                    .then(response => {
                        this.vehiculos = response.data;
                        console.log(this.vehiculos);
                    })
                    .catch(error => {
                        mostrarMensajeError();
                    });
            }

        },
        seleccionarCliente(cliente) {
            this.clienteSeleccionado = cliente;
            this.dni = '';
            this.clientes = [];
        },
        cargarVehiculos() {
            axios.get('api/vehiculos.php')
                .then(response => {
                    this.vehiculos = response.data;
                })
                .catch(error => {
                    console.error('Error al cargar vehiculos: ' + error);
                });
        },
        
        fetchModelosPorMarca() {
            console.log(this.vehiculo.marca_id);
            if (this.vehiculo.marca_id) {
                axios.get('api/modelos_marca.php', {
                    params: {
                        marca_id: this.vehiculo.marca_id
                    }
                })
                .then(response => {
                    this.modelos = response.data;
                    console.log(this.modelos);
                    if(this.modelos.length === 0){
                        this.marcaSinModelos = true;
                    }else{
                        this.marcaSinModelos = false;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar modelos por marca: ' + error);
                });
            } else {
                this.modelos = [];
            }
        },
        fetchMarcas() {
            axios.get('api/marcas.php')
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.error('Error al cargar marcas: ' + error);
                });
        },
        fetchModelos() {
            axios.get('api/modelos.php')
                .then(response => {
                    this.modelos = response.data;
                })
                .catch(error => {
                    console.error('Error al cargar modelos: ' + error);
                });
        },
        fetchColores() {
            axios.get('api/colores.php')
                .then(response => {
                    this.colores = response.data;
                })
                .catch(error => {
                    console.error('Error al cargar colores: ' + error);
                });
        },
        async agregarVehiculo() {
            

            this.vehiculo.cliente_id = this.clienteSeleccionado.id;
            console.log(this.vehiculo);

            const existe = await this.verificarExistenciaVehiculo();
            if (!existe) {
                axios.post('api/vehiculos.php', this.vehiculo)
                    .then(() => {
                        mostrarMensajeExito("Vehiculo registrado");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error al agregar vehiculo: ' + error);
                    });
            } else {
                mostrarMensajeError("El vehiculo ya existe.");
            }
        },
        verificarExistenciaVehiculo() {
            return axios.post('api/verificar_vehiculo.php', {
                nombre: this.nombre
            })
                .then(response => {
                    return response.data.existe;
                })
                .catch(error => {
                    console.error('Error al verificar el nombre: ' + error);
                    return false; // Devolver false en caso de error
                });
        },
        irEditarVehiculo(id) {
            window.location.href = "editar_vehiculo.php?id=" + id;
        },
        
        irNuevoVehiculo() {
            window.location.href = "nuevo_vehiculo.php";
        },
        async eliminarVehiculo(id) {
            try {
                mostrarMensajeConfirmacion('¿Estás seguro?', async (confirmado) => {
                    if (confirmado) {
                        const response = await fetch(`api/vehiculos.php`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: id }), // Enviamos un objeto que contiene el id del vehiculo a eliminar
                        });

                        if (!response.ok) {
                            throw new Error('Error al eliminar el vehiculo');
                        }

                        const data = await response.json();

                        if (data.success) {
                            mostrarMensajeExito('Vehiculo eliminado con éxito.');
                            setTimeout(() => {
                                window.location.href = "vehiculos.php";
                            }, 2000);
                            this.cargarVehiculos(); // Actualizar la lista de clientes
                        } else {
                            mostrarMensajeError('Error al eliminar el vehiculo: ' + data.message);
                        }
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                mostrarMensajeError('Error al eliminar el cliente.');
            }
        }
    }
});

app.mount('#app');

const { createApp } = Vue;
createApp({
    data() {
        return {
            modelos: [],
            marcas: [],
            modelo: {
                id: null,
                nombre: '',
                marca_id: null
            },
            editando: false
        };
    },
    mounted() {
        this.obtenerModelos();
        this.obtenerMarcas();
        chequeo_permiso('vehiculos');

    },
    methods: {
        async obtenerModelos() {
            const response = await axios.get('api/modelos.php');
            this.modelos = response.data;
        },
        async obtenerMarcas() {
            const response = await axios.get('api/marcas.php');
            this.marcas = response.data;
        },
        async guardarModelo() {
            try {
                if (this.editando) {
                    await axios.put('api/modelos.php', JSON.stringify(this.modelo), {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    Swal.fire('Éxito', 'Modelo actualizado exitosamente', 'success');
                } else {
                    await axios.post('api/modelos.php', JSON.stringify(this.modelo), {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    Swal.fire('Éxito', 'Modelo creado exitosamente', 'success');
                }
                this.obtenerModelos();
                this.limpiarFormulario();
            } catch (error) {
                Swal.fire('Error', error.response ? error.response.data.error : error.message, 'error');
            }
        },
        editarModelo(modelo) {
            this.modelo = {
                id: modelo.id,
                nombre: modelo.nombre,
                marca_id: modelo.marca_id
            };
            this.editando = true;
        },
        async eliminarModelo(id) {
            try {
                await axios.delete('api/modelos.php', {
                    data: { id },
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                Swal.fire('Éxito', 'Modelo eliminado exitosamente', 'success');
                this.obtenerModelos();
            } catch (error) {
                Swal.fire('Error', error.response ? error.response.data.error : error.message, 'error');
            }
        },
        limpiarFormulario() {
            this.modelo = { id: null, nombre: '', marca_id: null };
            this.editando = false;
        }
    }
}).mount('#app');

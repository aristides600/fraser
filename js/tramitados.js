const { createApp } = Vue;

createApp({
    data() {
        return {
            patente: '', // Valor de búsqueda
            data: [],    // Almacenará todos los datos combinados
            sinCoincidencias: false // Para mostrar mensaje si no hay coincidencias
        };
    },
    mounted() {
        this.cargarDatos(); // Cargar todos los datos al iniciar
    },
    methods: {
        cargarDatos() {
            axios.get('api/tramitados.php', {
                params: {
                    action: 'data'
                }
            })
            .then(response => {
                this.data = response.data;
                this.sinCoincidencias = this.data.length === 0;
            })
            .catch(error => {
                console.error(error);
            });
        },
        obtenerDocumentos() {
            if (this.patente.length > 0) {
                axios.get('api/tramitados.php', {
                    params: {
                        action: 'data',
                        patente: this.patente
                    }
                })
                .then(response => {
                    this.data = response.data;
                    this.sinCoincidencias = this.data.length === 0;
                })
                .catch(error => {
                    console.error(error);
                });
            } else {
                this.cargarDatos(); // Cargar todos los datos si no hay búsqueda
            }
        }
    }
}).mount('#app');

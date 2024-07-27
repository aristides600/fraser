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
    },
    methods: {
        getMarcas() {
            axios.get('marcas.php')
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        addMarca() {
            axios.post('marcas.php', this.newMarca)
                .then(response => {
                    if (response.data.success) {
                        this.getMarcas();
                        this.newMarca.nombre = '';
                        this.errorMessage = '';
                    } else {
                        this.errorMessage = response.data.message;
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        updateMarca(marca) {
            axios.put('marcas.php', marca)
                .then(response => {
                    if (response.data.success) {
                        this.getMarcas();
                        this.errorMessage = '';
                    } else {
                        this.errorMessage = response.data.message;
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        deleteMarca(id) {
            axios.delete('marcas.php', { data: { id: id } })
                .then(response => {
                    this.getMarcas();
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
}).mount('#app');
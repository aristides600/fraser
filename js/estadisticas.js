const app = Vue.createApp({
    data() {
        return {
            vigentes: 0,
            vencidos: 0
        };
    },
    mounted() {
        this.getDocumentosData();
    },
    methods: {
        async getDocumentosData() {
            try {
                const response = await axios.get('api/estadisticas.php');
                this.vigentes = response.data.vigentes;
                this.vencidos = response.data.vencidos;
                this.renderChart();
            } catch (error) {
                console.error("Error al obtener los datos:", error);
            }
        },
        renderChart() {
            const ctx = document.getElementById('documentosChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Vigentes', 'Vencidos'],
                    datasets: [{
                        data: [this.vigentes, this.vencidos],
                        backgroundColor: ['#4CAF50', '#FF5252'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Documentos Vigentes vs Vencidos'
                        }
                    }
                }
            });
        }
    }
});

app.mount('#app');

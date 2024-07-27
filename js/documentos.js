const { createApp } = Vue;

createApp({
    data() {
        return {
            documentos: [],
            documento: {
                vehiculo_id: null,
                tipo_id: null,
                fecha_vencimiento: null,
                observacion: ''
            },
            editando: false,
            idActual: null,
            documentosPorVencer: []
        };
    },
    methods: {
        obtenerDocumentos() {
            axios.get('api/documentos.php')
                .then(response => {
                    this.documentos = response.data.sort((a, b) => new Date(a.fecha_vencimiento) - new Date(b.fecha_vencimiento));
                    this.verificarFechasDeVencimiento();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        verificarFechasDeVencimiento() {
            const hoy = new Date();
            this.documentosPorVencer = this.documentos.filter(doc => {
                const fechaVencimiento = new Date(doc.fecha_vencimiento);
                const diferenciaDias = (fechaVencimiento - hoy) / (1000 * 60 * 60 * 24);
                return diferenciaDias <= 7 && diferenciaDias > 0;
            }).map(doc => doc.id);

            this.documentosVencidos = this.documentos.filter(doc => {
                const fechaVencimiento = new Date(doc.fecha_vencimiento);
                const diferenciaDias = (fechaVencimiento - hoy) / (1000 * 60 * 60 * 24);
                return diferenciaDias < 0;
            }).map(doc => doc.id);

            if (this.documentosPorVencer.length > 0) {
                this.documentosPorVencer.forEach(id => {
                    const doc = this.documentos.find(doc => doc.id === id);
                    // Swal.fire({
                    //     title: '¡Atención!',
                    //     text: `El documento con patente ${doc.patente} está próximo a vencerse en ${Math.ceil((new Date(doc.fecha_vencimiento) - hoy) / (1000 * 60 * 60 * 24))} días.`,
                    //     icon: 'warning',
                    //     confirmButtonText: 'Aceptar'
                    // });
                });
            }
        },
        getRowClass(doc) {
            if (this.documentosPorVencer.includes(doc.id)) {
                return 'resaltar';
            } else if (this.documentosVencidos.includes(doc.id)) {
                return 'vencido';
            } else {
                return '';
            }
        },
        getRowClass(id) {
            return this.documentosPorVencer.includes(id) ? 'resaltar' : '';
        },
        formatFecha(fecha) {
            const [year, month, day] = fecha.split('-');
            return `${day}/${month}/${year}`;
        },

        editarDocumento(id) {
            window.location.href = "editar_documento.php?id=" + id;
        },
        eliminarDocumento(id) {

        },

        porVencer(fecha) {
            const hoy = new Date();
            const fechaVencimiento = new Date(fecha);
            const diferenciaDias = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            return diferenciaDias;
        },
    },
    mounted() {
        this.obtenerDocumentos();
    }
}).mount('#app');
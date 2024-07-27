const { createApp } = Vue;

createApp({
    data() {
        return {
            documentos: []
        };
    },
    methods: {
        obtenerDocumentos() {
            axios.get('api/documentos.php')
                .then(response => {
                    console.log(response.data); // Verifica los datos recibidos
                    
                    // Agrupamos los documentos por id
                    const documentosAgrupados = response.data.reduce((acc, doc) => {
                        // Si el id ya está en el acumulador, agregamos el color
                        if (!acc[doc.id]) {
                            acc[doc.id] = { ...doc, colores: [doc.color_nombre] };
                        } else {
                            acc[doc.id].colores.push(doc.color_nombre);
                        }
                        return acc;
                    }, {});
                    
                    // Convertimos el objeto agrupado en un array
                    this.documentos = Object.values(documentosAgrupados);
                    
                    // Procesamos los datos para incluir el estado y la clase CSS
                    this.documentos = this.documentos.map(doc => {
                        const hoy = new Date();
                        const fechaVencimiento = new Date(doc.fecha_vencimiento);
                        const diferenciaDias = (fechaVencimiento - hoy) / (1000 * 60 * 60 * 24);
                        
                        return {
                            ...doc,
                            diasPorVencer: Math.ceil(diferenciaDias),
                            estado: diferenciaDias > 0 ? (diferenciaDias <= 7 ? `Vence en ${Math.ceil(diferenciaDias)} días` : 'No') : `Vencido hace ${Math.abs(Math.floor(diferenciaDias))} días`,
                            class: diferenciaDias > 0 ? (diferenciaDias <= 7 ? 'resaltar' : '') : 'vencido'
                        };
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        },
        
        getRowClass(doc) {
            return doc.class || '';
        },
        formatFecha(fecha) {
            const [year, month, day] = fecha.split('-');
            return `${day}/${month}/${year}`;
        },
        editarDocumento(id) {
            window.location.href = "editar_documento.php?id=" + id;
        },
        eliminarDocumento(id) {
            // Confirmación del usuario antes de realizar la operación
            if (confirm('¿Estás seguro de que deseas desactivar este documento?')) {
                axios.post('api/documentos.php', {
                    id: id,
                    esta: false // Marcamos el documento como inactivo
                })
                .then(response => {
                    if (response.data.success) {
                        // Actualizamos el estado local del documento
                        this.documentos = this.documentos.map(doc =>
                            doc.id === id ? { ...doc, esta: false } : doc
                        );
                        alert('Documento desactivado con éxito');
                    } else {
                        alert('Error al desactivar el documento');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Error al comunicarse con el servidor');
                });
            }
        }
        
    },
    mounted() {
        this.obtenerDocumentos();
    }
}).mount('#app');

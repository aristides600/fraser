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
                    let documentos = Object.values(documentosAgrupados);

                    // Ordenamos los documentos por fecha de vencimiento
                    documentos.sort((a, b) => new Date(a.fecha_vencimiento) - new Date(b.fecha_vencimiento));

                    //Procesamos los datos para incluir el estado y la clase CSS
                    this.documentos = documentos.map(doc => {
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
        tramitarDocumento(id) {
            // Confirmación del usuario antes de realizar la operación
            if (confirm('¿Estás seguro de que deseas tramitar este documento?')) {
                axios.delete('api/documentos.php', {
                    data: { id: id, estado: false } // Enviamos el ID y el estado que queremos actualizar
                })
                .then(response => {
                    if (response.data.success) {
                        // Actualizamos el estado local del documento
                        this.documentos = this.documentos.map(doc =>
                            doc.id === id ? { ...doc, estado: false } : doc
                        );
                        alert('Documento tramitado con éxito');
                    } else {
                        alert('Error al tramitar el documento');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Error al comunicarse con el servidor');
                });
            }
        },
        
        
        nuevoDocumento() {
            window.location.href = "nuevo_documento.php";
        },

    },
    computed: {
        // Filtrar los documentos pendientes
        documentosPendientes() {
            return this.documentos.filter(doc => doc.estado === 1);
        },
        // Filtrar los documentos tramitados
        documentosTramitados() {
            return this.documentos.filter(doc => doc.estado === 0);
        }
    },
    mounted() {
        this.obtenerDocumentos();
    }
}).mount('#app');

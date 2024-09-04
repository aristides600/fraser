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
                            estado: diferenciaDias > 0 ? (diferenciaDias <= 10 ? `Vence en ${Math.ceil(diferenciaDias)} días` : 'No') : `Vencido hace ${Math.abs(Math.floor(diferenciaDias))} días`,
                            class: diferenciaDias > 0 ? (diferenciaDias <= 10 ? 'resaltar' : '') : 'vencido'
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
            Swal.fire({
                title: 'Tramitar Documento',
                text: "Ingrese una observación antes de tramitar:",
                input: 'text',
                inputPlaceholder: 'Observación',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tramitar',
                cancelButtonText: 'Cancelar',
                preConfirm: (observacion) => {
                    if (!observacion) {
                        Swal.showValidationMessage('Debe ingresar una observación');
                    }
                    return observacion;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const observacion = result.value;

                    // Crear un objeto FormData para enviar los datos en formato compatible con $_POST
                    const formData = new FormData();
                    formData.append('documento_id', id);
                    formData.append('observacion', observacion);

                    axios.post('api/tramitar_documento.php', formData)
                        .then(response => {
                            console.log(response.data); // Verificar la respuesta del servidor
                            if (response.data.success) {
                                Swal.fire('Tramitado', 'El documento ha sido tramitado con éxito.', 'success');
                                this.obtenerDocumentos(); // Actualizar la lista de documentos
                            } else {
                                Swal.fire('Error', response.data.error || 'Hubo un problema al tramitar el documento.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error', 'Ocurrió un error al tramitar el documento.', 'error');
                        });
                }
            });
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

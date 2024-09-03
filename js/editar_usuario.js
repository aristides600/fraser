const app = Vue.createApp({
    data() {
        return {
            usuario: {},
            roles: [],
        };
    },
    mounted() {
        const usuario_id = new URLSearchParams(window.location.search).get('id');
        console.log(usuario_id);
        this.obtenerUsuario(usuario_id);
        this.cargarRoles()

    },
    methods: {
        // Otros métodos existentes
        obtenerUsuario(usuario_id) {
            axios.get(`api/usuario_id.php?usuario_id=${usuario_id}`)
                .then(response => {
                    this.usuario = response.data;
                    console.log(this.usuario);
                })
                .catch(error => {
                    console.error('Error al cargar usuarios: ' + error);
                });
        },
        async cargarRoles() {
            axios.get('api/roles.php')
                .then(response => {
                    this.roles = response.data;
                    // console.log(this.usuarios);
                })
                .catch(error => {
                    console.error('Error al cargar roles: ' + error);
                });
        },

        verificarExistenciaUsuario() {
            return axios.post('api/verificar_usuario.php', {
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
        async guardarEdicion() {
            const existe = await this.verificarExistenciaUsuario();
            if (!existe) {
                axios.put('api/usuarios.php', {
                    id: this.usuario.id,
                    dni: this.usuario.dni,
                    apellido: this.usuario.apellido,
                    nombre: this.usuario.nombre,
                    usuario: this.usuario.usuario,
                    clave: this.usuario.clave,
                    rol_id: this.usuario.rol_id
                })
                    .then(() => {
                        mostrarMensajeExito("Usuario modificado.");
                        setTimeout(() => {
                            window.location.href = "usuarios.php";
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error al guardar los cambios: ' + error);
                    });
            } else {
                mostrarMensajeError("El usuario ya existe");
            }
        },
        cancelarEdicion() {
            window.location.href = "usuarios.php";
        }
    }
});

app.mount('#app');

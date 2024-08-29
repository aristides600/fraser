const app = Vue.createApp({
    data() {
        return {
            usuario: '',
            clave: ''
        };
    },
    methods: {
        login() {
            axios.post('api/login.php', {
                usuario: this.usuario,
                clave: this.clave
            })
                .then(response => {
                    if (response.data.success) {
                        // mostrarMensajeExito("Logeado exitosamente");
                        // Login exitoso, redireccionar a otra página

                        window.location.href = 'index.php';

                    } else {
                        mostrarMensajeError(response.data.message || "Datos incorrectos");
                    }
                })
                .catch(error => {
                    console.error('Error al iniciar sesión: ' + error);
                    mostrarMensajeError("Error al iniciar sesión");
                });
        }
    }
});

app.mount('#app');
// const app = Vue.createApp({
//     data() {
//         return {
//             usuario: '',
//             clave: '',
//             permisos: {}  // Almacenar permisos aquí
//         };
//     },
//     methods: {
//         login() {
//             axios.post('api/login.php', {
//                 usuario: this.usuario,
//                 clave: this.clave
//             })
//             .then(response => {
//                 if (response.data.success) {
//                     this.permisos = response.data.permisos;
//                     // Guardar permisos en localStorage
//                     localStorage.setItem('permisos', JSON.stringify(this.permisos));
//                     window.location.href = 'index.php';
//                 } else {
//                     mostrarMensajeError(response.data.message || "Datos incorrectos");
//                 }
//             })
//             .catch(error => {
//                 console.error('Error al iniciar sesión: ' + error);
//                 mostrarMensajeError("Error al iniciar sesión");
//             });
//         },
//         cerrarSesion() {
//             // Limpiar permisos de localStorage
//             localStorage.removeItem('permisos');
//             window.location.href = 'login.php';
//         }
//     },
//     mounted() {
//         // Cargar permisos desde localStorage si existen
//         const permisosGuardados = localStorage.getItem('permisos');
//         if (permisosGuardados) {
//             this.permisos = JSON.parse(permisosGuardados);
//         }
//     }
// });

// app.mount('#app');

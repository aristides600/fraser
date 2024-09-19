const app = Vue.createApp({
    data() {
        return {
            usuario: '',
            clave: '',
            timeoutID: null,
            tiempoInactividad: 20 * 60 * 1000 // 20 minutos en milisegundos
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
                    this.iniciarTemporizadorInactividad(); // Iniciar temporizador
                    window.location.href = 'index.php'; // Redireccionar al dashboard
                } else {
                    Swal.fire({
                        text: response.data.message || "Datos incorrectos",
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    text: "Error al iniciar sesión",
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        },
        iniciarTemporizadorInactividad() {
            this.resetearTemporizador(); // Reseteamos cada vez que se llama esta función
            window.addEventListener('mousemove', this.resetearTemporizador);
            window.addEventListener('keydown', this.resetearTemporizador);
        },
        resetearTemporizador() {
            clearTimeout(this.timeoutID);
            this.timeoutID = setTimeout(this.cerrarSesionPorInactividad, this.tiempoInactividad);
        },
        cerrarSesionPorInactividad() {
            Swal.fire({
                text: "Sesión cerrada por inactividad",
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            });
            window.location.href = 'logout.php'; // Redirigir a la página de logout
        }
    },
    mounted() {
        if (window.location.href.includes('index.php')) {
            this.iniciarTemporizadorInactividad();
        }
    }
});

app.mount('#app');

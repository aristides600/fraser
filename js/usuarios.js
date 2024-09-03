const app = Vue.createApp({
    data() {
        return {
            usuarios: [],
            roles: [],
            form: {
                id: null,
                dni: '',
                apellido: '',
                nombre: '',
                usuario: '',
                clave: '',
                rol_id: '',
                // estado: 1
            },
            modalTitle: '',
            modalAction: ''
        };
    },
    created() {
        this.fetchUsuarios();
        this.fetchRoles();
    },
    methods: {
        async fetchUsuarios() {
            try {
                const response = await axios.get('api/usuarios.php');
                this.usuarios = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchRoles() {
            try {
                const response = await axios.get('api/roles.php');
                this.roles = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        showModal(action, usuario = {}) {
            this.modalAction = action === 'create' ? 'Crear Usuario' : 'Actualizar Usuario';
            this.modalTitle = action === 'create' ? 'Agregar Usuario' : 'Editar Usuario';
            if (action === 'edit') {
                this.form = { ...usuario };
            } else {
                this.form = {
                    id: null,
                    dni: '',
                    apellido: '',
                    nombre: '',
                    usuario: '',
                    clave: '',
                    rol_id: '',
                    // estado: 1
                };
            }
            new bootstrap.Modal(document.getElementById('userModal')).show();
        },
        async submitForm() {
            try {
                if (this.form.id) {
                    // Actualizar
                    await axios.post('api/usuarios.php', { update: true, ...this.form });
                } else {
                    // Crear
                    await axios.post('api/usuarios.php', { create: true, ...this.form });
                }
                this.fetchUsuarios();
                const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
                modal.hide();
            } catch (error) {
                console.error(error);
            }
        },
        async deleteUsuario(id) {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                try {
                    await axios.post('api/usuarios.php', { delete: true, id });
                    this.fetchUsuarios();
                } catch (error) {
                    console.error(error);
                }
            }
        },
        getRolName(rol_id) {
            const rol = this.roles.find(r => r.id === rol_id);
            return rol ? rol.nombre : 'Desconocido';
        }

    }
});

app.mount('#app');
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container mt-5">
        <h1>Gestión de Usuarios</h1>
        <div class="mb-3">
            <button class="btn btn-primary" @click="showModal('create')">Agregar Usuario</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="usuario in usuarios" :key="usuario.id">
                    <td>{{ usuario.dni }}</td>
                    <td>{{ usuario.apellido }}</td>
                    <td>{{ usuario.nombre }}</td>
                    <td>{{ usuario.usuario }}</td>
                    <td>{{ getRolName(usuario.rol_id) }}</td>
                    <td>{{ usuario.estado ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" @click="showModal('edit', usuario)">Editar</button>
                        <button class="btn btn-danger btn-sm" @click="deleteUsuario(usuario.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm">
                            <input type="hidden" v-model="form.id">
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" v-model="form.dni" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" v-model="form.apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" v-model="form.nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" v-model="form.usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="clave" class="form-label">Clave</label>
                                <input type="password" class="form-control" id="clave" v-model="form.clave" required>
                            </div>
                            <div class="mb-3">
                                <label for="rol_id" class="form-label">Rol</label>
                                <select id="rol_id" class="form-select" v-model="form.rol_id" required>
                                    <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">{{ modalAction }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./cdn/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/usuarios.js"></script>

  
</body>

</html>

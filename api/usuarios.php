<?php
header('Content-Type: application/json');
include 'db.php';

function usuarioExists($campo, $valor) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE $campo = ?");
    $stmt->execute([$valor]);
    return $stmt->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $dni = $_POST['dni'];
        $apellido = $_POST['apellido'];
        $nombre = $_POST['nombre'];
        $usuario = $_POST['usuario'];
        $clave = password_hash($_POST['clave'], PASSWORD_BCRYPT);
        $rol_id = $_POST['rol_id'];
        $estado = 1;

        // Verificar duplicados
        if (usuarioExists('dni', $dni)) {
            echo json_encode(['error' => 'El DNI ya está registrado.']);
            exit;
        }
        if (usuarioExists('usuario', $usuario)) {
            echo json_encode(['error' => 'El usuario ya está registrado.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (dni, apellido, nombre, usuario, clave, rol_id, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$dni, $apellido, $nombre, $usuario, $clave, $rol_id, $estado])) {
            echo json_encode(['success' => 'Usuario creado exitosamente.']);
        } else {
            echo json_encode(['error' => 'Error al crear el usuario.']);
        }
    }
    elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $dni = $_POST['dni'];
        $apellido = $_POST['apellido'];
        $nombre = $_POST['nombre'];
        $usuario = $_POST['usuario'];
        $rol_id = $_POST['rol_id'];

        $stmt = $pdo->prepare("UPDATE usuarios SET dni = ?, apellido = ?, nombre = ?, usuario = ?, rol_id = ? WHERE id = ?");
        if ($stmt->execute([$dni, $apellido, $nombre, $usuario, $rol_id, $id])) {
            echo json_encode(['success' => 'Usuario actualizado exitosamente.']);
        } else {
            echo json_encode(['error' => 'Error al actualizar el usuario.']);
        }
    }
    elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => 'Usuario eliminado exitosamente.']);
        } else {
            echo json_encode(['error' => 'Error al eliminar el usuario.']);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($usuarios);
}
?>

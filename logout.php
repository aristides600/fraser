<?php
// session_start();
// session_destroy();
// echo json_encode(['success' => true]);
// exit();

// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir a la página de login
header("Location: login.php");
exit();

?>

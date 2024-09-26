<?php


// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir a la página de login
header("Location: login.php");
exit();



// session_start();

// // Destruir la sesión
// session_unset();
// session_destroy();

// // Devolver una respuesta JSON si es necesario
// echo json_encode([
//     'success' => true,
//     'message' => 'Sesión cerrada por inactividad'
// ]);

?>

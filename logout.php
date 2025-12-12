<?php
session_start();

//Vaciar el array de sesión
$_SESSION = array();

//Borrar la cookie de sesión del navegador (Limpieza profunda)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

//Destruir la sesión en el servidor
session_destroy();

//Redirigir al login
header("Location: login.php"); // Ojo con la ruta según donde esté tu archivo
exit();
?>
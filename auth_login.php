<?php
/*
    LOGICA DE INICIO DE SESIÓN
    --------------------------
    Recibe los datos del formulario y decide si dejar entrar al usuario.
*/

// Iniciamos la Sesión (Para que el sistema recuerde quién eres mientras navegas)
session_start();

// Incluimos la conexión
require 'includes/conexion.php';

// Verificamos que vengan datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario_form = $_POST['usuario'];
    $password_form = $_POST['password'];

    // Preparamos la consulta SQL (Buscamos al usuario por su nombre)
    // Usamos :usuario para evitar Hackeos (Inyección SQL)
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario' => $usuario_form]);
    
    // Obtenemos el resultado
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificamos si el usuario existe y si la contraseña coincide
    // NOTA: Como en la BD guardamos texto plano por ahora, comparamos directo con '=='.
    // En el futuro, usaremos password_verify() aquí.
    if ($user && $user['password'] == $password_form) {
        
        // ¡ÉXITO! Guardamos datos en la sesión del navegador
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre']  = $user['nombre_completo'];
        $_SESSION['rol']     = $user['rol'];

        //REDIRECCIONAMOS SEGÚN EL ROL
        // Rol 1 = Dueño -> Va al Dashboard
        // Rol 2 = Chofer -> Va al Escáner
        if ($user['rol'] == 1) {
            header("Location: dashboard.php");
        } else {
            header("Location: escaner.php");
        }
        exit();

    } else {
        // FALLÓ: Lo regresamos al login con un error
        header("Location: login.php?error=1");
        exit();
    }
}
?>
<?php
session_start();
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

// Si ya está logueado, redirigir
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['rol'] == 1) { header("Location: dashboard.php"); }
    else { header("Location: escaner.php"); }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Transporte</title>
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body class="bg-fondo h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl flex max-w-4xl w-full overflow-hidden min-h-[500px]">

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Iniciar Sesión</h2>
                <p class="text-gray-400 text-sm mt-2">Ingresa tus credenciales para acceder</p>
            </div>

            <form action="auth_login.php" method="POST">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Usuario</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input type="text" name="usuario" placeholder="Ej: admin" required class="w-full bg-gray-100 px-10 py-3 rounded-lg border border-transparent focus:border-marca focus:bg-white focus:outline-none transition">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Contraseña</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input type="password" name="password" placeholder="********" required class="w-full bg-gray-100 px-10 py-3 rounded-lg border border-transparent focus:border-marca focus:bg-white focus:outline-none transition">
                    </div>
                </div>
                <!-- aqui va un link para solicitar la creacion de usuario a IT-->
                 <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Solicitar creación de usuario</a>

                <?php if(isset($_GET['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-estado-error text-red-700 p-3 mb-6 text-sm rounded">
                        Usuario o contraseña incorrectos
                    </div>
                <?php endif; ?>

                <button type="submit" 
                        class="w-full bg-marca text-white font-bold py-3 px-4 rounded-lg hover:bg-marca-oscuro transition duration-300 transform active:scale-95 shadow-md">
                    INGRESAR
                </button>

            </form>
        </div>

        <div class="hidden md:flex w-1/2 bg-marca-subcolor flex-col justify-center items-center p-12 text-white relative">
            
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 rounded-full bg-white opacity-10"></div>
            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-white opacity-10"></div>

            <div class="text-center z-10">
                <h2 class="text-4xl font-bold mb-4">¡Bienvenido!</h2>
                <p class="text-blue-100 mb-6">Sistema de Control de Transporte Escolar</p>
                
                <div class="bg-white bg-opacity-20 p-6 rounded-full inline-block mb-4">
                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                
                <p class="text-sm opacity-80">Gestión segura y eficiente.</p>
            </div>
        </div>

    </div>
        <script>
        // Empuja un estado nuevo al historial cada vez que carga
        history.pushState(null, null, location.href);
        
        // Si el usuario intenta volver atrás...
        window.onpopstate = function () {
            // lo volvemos a empujar al frente (al Login)
            history.go(1);
        };
        </script>

</body>
</html>
</body>
</html>
<?php

require_once 'includes/seguridad.php'; // <--- ESTA LÍNEA HACE TODO EL TRABAJO
require_once 'includes/config.php';
require_once 'includes/conexion.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// LOGICA: SI SE ENVIA EL FORMULARIO
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre'];
    $usuario  = $_POST['usuario'];
    $password = $_POST['password']; 
    $rol      = $_POST['rol']; // 1=Admin, 2=Chofer

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    
    if ($stmt->rowCount() > 0) {
        $mensaje = "<p class='text-red-500 font-bold'> El usuario '$usuario' ya existe.</p>";
    } else {
        // INSERTAR NUEVO USUARIO
        // Nota: Aquí guardamos la password tal cual para seguir tu ritmo, 
        // pero luego usaremos password_hash() para seguridad.
        $sql = "INSERT INTO usuarios (nombre_completo, usuario, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nombre, $usuario, $password, $rol])) {
            $mensaje = "<p class='text-green-600 font-bold'>¡Empleado registrado con éxito!</p>";
        } else {
            $mensaje = "<p class='text-red-500'> Error al registrar.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empleado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex">
    <main class="flex-1 p-8">
        <h2 class="text-2xl font-bold mb-6">Registrar Nuevo Empleado</h2>
        
        <?php echo $mensaje; ?>

        <div class="bg-white p-6 rounded shadow-md max-w-lg">
            <form method="POST">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nombre Completo</label>
                    <input type="text" name="nombre" required class="w-full border p-2 rounded">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Usuario (Login)</label>
                        <input type="text" name="usuario" required class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Contraseña</label>
                        <input type="password" name="password" required class="w-full border p-2 rounded">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Rol / Puesto</label>
                    <select name="rol" class="w-full border p-2 rounded bg-white">
                        <option value="2">Chofer (Registra Asistencia)</option>
                        <option value="1">Administrador (Dueño)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-marca text-white font-bold py-2 rounded hover:bg-marca-oscuro">
                    Guardar Empleado
                </button>

            </form>
        </div>
    </main>

</body>

</html>

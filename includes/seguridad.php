<?php
session_start();
// Esto obliga al navegador a no guardar la página en el historial visual.
// Si dan "Atrás", el navegador recargará y el sistema los expulsará.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado

/* ---------------------------------------------------------
   CONFIGURACIÓN DE PERMISOS (El Cerebro del Sistema)
   ---------------------------------------------------------
   Aquí defines qué páginas puede ver cada quién.
*/

//Páginas que puede ver el DUEÑO (Rol 1)
$paginas_admin = [
    'dashboard.php',
    'registro_alumno.php',
    'registrar_empleado.php',
    'reportes.php',
    'perfil.php',
    'buses.php',
    'alumnos.php',
    'editar_alumno.php'
];

//Páginas que puede ver el CHOFER (Rol 2)
$paginas_chofer = [
    'escaner.php',
    'perfil.php'
];

//Obtenemos el nombre del archivo actual
$pagina_actual = basename($_SERVER['PHP_SELF']);


/* ---------------------------------------------------------
   LÓGICA DE VALIDACIÓN (El Guardia)
   ---------------------------------------------------------
*/

// CASO A: Si el usuario NO está logueado...
if (!isset($_SESSION['user_id'])) {
    // Si intenta entrar a cualquier página que no sea el login...
    if ($pagina_actual != 'login.php' && $pagina_actual != 'auth_login.php') {
        header("Location: login.php");
        exit();
    }
} 
// CASO B: Si el usuario SÍ está logueado...
else {
    $rol_usuario = $_SESSION['rol'];

    // Si intenta volver al login estando ya dentro, lo mandamos a su casa
    if ($pagina_actual == 'login.php') {
        if ($rol_usuario == 1) { header("Location: dashboard.php"); }
        else { header("Location: escaner.php"); }
        exit();
    }

    // VALIDACIÓN DE ROLES
    
    // Si es DUEÑO (1) y la página NO está en su lista...
    if ($rol_usuario == 1 && !in_array($pagina_actual, $paginas_admin)) {
        // Lo mandamos a su pantalla principal
        header("Location: dashboard.php");
        exit();
    }

    // Si es CHOFER (2) y la página NO está en su lista...
    if ($rol_usuario == 2 && !in_array($pagina_actual, $paginas_chofer)) {
        // Lo sacamos de ahí y lo mandamos al escáner
        header("Location: escaner.php");
        exit();
    }
}
?>
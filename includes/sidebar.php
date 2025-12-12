<?php
// Obtenemos el nombre del archivo actual (ej: dashboard.php)
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-64 bg-gray-800 text-white flex flex-col hidden md:flex">
    
    <div class="h-16 flex items-center justify-center border-b border-gray-700">
        <h1 class="text-xl font-bold">Transportes-QR</h1>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-2">
        
        <a href="dashboard.php" 
           class="flex items-center px-4 py-3 rounded-md transition <?php echo ($pagina_actual == 'dashboard.php') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            Panel Principal
        </a>

        <a href="registro_alumno.php" 
           class="flex items-center px-4 py-3 rounded-md transition <?php echo ($pagina_actual == 'registro_alumno.php') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Registrar Alumno
        </a>

        <a href="registrar_empleado.php" 
           class="flex items-center px-4 py-3 rounded-md transition <?php echo ($pagina_actual == 'registrar_empleado.php') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">
           <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Registrar Empleado
        </a>

        <a href="reportes.php" class="flex items-center px-4 py-3 hover:bg-gray-700 rounded-md text-gray-300 hover:text-white transition">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Reportes
        </a>

        <a href="buses.php" class="flex items-center px-4 py-3 rounded-md transition <?php echo ($pagina_actual == 'buses.php') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Gestión de Transporte
        </a>

        <a href="alumnos.php" class="flex items-center px-4 py-3 rounded-md transition <?php echo ($pagina_actual == 'alumnos.php') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Directorio de Estudiantes
        </a>

    </nav>

    <div class="p-4 border-t border-gray-700">
        <a href="logout.php" class="block w-full py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded text-center">
            Cerrar Sesión
        </a>
    </div>
</aside>
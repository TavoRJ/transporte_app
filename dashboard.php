<?php
require_once 'includes/seguridad.php';
// Validar que sea Admin (Rol 1)
if ($_SESSION['rol'] != 1) { header("Location: escaner.php"); exit(); }

require_once 'includes/config.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

//CONSULTAS PARA LAS TARJETAS (ESTADÍSTICAS)

// A. Total Alumnos
$stmt = $pdo->query("SELECT COUNT(*) FROM alumnos");
$total_alumnos = $stmt->fetchColumn();

// B. Asistencias de HOY (Usamos la función SQL CURDATE() para obtener la fecha actual)
$stmt = $pdo->query("SELECT COUNT(*) FROM asistencias WHERE DATE(fecha_hora) = CURDATE()");
$total_asistencias_hoy = $stmt->fetchColumn();

// C. Total Buses
$stmt = $pdo->query("SELECT COUNT(*) FROM buses");
$total_buses = $stmt->fetchColumn();


//CONSULTA PARA LA TABLA (REGISTROS DE HOY)

$sql_tabla = "SELECT a.fecha_hora, a.tipo, 
                     al.nombre_completo, al.institucion,
                     b.nombre_bus
              FROM asistencias a
              INNER JOIN alumnos al ON a.alumno_id = al.id
              INNER JOIN buses b ON a.bus_id = b.id
              WHERE DATE(a.fecha_hora) = CURDATE() 
              ORDER BY a.fecha_hora DESC"; // Los más recientes primero

$stmt = $pdo->query($sql_tabla);
$registros_hoy = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --- 3. INCLUIMOS EL DISEÑO ---
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-fondo">
    
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">
            Bienvenido, <?php echo $_SESSION['nombre']; ?>
        </h2>
        <div class="text-sm text-gray-500">Panel de Control</div>
    </header>

    <div class="p-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-marca">
                <div class="text-gray-500 text-sm font-bold uppercase">Total Alumnos</div>
                <div class="text-3xl font-bold text-gray-800 mt-2">
                    <?php echo $total_alumnos; ?>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-estado-exito">
                <div class="text-gray-500 text-sm font-bold uppercase">Asistencias Hoy</div>
                <div class="text-3xl font-bold text-gray-800 mt-2">
                    <?php echo $total_asistencias_hoy; ?>
                </div>
            </div>

             <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-estado-alerta">
                <div class="text-gray-500 text-sm font-bold uppercase">Buses Registrados</div>
                <div class="text-3xl font-bold text-gray-800 mt-2">
                    <?php echo $total_buses; ?>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 text-lg">Actividad Reciente (Hoy)</h3>
                <span class="text-xs bg-marca text-white px-2 py-1 rounded-full">En tiempo real</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Hora
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Alumno
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Bus
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($registros_hoy) > 0): ?>
                            <?php foreach ($registros_hoy as $fila): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-gray-900 font-bold">
                                                <?php echo date("h:i A", strtotime($fila['fecha_hora'])); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-semibold">
                                            <?php echo $fila['nombre_completo']; ?>
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            <?php echo $fila['institucion']; ?>
                                        </p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <span class="text-gray-700 bg-gray-200 px-2 py-1 rounded text-xs">
                                            <?php echo $fila['nombre_bus']; ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <?php if ($fila['tipo'] == 'ENTRADA'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-estado-exito">
                                                <svg class="w-2 h-2 mr-1 bg-estado-exito rounded-full" fill="currentColor" viewBox="0 0 8 8"></svg>
                                                ENTRADA
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-estado-alerta">
                                                <svg class="w-2 h-2 mr-1 bg-estado-alerta rounded-full" fill="currentColor" viewBox="0 0 8 8"></svg>
                                                SALIDA
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-5 py-10 border-b border-gray-200 bg-white text-sm text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-lg font-semibold">Sin movimientos hoy</p>
                                        <p class="text-sm">Los registros aparecerán aquí cuando el bus empiece a trabajar.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
?>
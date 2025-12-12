<?php
require_once 'includes/seguridad.php';
// Solo el Admin (Rol 1) puede ver reportes
if ($_SESSION['rol'] != 1) { header("Location: escaner.php"); exit(); }

require_once 'includes/config.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

//FILTRO DE FECHA (Por defecto: HOY)
$fecha_filtro = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

//CONSULTA SQL AVANZADA
// Unimos (JOIN) la tabla asistencias con alumnos y buses para traer los nombres reales
$sql = "SELECT a.fecha_hora, a.tipo, 
               al.nombre_completo, al.institucion,
               b.nombre_bus, b.placa
        FROM asistencias a
        INNER JOIN alumnos al ON a.alumno_id = al.id
        INNER JOIN buses b ON a.bus_id = b.id
        WHERE DATE(a.fecha_hora) = :fecha
        ORDER BY a.fecha_hora DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':fecha' => $fecha_filtro]);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Incluimos el diseño
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reporte de Asistencia</h2>
        
        <form method="GET" class="flex items-center bg-white p-2 rounded shadow">
            <label class="mr-2 text-sm font-bold text-gray-600">Ver fecha:</label>
            <input type="date" name="fecha" value="<?php echo $fecha_filtro; ?>" 
                   class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-marca">
            <button type="submit" class="ml-2 bg-marca text-white px-4 py-1 rounded hover:bg-marca-oscuro text-sm">
                Filtrar
            </button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Hora
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Alumno / Institución
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Bus
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Movimiento
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($registros) > 0): ?>
                    <?php foreach ($registros as $fila): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap font-bold">
                                    <?php echo date("h:i A", strtotime($fila['fecha_hora'])); ?>
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    <div>
                                        <p class="text-gray-900 whitespace-no-wrap font-semibold">
                                            <?php echo $fila['nombre_completo']; ?>
                                        </p>
                                        <p class="text-gray-500 text-xs">
                                            <?php echo $fila['institucion']; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo $fila['nombre_bus']; ?></p>
                                <p class="text-gray-500 text-xs"><?php echo $fila['placa']; ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <?php if ($fila['tipo'] == 'ENTRADA'): ?>
                                    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                        <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                        <span class="relative">ENTRADA</span>
                                    </span>
                                <?php else: ?>
                                    <span class="relative inline-block px-3 py-1 font-semibold text-orange-900 leading-tight">
                                        <span aria-hidden class="absolute inset-0 bg-estado-alerta opacity-50 rounded-full"></span>
                                        <span class="relative">SALIDA</span>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No hay registros para esta fecha.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<?php include 'includes/footer.php'; ?>
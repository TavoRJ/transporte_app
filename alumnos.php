<?php
require_once 'includes/seguridad.php';
// Solo Admin
if ($_SESSION['rol'] != 1) { header("Location: escaner.php"); exit(); }

require_once 'includes/config.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php'; // Para limpiar datos

$mensaje = "";

//LÓGICA DE ELIMINACIÓN
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    
    $id_alumno = limpiar($_GET['id']);

    // Preparamos la eliminación
    // IMPORTANTE: Al borrar al alumno, MySQL borrará automáticamente sus asistencias
    // gracias a la configuración "ON DELETE CASCADE" que pusimos al crear la tabla.
    $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = ?");
    
    if ($stmt->execute([$id_alumno])) {
        // Redirigimos a la misma página para limpiar la URL y mostrar éxito
        header("Location: alumnos.php?msg=eliminado");
        exit();
    } else {
        $mensaje = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4'>❌ Error al eliminar el alumno.</div>";
    }
}

// Mensaje de éxito al volver de la redirección
if (isset($_GET['msg']) && $_GET['msg'] == 'eliminado') {
    $mensaje = "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4'>✅ Alumno eliminado correctamente del sistema.</div>";
}
if (isset($_GET['msg']) && $_GET['msg'] == 'editado') {
    $mensaje = "<div class='bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4'>✏️ Datos del alumno actualizados correctamente.</div>";
}   


// --- CONSULTA LISTADO ---
$sql = "SELECT a.*, b.nombre_bus 
        FROM alumnos a 
        LEFT JOIN buses b ON a.bus_asignado_id = b.id 
        ORDER BY a.nombre_completo ASC";
$alumnos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-fondo p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Directorio de Estudiantes</h2>
    
    <?php echo $mensaje; ?>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <th class="px-5 py-3 text-left">Estudiante</th>
                    <th class="px-5 py-3 text-left">Grado / Institución</th>
                    <th class="px-5 py-3 text-left">Bus Asignado</th>
                    <th class="px-5 py-3 text-center">Contacto</th>
                    <th class="px-5 py-3 text-center">Acciones</th> </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alu): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                        
                        <td class="px-5 py-4 font-bold text-gray-800">
                            <?php echo $alu['nombre_completo']; ?>
                            <div class="text-xs text-gray-400 font-normal mt-1">ID: <?php echo $alu['codigo_qr']; ?></div>
                        </td>
                        
                        <td class="px-5 py-4">
                            <p class="font-semibold"><?php echo $alu['grado']; ?></p>
                            <p class="text-xs text-gray-500"><?php echo $alu['institucion']; ?></p>
                        </td>
                        
                        <td class="px-5 py-4 text-marca font-semibold">
                            <?php echo $alu['nombre_bus'] ?? '<span class="text-gray-400 italic">Sin asignar</span>'; ?>
                        </td>
                        
                        <td class="px-5 py-4 text-center">
                            <?php if ($alu['telefono_emergencia']): ?>
                                <a href="tel:<?php echo $alu['telefono_emergencia']; ?>" class="inline-flex items-center bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold hover:bg-green-200 transition">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                                    <?php echo $alu['telefono_emergencia']; ?>
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-5 py-4 text-center">

                            <a href="editar_alumno.php?id=<?php echo $alu['id']; ?>" class="text-blue-500 hover:text-blue-700 transition mr-3" title="Editar Datos">
                                <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                             </a>
                            
                            <a href="alumnos.php?accion=eliminar&id=<?php echo $alu['id']; ?>" 
                               onclick="return confirm('¡ATENCIÓN!\n\n¿Estás seguro de que quieres ELIMINAR a este alumno?\n\nAl hacerlo, se borrará todo su historial de asistencia permanentemente.\n\nEsta acción no se puede deshacer.');"
                               class="text-red-500 hover:text-red-700 transition" 
                               title="Eliminar Estudiante">
                                
                                <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>

                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
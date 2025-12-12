<?php
require_once 'includes/seguridad.php';
// Solo Admin
if ($_SESSION['rol'] != 1) { header("Location: escaner.php"); exit(); }

require_once 'includes/config.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

$mensaje = "";

//GUARDAR NUEVO BUS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_bus'])) {
    $placa  = limpiar($_POST['placa']);
    $nombre = limpiar($_POST['nombre']);
    $chofer = limpiar($_POST['chofer_id']); // ID del usuario chofer

    $sql = "INSERT INTO buses (placa, nombre_bus, chofer_id, activo) VALUES (?, ?, ?, 1)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$placa, $nombre, $chofer])) {
        $mensaje = "<p class='text-estado-exito font-bold'>Bus creado correctamente</p>";
    } else {
        $mensaje = "<p class='text-estado-alerta font-bold'>Error al crear bus</p>";
    }
}

//CAMBIAR ESTADO (ACTIVAR/DESACTIVAR)
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = limpiar($_GET['id']);
    $nuevo_estado = ($_GET['accion'] == 'activar') ? 1 : 0;
    
    $stmt = $pdo->prepare("UPDATE buses SET activo = ? WHERE id = ?");
    $stmt->execute([$nuevo_estado, $id]);
    header("Location: buses.php"); // Recargar para limpiar URL
    exit();
}

// CONSULTAR BUSES Y CHOFERES
//traemos los buses con el nombre del chofer
$sql_buses = "SELECT b.*, u.nombre_completo as nombre_chofer 
              FROM buses b 
              LEFT JOIN usuarios u ON b.chofer_id = u.id 
              ORDER BY b.id DESC";
$lista_buses = $pdo->query($sql_buses)->fetchAll(PDO::FETCH_ASSOC);

//Traemos usuarios que sean CHOFERES (Rol 2) para el select
$lista_choferes = $pdo->query("SELECT id, nombre_completo FROM usuarios WHERE rol = 2")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-fondo p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestión de Transporte</h2>
    <?php echo $mensaje; ?>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8 border-l-4 border-marca">
        <h3 class="font-bold text-lg mb-4 text-gray-700">Agregar Nueva Unidad</h3>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-bold text-gray-600">Nombre Unidad</label>
                <input type="text" name="nombre" placeholder="Ej: Bus Escolar #5" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600">Placa</label>
                <input type="text" name="placa" placeholder="Ej: P-555-ABC" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600">Chofer Encargado</label>
                <select name="chofer_id" class="w-full border p-2 rounded bg-white">
                    <?php foreach ($lista_choferes as $chofer): ?>
                        <option value="<?php echo $chofer['id']; ?>"><?php echo $chofer['nombre_completo']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="crear_bus" class="bg-marca hover:bg-marca-oscuro text-white font-bold py-2 px-4 rounded">
                Guardar Bus
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Placa</th>
                    <th class="px-5 py-3 text-left">Chofer</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_buses as $bus): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-5 py-4 text-sm font-bold"><?php echo $bus['nombre_bus']; ?></td>
                        <td class="px-5 py-4 text-sm text-gray-500"><?php echo $bus['placa']; ?></td>
                        <td class="px-5 py-4 text-sm"><?php echo $bus['nombre_chofer'] ?? 'Sin asignar'; ?></td>
                        <td class="px-5 py-4 text-center">
                            <?php if ($bus['activo']): ?>
                                <span class="bg-green-100 text-estado-exito px-2 py-1 rounded-full text-xs font-bold">ACTIVO</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-estado-error px-2 py-1 rounded-full text-xs font-bold">INACTIVO</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <?php if ($bus['activo']): ?>
                                <a href="buses.php?accion=desactivar&id=<?php echo $bus['id']; ?>" class="text-xs text-red-500 hover:underline">Desactivar</a>
                            <?php else: ?>
                                <a href="buses.php?accion=activar&id=<?php echo $bus['id']; ?>" class="text-xs text-green-500 hover:underline">Habilitar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<?php include 'includes/footer.php'; ?>
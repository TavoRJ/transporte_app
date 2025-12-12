<?php
require_once 'includes/seguridad.php';
if ($_SESSION['rol'] != 1) { header("Location: escaner.php"); exit(); }

require_once 'includes/config.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

$mensaje = "";
$alumno = null;

// 1. VERIFICAR QUE VENGA UN ID
if (!isset($_GET['id'])) {
    header("Location: alumnos.php");
    exit();
}

$id_alumno = limpiar($_GET['id']);

// 2. PROCESAR EL FORMULARIO (GUARDAR CAMBIOS)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nombre      = limpiar($_POST['nombre']);
    $grado       = limpiar($_POST['grado']);
    $institucion = limpiar($_POST['institucion']);
    $telefono    = limpiar($_POST['telefono']);
    $bus_id      = limpiar($_POST['bus_id']);
    
    // Si el bus viene vacío, guardamos NULL en la base de datos
    $bus_final = ($bus_id == "") ? NULL : $bus_id;

    $sql = "UPDATE alumnos 
            SET nombre_completo = ?, grado = ?, institucion = ?, telefono_emergencia = ?, bus_asignado_id = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nombre, $grado, $institucion, $telefono, $bus_final, $id_alumno])) {
        // Redirigir con mensaje de éxito
        header("Location: alumnos.php?msg=editado");
        exit();
    } else {
        $mensaje = "<div class='bg-red-100 text-estado-error p-4 mb-4 rounded'>❌ Error al actualizar.</div>";
    }
}

// 3. OBTENER DATOS ACTUALES DEL ALUMNO
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
$stmt->execute([$id_alumno]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    echo "Alumno no encontrado";
    exit();
}

// 4. TRAER LISTA DE BUSES (Para el select)
$buses = $pdo->query("SELECT id, nombre_bus FROM buses WHERE activo = 1")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-fondo p-6">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Editar Estudiante</h2>
        <a href="alumnos.php" class="text-gray-500 hover:text-marca transition">⬅ Volver al directorio</a>
    </div>

    <?php echo $mensaje; ?>

    <div class="bg-white rounded-lg shadow-md p-8 border-t-4 border-marca max-w-4xl mx-auto">
        
        <form method="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Nombre Completo</label>
                    <input type="text" name="nombre" value="<?php echo $alumno['nombre_completo']; ?>" required 
                           class="w-full border border-gray-300 p-2 rounded focus:border-marca focus:ring-1 focus:ring-marca outline-none">
                </div>

                <div>
                    <label class="block text-gray-400 font-bold mb-2">ID / Carnet (No editable)</label>
                    <input type="text" value="<?php echo $alumno['codigo_qr']; ?>" disabled 
                           class="w-full border border-gray-200 bg-gray-100 p-2 rounded text-gray-500 cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Institución</label>
                    <input type="text" name="institucion" value="<?php echo $alumno['institucion']; ?>" required class="w-full border border-gray-300 p-2 rounded focus:border-marca outline-none">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Grado / Grupo</label>
                    <select name="grado" class="w-full border border-gray-300 p-2 rounded bg-white focus:border-marca outline-none">
                        <option value="1ro Primaria" <?php echo ($alumno['grado'] == '1ro Primaria') ? 'selected' : ''; ?>>1ro Primaria</option>
                        <option value="2do Primaria" <?php echo ($alumno['grado'] == '2do Primaria') ? 'selected' : ''; ?>>2do Primaria</option>
                        <option value="3ro Primaria" <?php echo ($alumno['grado'] == '3ro Primaria') ? 'selected' : ''; ?>>3ro Primaria</option>
                        <option value="Transporte A" <?php echo ($alumno['grado'] == 'Transporte A') ? 'selected' : ''; ?>>Transporte A</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Teléfono Emergencia</label>
                    <input type="text" name="telefono" value="<?php echo $alumno['telefono_emergencia']; ?>" 
                           class="w-full border border-gray-300 p-2 rounded focus:border-marca outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Bus Asignado</label>
                    <select name="bus_id" class="w-full border border-gray-300 p-2 rounded bg-white focus:border-marca outline-none">
                        <option value="">-- Sin asignar --</option>
                        <?php foreach ($buses as $bus): ?>
                            <option value="<?php echo $bus['id']; ?>" 
                                <?php echo ($alumno['bus_asignado_id'] == $bus['id']) ? 'selected' : ''; ?>>
                                <?php echo $bus['nombre_bus']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="alumnos.php" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-semibold">Cancelar</a>
                <button type="submit" class="bg-marca hover:bg-marca-oscuro text-white font-bold py-2 px-6 rounded shadow transition">
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>

</main>

<?php include 'includes/footer.php'; ?>
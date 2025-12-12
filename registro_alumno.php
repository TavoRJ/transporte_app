<?php
//SEGURIDAD Y CONEXIONES
require_once 'includes/config.php';  // <--- El _once es la clave
require_once 'includes/seguridad.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

// Incluimos la librería del QR (El pincel)
require 'includes/phpqrcode/qrlib.php';

$mensaje = "";
$qr_mostrado = ""; // Aquí guardaremos la ruta para mostrarla si se crea bien
//LÓGICA: SI ALGUIEN PRESIONA "GUARDAR"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Limpiamos los datos (Seguridad)
    $nombre      = limpiar($_POST['nombre']);
    $apellidos   = limpiar($_POST['apellidos']);
    $nombre_completo = $nombre . " " . $apellidos;
    $institucion = limpiar($_POST['institucion']);
    $grado       = limpiar($_POST['grado']);
    $carnet      = limpiar($_POST['carnet']); // Este será el código único
    $telefono = limpiar($_POST['telefono']);
    $bus_id   = limpiar($_POST['bus_id']);

    // Verificamos que el carnet no exista ya
    $stmt = $pdo->prepare("SELECT id FROM alumnos WHERE codigo_qr = ?");
    $stmt->execute([$carnet]);
    

    if ($stmt->rowCount() > 0) {
        $mensaje = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4'>❌ Error: Ese número de carnet ya existe.</div>";
    } else {
        
        // --- AQUÍ OCURRE LA MAGIA DEL QR ---
        
        // A. Definimos dónde se guardará
        $nombre_archivo = "qr_" . $carnet . ".png";
        $ruta_guardado  = "img/qrs/" . $nombre_archivo;
        
        // B. Creamos el QR
        // Sintaxis: QRcode::png(Texto, Archivo, NivelCorrección, Tamaño, Margen)
        QRcode::png($carnet, $ruta_guardado, 'L', 10, 2);

        // --- FIN DE LA MAGIA ---

        // C. Guardamos en Base de Datos
       $sql = "INSERT INTO alumnos (nombre_completo, grado, institucion, codigo_qr, telefono_emergencia, bus_asignado_id) VALUES (?, ?, ?, ?, ?, ?)";
       $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nombre_completo, $grado, $institucion, $carnet, $telefono, $bus_id])) {
            $mensaje = "<div class='bg-estado border-l-4 border-estado- text-green-700 p-4 mb-4'>¡Alumno registrado y QR generado!</div>";
            $qr_mostrado = $ruta_guardado; // Para mostrarlo en pantalla
        } else {
            $mensaje = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4'>Error al guardar en base de datos.</div>";
        }
    }
}

//INCLUIMOS LA VISTA (Header y Sidebar)
include 'includes/header.php';
include 'includes/sidebar.php';
$buses_activos = $pdo->query("SELECT id, nombre_bus FROM buses WHERE activo = 1")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
    
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Nuevo Estudiante</h2>
    <?php echo $mensaje; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="registro_alumno.php"> 
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-marca">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Apellidos</label>
                        <input type="text" name="apellidos" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-marca">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Número de Carnet / ID (Único)</label>
                    <input type="text" name="carnet" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-marca" placeholder="Ej: 2025001">
                    <p class="text-xs text-gray-500 mt-1">Este ID será el contenido del código QR.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Institución</label>
                        <input type="text" name="institucion" required class="w-full border border-gray-300 p-2 rounded" placeholder="Ej: Colegio San Juan">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Grado / Grupo</label>
                        <select name="grado" class="w-full border border-gray-300 p-2 rounded bg-white">
                            <option>1ro Primaria</option>
                            <option>2do Primaria</option>
                            <option>3ro Primaria</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                <label class="block text-gray-700 font-bold mb-2">Bus Asignado</label>
                <select name="bus_id" class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="">-- Seleccionar Transporte --</option>
                <?php foreach ($buses_activos as $bus): ?>
                <option value="<?php echo $bus['id']; ?>"><?php echo $bus['nombre_bus']; ?></option>
             <?php endforeach; ?>
            </select>
    </div>
    <div>
        <label class="block text-gray-700 font-bold mb-2">Contacto Emergencia</label>
        <input type="text" name="telefono" class="w-full border border-gray-300 p-2 rounded" placeholder="Ej: 5555-1234">
    </div>
</div>

                <button type="submit" class="w-full bg-marca hover:bg-marca-oscuro text-white font-bold py-3 px-4 rounded flex justify-center items-center transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Guardar y Generar QR
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center text-center">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Código QR Generado</h3>
            
            <div class="w-48 h-48 bg-gray-100 rounded flex items-center justify-center mb-4 border-2 border-dashed border-gray-300 overflow-hidden">
                <?php if ($qr_mostrado != ""): ?>
                    <img src="<?php echo $qr_mostrado; ?>" class="w-full h-full object-contain">
                <?php else: ?>
                    <span class="text-gray-400 text-sm">Esperando datos...</span>
                <?php endif; ?>
            </div>

            <?php if ($qr_mostrado != ""): ?>
                <a href="<?php echo $qr_mostrado; ?>" download="QR_Alumno.png" class="bg-estado bg-estado-exito text-white text-sm font-bold py-2 px-4 rounded">
                    Descargar Imagen
                </a>
            <?php endif; ?>

        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
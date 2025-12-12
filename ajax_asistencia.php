<?php
//LIMPIEZA
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

require_once 'includes/config.php';
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

ob_clean();
header('Content-Type: application/json');

//RECIBIR DATOS
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$response = [];

if (isset($data['codigo'])) {
    
    $codigo_qr = limpiar($data['codigo']);
    $bus_id    = isset($data['bus_id']) ? $data['bus_id'] : 1; 
    
    // RECIBIMOS EL TIPO MANUALMENTE (Si no llega, por defecto es ENTRADA)
    $tipo_manual = isset($data['tipo']) ? $data['tipo'] : 'ENTRADA';

    // A. BUSCAR ALUMNO
    $stmt = $pdo->prepare("SELECT id, nombre_completo FROM alumnos WHERE codigo_qr = ? LIMIT 1");
    $stmt->execute([$codigo_qr]);
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alumno) {
        
        // --- EVITAR DOBLE SCAN DEL MISMO TIPO EN MENOS DE 1 MINUTO ---
        // Esto evita que si deja la cámara puesta, marque 5 veces "ENTRADA" seguidas.
        $stmtCheck = $pdo->prepare("SELECT fecha_hora FROM asistencias WHERE alumno_id = ? AND tipo = ? ORDER BY id DESC LIMIT 1");
        $stmtCheck->execute([$alumno['id'], $tipo_manual]);
        $ultimo = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        $permitir_registro = true;
        if ($ultimo) {
            $segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($ultimo['fecha_hora']);
            if ($segundos < 60) { // Si hace menos de 60 segundos marcó lo mismo...
                $permitir_registro = false;
            }
        }
        // -------------------------------------------------------------

        if ($permitir_registro) {
            $sql = "INSERT INTO asistencias (alumno_id, bus_id, fecha_hora, tipo) VALUES (?, ?, NOW(), ?)";
            $stmtInsert = $pdo->prepare($sql);
            
            if ($stmtInsert->execute([$alumno['id'], $bus_id, $tipo_manual])) {
                $response = [
                    'status' => 'success', 
                    'mensaje' => 'Registrado correctamente', 
                    'alumno' => $alumno['nombre_completo'],
                    'hora' => date('h:i A'),
                    'tipo' => $tipo_manual
                ];
            } else {
                $response = ['status' => 'error', 'mensaje' => 'Error SQL'];
            }
        } else {
            // Respondemos éxito pero avisamos que ya estaba (para no asustar al chofer)
            $response = [
                'status' => 'success', 
                'mensaje' => 'Ya registrado hace un momento', 
                'alumno' => $alumno['nombre_completo'],
                'hora' => date('h:i A'),
                'tipo' => $tipo_manual
            ];
        }

    } else {
        $response = ['status' => 'error', 'mensaje' => 'Alumno no encontrado'];
    }

} else {
    $response = ['status' => 'error', 'mensaje' => 'Faltan datos'];
}

echo json_encode($response);
exit;
?>
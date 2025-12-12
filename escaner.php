<?php
require_once 'includes/seguridad.php';
if ($_SESSION['rol'] != 2) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escaner - Chofer</title>
    <link href="css/estilos.css" rel="stylesheet">
    <script src="js/html5-qrcode.min.js"></script> 
    
    <style>
        .flash-success { animation: flashGreen 0.5s; }
        @keyframes flashGreen { 0% { background-color: #10B981; } 100% { background-color: transparent; } }
        
        /* Estilos para los botones de radio personalizados */
        input:checked + div { border-color: white; box-shadow: 0 0 10px white; transform: scale(1.05); }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans h-screen flex flex-col">

    <header class="bg-gray-800 p-4 flex justify-between items-center shadow-lg">
        <div>
            <h1 class="font-bold text-lg">Escaner Bus</h1>
            <p class="text-xs text-gray-400"><?php echo $_SESSION['nombre']; ?></p>
        </div>
        <a href="logout.php" class="bg-red-600 px-3 py-1 rounded text-sm font-bold hover:bg-red-700">Salir</a>
    </header>

    <div class="flex justify-center gap-4 p-4 bg-gray-800 border-t border-gray-700">
        
        <label class="cursor-pointer w-1/2">
            <input type="radio" name="modo_scan" value="ENTRADA" class="hidden" checked>
            <div class="bg-green-600 text-center py-3 rounded-lg border-2 border-transparent transition-all font-bold opacity-80 hover:opacity-100">
                SUBIENDO<br><span class="text-xs">ENTRADA</span>
            </div>
        </label>

        <label class="cursor-pointer w-1/2">
            <input type="radio" name="modo_scan" value="SALIDA" class="hidden">
            <div class="bg-estado-alerta text-center py-3 rounded-lg border-2 border-transparent transition-all font-bold opacity-80 hover:opacity-100">
                BAJANDO<br><span class="text-xs">SALIDA</span>
            </div>
        </label>

    </div>

    <main class="flex-1 flex flex-col items-center justify-start p-4 bg-black relative">
        
        <div id="reader" class="w-full max-w-sm border-2 border-gray-700 rounded-lg overflow-hidden"></div>
        <p id="statusTxt" class="mt-2 text-gray-400 text-sm animate-pulse">Cámara lista...</p>
    
    </main>

    <div class="bg-gray-800 h-1/3 rounded-t-3xl p-6 shadow-inner overflow-y-auto z-10">
        <h3 class="text-sm font-bold text-gray-400 mb-3 uppercase tracking-wider">Últimos Registros</h3>
        <ul id="listaAsistencia" class="space-y-3"></ul>
    </div>

    <audio id="beepOk" src="https://www.soundjay.com/buttons/sounds/button-3.mp3"></audio>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        let isScanning = true; 

        const onScanSuccess = (decodedText, decodedResult) => {
            if (!isScanning) return; 

            //LEER QUÉ MODO ESTÁ SELECCIONADO (Entrada o Salida)
            const modoSeleccionado = document.querySelector('input[name="modo_scan"]:checked').value;

            isScanning = false; 
            document.getElementById('statusTxt').innerText = "Procesando " + modoSeleccionado + "...";

            //ENVIAR AL SERVIDOR CON EL TIPO SELECCIONADO
            fetch('ajax_asistencia.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    codigo: decodedText, 
                    bus_id: 1,
                    tipo: modoSeleccionado // <--- Aquí enviamos la elección del chofer
                })
            })
            .then(response => response.text().then(text => {
                try { return JSON.parse(text); } catch (e) { throw new Error(text); }
            }))
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('beepOk').play();
                    
                    // Pasamos el tipo que nos devolvió el servidor (que será el mismo que enviamos)
                    agregarALista(data.alumno, data.hora, data.tipo);
                    
                    document.body.classList.add('flash-success');
                    setTimeout(() => document.body.classList.remove('flash-success'), 500);
                } else {
                    alert("⚠️ " + data.mensaje); 
                }
            })
            .catch(error => {
                console.error(error);
                // alert(" ERROR: " + error.message); // Opcional: comentar si molesta mucho
            })
            .finally(() => {
                setTimeout(() => { 
                    isScanning = true; 
                    document.getElementById('statusTxt').innerText = "Escaneando...";
                }, 2000); // 2 segundos de pausa entre escaneos
            });
        };

        function agregarALista(nombre, hora, tipo) {
            const lista = document.getElementById('listaAsistencia');
            let colorBorde = (tipo === 'ENTRADA') ? 'border-estado-exito' : 'border-estado-alerta';
            let colorTexto = (tipo === 'ENTRADA') ? 'text-estado-exito' : 'text-estado-alerta';
            
            const item = `
            <li class="flex items-center justify-between bg-gray-700 p-3 rounded-lg border-l-4 ${colorBorde} animate-bounce">
                <div>
                    <p class="font-bold">${nombre}</p>
                    <p class="text-xs text-gray-400">${hora}</p>
                </div>
                <span class="${colorTexto} font-bold text-sm">${tipo}</span>
            </li>`;
            lista.insertAdjacentHTML('afterbegin', item);
        }

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => { document.getElementById('statusTxt').innerText = "Error cámara: " + err; });

    </script>
</body>
</html>
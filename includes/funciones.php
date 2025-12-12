<?php
/*
    CAJA DE HERRAMIENTAS
    --------------------
    Funciones pequeñas que usaremos en todo el sistema.
*/

// Función para limpiar datos de entrada (Evita Hackeos básicos XSS)
function limpiar($datos) {
    $datos = trim($datos);                 // Quita espacios al inicio y final
    $datos = stripslashes($datos);         // Quita barras invertidas
    $datos = htmlspecialchars($datos);     // Convierte caracteres especiales en HTML
    return $datos;
}

// Función para mostrar fechas bonitas (Ej: 10/12/2025)
function fechaBonita($fecha) {
    return date("d/m/Y h:i A", strtotime($fecha));
}
?>
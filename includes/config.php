<?php
/*
    CONFIGURACIÓN GENERAL DEL SISTEMA
    ---------------------------------
    Aquí van las variables que no cambian y la configuración regional.
*/

//ZONA HORARIA
// Cambia esto por tu zona (ej: 'America/Mexico_City', 'America/Bogota', 'America/El_Salvador')
date_default_timezone_set('America/El_Salvador');

//DATOS DE LA EMPRESA
define('NOMBRE_SISTEMA', 'Transporte App');
define('MONEDA', '$');

//RUTAS (Opcional, ayuda si subes el proyecto a una subcarpeta)
define('URL_BASE', 'http://localhost/transporte_app/');
?>
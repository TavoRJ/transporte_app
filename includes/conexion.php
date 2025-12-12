<?php
/*
    ARCHIVO DE CONEXIÓN A LA BASE DE DATOS
    --------------------------------------s
*/

//Credenciales de Laragon (Por defecto son estas)
$host     = 'localhost';
$db_name  = 'transporte_app'; // El nombre exacto que pusimos en HeidiSQL
$user     = 'root';           // Usuario por defecto de Laragon
$password = '';               // Laragon trae la clave vacía por defecto

//Intentamos conectar (Usamos try-catch para manejar errores)
try {
    // Cadena de conexión (DSN)
    $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
    
    // Opciones para que nos avise si hay errores
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar error si algo falla
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devolver datos como array asociativo
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Crear la conexión
    $pdo = new PDO($dsn, $user, $password, $options);

    // Si el código llega aquí, es que todo salió bien.
    // (No imprimimos nada para no ensuciar las páginas web, pero la variable $pdo ya está lista para usarse).

} catch (PDOException $e) {
    //Si algo falla, el código salta aquí
    die("Error de conexión: " . $e->getMessage());
}
?>
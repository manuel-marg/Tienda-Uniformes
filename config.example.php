<?php
/**
 * Plantilla de Configuración
 * Instrucciones: Copiar este archivo como 'config.php' y configurar sus credenciales.
 */

// Credenciales del Servidor
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda_uniformes');
define('DB_USER', 'root');
define('DB_PASS', '');

// Cargar funciones globales
require_once 'functions.php';

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

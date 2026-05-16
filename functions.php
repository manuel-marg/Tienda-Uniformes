<?php
/**
 * Funciones de utilidad global
 * Este archivo se sincroniza con el repositorio Git.
 */

// Iniciar sesión en todas las páginas que carguen funciones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está logueado, de lo contrario redirige al login
 */
function checkAuth() {
    // Si no existe la sesión de usuario y no estamos en la página de login
    if (!isset($_SESSION['usuario_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header("Location: login.php");
        exit();
    }
}

/**
 * Sanitiza entradas de texto para evitar ataques XSS
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Formatea un número como moneda (USD)
 */
function formatMoney($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Genera el badge HTML para los estados del pedido
 */
function getStatusBadge($status) {
    $colors = [
        'Pendiente' => 'bg-gray-100 text-gray-800',
        'Listo' => 'bg-green-100 text-green-800',
        'Entregado' => 'bg-blue-100 text-blue-800'
    ];
    
    $color = $colors[$status] ?? 'bg-gray-100 text-gray-800';
    return "<span class='px-2.5 py-0.5 rounded-full text-xs font-medium {$color}'>{$status}</span>";
}
?>

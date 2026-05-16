<?php
require_once 'config.php';

// Asegurar autenticación
checkAuth();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Al eliminar el pedido, los detalles se eliminan automáticamente 
        // gracias a la restricción ON DELETE CASCADE en la base de datos.
        $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: index.php?deleted=1");
        exit();
    } catch (Exception $e) {
        die("Error al eliminar el pedido: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>

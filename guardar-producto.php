<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $accion = $_POST['accion'] ?? 'registrar';
        
        if ($accion === 'registrar') {
            $nombre = cleanInput($_POST['nombre']);
            $descripcion = cleanInput($_POST['descripcion']);
            $precio_base = (float)$_POST['precio_base'];

            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_base) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio_base]);
        } 
        elseif ($accion === 'desactivar') {
            $id = (int)$_POST['id'];
            $stmt = $pdo->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
            $stmt->execute([$id]);
        }
        elseif ($accion === 'activar') {
            $id = (int)$_POST['id'];
            $stmt = $pdo->prepare("UPDATE productos SET activo = 1 WHERE id = ?");
            $stmt->execute([$id]);
        }

        header("Location: productos.php?success=1");
        exit();

    } catch (Exception $e) {
        die("Error al procesar producto: " . $e->getMessage());
    }
} else {
    header("Location: productos.php");
    exit();
}
?>

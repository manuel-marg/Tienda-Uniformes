<?php
require_once 'config.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $accion = $_POST['accion'] ?? 'registrar';
        
        if ($accion === 'registrar') {
            $nombre = cleanInput($_POST['nombre']);
            $descripcion = cleanInput($_POST['descripcion']);
            $precio_base = (float)$_POST['precio_base'];

            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_base) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio_base]);
            header("Location: productos.php?success=registered");
            exit();
        } 
        elseif ($accion === 'modificar') {
            $id = (int)$_POST['id'];
            $nombre = cleanInput($_POST['nombre']);
            $descripcion = cleanInput($_POST['descripcion']);
            $precio_base = (float)$_POST['precio_base'];

            $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio_base = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $precio_base, $id]);
            header("Location: productos.php?success=modified");
            exit();
        }
        elseif ($accion === 'eliminar') {
            $id = (int)$_POST['id'];
            
            // Intentar eliminar físicamente
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: productos.php?success=deleted");
            exit();
        }

    } catch (PDOException $e) {
        // Si hay una restricción de llave foránea (error 23000), redirigir con error específico
        if ($e->getCode() == '23000') {
            header("Location: productos.php?error=foreign_key");
            exit();
        }
        die("Error de base de datos: " . $e->getMessage());
    } catch (Exception $e) {
        die("Error al procesar producto: " . $e->getMessage());
    }
} else {
    header("Location: productos.php");
    exit();
}
?>

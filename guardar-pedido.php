<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Sanitización
        $cliente_nombre = cleanInput($_POST['cliente_nombre']);
        $cliente_telefono = cleanInput($_POST['cliente_telefono']);
        $metodo_entrega = cleanInput($_POST['metodo_entrega']);
        $producto_id = (int)$_POST['producto_id'];
        $color = cleanInput($_POST['color']);
        $tela = cleanInput($_POST['tela']);
        $talla_superior = cleanInput($_POST['talla_superior']);
        $talla_inferior = cleanInput($_POST['talla_inferior']);
        $estampado_bordado = cleanInput($_POST['estampado_bordado']);
        $cantidad = (int)$_POST['cantidad'];

        // 2. Obtener precio base del producto seleccionado
        $stmt_p = $pdo->prepare("SELECT precio_base FROM productos WHERE id = ?");
        $stmt_p->execute([$producto_id]);
        $prod = $stmt_p->fetch();
        
        if (!$prod) throw new Exception("Producto no encontrado.");

        $subtotal = $prod['precio_base'] * $cantidad;
        $total = $subtotal; // En esta versión simplificada total = subtotal

        // 3. Transacción SQL
        $pdo->beginTransaction();

        $sql_ped = "INSERT INTO pedidos (cliente_nombre, cliente_telefono, metodo_entrega, total) VALUES (?, ?, ?, ?)";
        $stmt_ped = $pdo->prepare($sql_ped);
        $stmt_ped->execute([$cliente_nombre, $cliente_telefono, $metodo_entrega, $total]);
        $pedido_id = $pdo->lastInsertId();

        $sql_det = "INSERT INTO detalles_pedido (pedido_id, producto_id, color, talla_superior, talla_inferior, tela, estampado_bordado, cantidad, subtotal) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_det = $pdo->prepare($sql_det);
        $stmt_det->execute([$pedido_id, $producto_id, $color, $talla_superior, $talla_inferior, $tela, $estampado_bordado, $cantidad, $subtotal]);

        $pdo->commit();
        header("Location: index.php?success=1");
        exit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Error crítico: " . $e->getMessage());
    }
}
?>

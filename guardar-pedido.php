<?php
require_once 'config.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Datos del Cliente
        $cliente_nombre = cleanInput($_POST['cliente_nombre']);
        $cliente_telefono = cleanInput($_POST['cliente_telefono']);
        $metodo_entrega = cleanInput($_POST['metodo_entrega']);
        $monto_abonado = isset($_POST['monto_abonado']) && $_POST['monto_abonado'] !== '' ? (float)$_POST['monto_abonado'] : 0;
        $items = $_POST['items'] ?? [];

        if (empty($items)) {
            throw new Exception("El pedido debe contener al menos un uniforme.");
        }

        // 2. Iniciar Transacción
        $pdo->beginTransaction();

        // 3. Insertar Cabecera del Pedido (Total temporal en 0)
        $sql_ped = "INSERT INTO pedidos (cliente_nombre, cliente_telefono, metodo_entrega, total, monto_abonado) VALUES (?, ?, ?, ?, ?)";
        $stmt_ped = $pdo->prepare($sql_ped);
        $stmt_ped->execute([$cliente_nombre, $cliente_telefono, $metodo_entrega, 0, $monto_abonado]);
        $pedido_id = $pdo->lastInsertId();

        $total_pedido = 0;

        // 4. Procesar cada Uniforme (Item)
        foreach ($items as $item) {
            $producto_id = (int)$item['producto_id'];
            $color = cleanInput($item['color']);
            $tela = cleanInput($item['tela']);
            $talla_superior = cleanInput($item['talla_superior']);
            $talla_inferior = cleanInput($item['talla_inferior']);
            $estampado_bordado = cleanInput($item['estampado_bordado']);
            $combinacion_modelos = isset($item['combinacion_modelos']) ? cleanInput($item['combinacion_modelos']) : '';
            $cantidad = (int)$item['cantidad'];

            // Obtener precio del producto
            $stmt_p = $pdo->prepare("SELECT precio_base FROM productos WHERE id = ?");
            $stmt_p->execute([$producto_id]);
            $prod = $stmt_p->fetch();
            
            if (!$prod) continue;

            $subtotal = $prod['precio_base'] * $cantidad;
            $total_pedido += $subtotal;

            // Insertar detalle
            $sql_det = "INSERT INTO detalles_pedido (pedido_id, producto_id, color, talla_superior, talla_inferior, tela, estampado_bordado, combinacion_modelos, cantidad, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_det = $pdo->prepare($sql_det);
            $stmt_det->execute([
                $pedido_id, 
                $producto_id, 
                $color, 
                $talla_superior, 
                $talla_inferior, 
                $tela, 
                $estampado_bordado, 
                $combinacion_modelos, 
                $cantidad, 
                $subtotal
            ]);
        }

        // 5. Actualizar el total real del pedido
        $stmt_upd_total = $pdo->prepare("UPDATE pedidos SET total = ? WHERE id = ?");
        $stmt_upd_total->execute([$total_pedido, $pedido_id]);

        $pdo->commit();
        header("Location: index.php?success=1");
        exit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Error al procesar el pedido múltiple: " . $e->getMessage());
    }
}
?>

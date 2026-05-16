<?php 
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

// Actualizar estado si se solicita
if (isset($_POST['nuevo_estado'])) {
    $stmt_upd = $pdo->prepare("UPDATE pedidos SET estado_pedido = ? WHERE id = ?");
    $stmt_upd->execute([cleanInput($_POST['nuevo_estado']), $id]);
    header("Location: ver-pedido.php?id=$id");
    exit;
}

// Consulta unificada con nombre de producto
$sql = "SELECT p.*, dp.*, pr.nombre as producto_nombre 
        FROM pedidos p 
        JOIN detalles_pedido dp ON p.id = dp.pedido_id 
        JOIN productos pr ON dp.producto_id = pr.id 
        WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) { header("Location: index.php"); exit; }

include 'header.php'; 
?>

<div class="mb-6 flex items-center gap-4">
    <a href="index.php" class="bg-white p-3 rounded-2xl border border-slate-100 text-slate-400 active:scale-90 transition-transform shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    <div>
        <h1 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Orden #<?= $id ?></h1>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= $pedido['estado_pedido'] ?></p>
    </div>
</div>

<div class="space-y-6">
    <!-- Estado de Producción -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Actualizar Avance</h2>
        <form method="POST" class="grid grid-cols-2 gap-2">
            <?php foreach (['Pendiente', 'Listo', 'Entregado'] as $e): ?>
                <button type="submit" name="nuevo_estado" value="<?= $e ?>" 
                    class="h-12 rounded-xl border text-xs font-black uppercase transition-all active:scale-95 <?= $pedido['estado_pedido'] == $e ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-50 border-slate-100 text-slate-400' ?>">
                    <?= $e ?>
                </button>
            <?php endforeach; ?>
        </form>
    </div>

    <!-- Ficha de Taller (Modo Oscuro para contraste) -->
    <div class="bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>

        <div class="border-b border-slate-800 pb-6 mb-6">
            <p class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Modelo Seleccionado</p>
            <h3 class="text-2xl font-black uppercase italic"><?= htmlspecialchars($pedido['producto_nombre']) ?></h3>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700">
                <p class="text-slate-500 text-[10px] font-black uppercase mb-2">Talla Sup.</p>
                <span class="text-3xl font-black text-blue-400"><?= $pedido['talla_superior'] ?></span>
            </div>
            <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700">
                <p class="text-slate-500 text-[10px] font-black uppercase mb-2">Talla Inf.</p>
                <span class="text-3xl font-black text-blue-400"><?= $pedido['talla_inferior'] ?></span>
            </div>
        </div>

        <div class="space-y-4 mb-8">
            <div class="flex justify-between items-center">
                <span class="text-slate-500 text-xs font-bold uppercase">Color:</span>
                <span class="font-black uppercase tracking-wider text-sm"><?= htmlspecialchars($pedido['color'] ?: 'N/A') ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 text-xs font-bold uppercase">Tela:</span>
                <span class="font-black uppercase tracking-wider text-sm"><?= htmlspecialchars($pedido['tela'] ?: 'N/A') ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 text-xs font-bold uppercase">Cantidad:</span>
                <span class="bg-white text-slate-900 px-3 py-1 rounded-lg font-black italic">x<?= $pedido['cantidad'] ?></span>
            </div>
        </div>

        <div class="bg-yellow-400/10 border-l-4 border-yellow-400 p-5 rounded-r-2xl">
            <p class="text-yellow-400 text-[10px] font-black uppercase tracking-widest mb-2">Instrucciones de Bordado</p>
            <p class="text-sm font-medium leading-relaxed italic text-slate-300">
                "<?= nl2br(htmlspecialchars($pedido['estampado_bordado'] ?: 'Sin notas de personalización.')) ?>"
            </p>
        </div>
    </div>

    <!-- Contacto -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-slate-100 p-3 rounded-2xl text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Cliente</p>
                <p class="font-black text-slate-800 uppercase"><?= htmlspecialchars($pedido['cliente_nombre']) ?></p>
            </div>
        </div>
        <a href="tel:<?= $pedido['cliente_telefono'] ?>" class="bg-blue-50 text-blue-600 p-3 rounded-2xl active:scale-90 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>

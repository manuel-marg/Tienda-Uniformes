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

// 1. Obtener Cabecera del Pedido
$stmt_cab = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt_cab->execute([$id]);
$pedido = $stmt_cab->fetch();

if (!$pedido) { header("Location: index.php"); exit; }

// 2. Obtener Todos los Uniformes de este Pedido
$stmt_det = $pdo->prepare("
    SELECT dp.*, pr.nombre as producto_nombre 
    FROM detalles_pedido dp 
    JOIN productos pr ON dp.producto_id = pr.id 
    WHERE dp.pedido_id = ?
");
$stmt_det->execute([$id]);
$items = $stmt_det->fetchAll();

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
        <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Actualizar Avance General</h2>
        <form method="POST" class="grid grid-cols-3 gap-2">
            <?php foreach (['Pendiente', 'Listo', 'Entregado'] as $e): ?>
                <button type="submit" name="nuevo_estado" value="<?= $e ?>" 
                    class="h-12 rounded-xl border text-[10px] font-black uppercase transition-all active:scale-95 <?= $pedido['estado_pedido'] == $e ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-50 border-slate-100 text-slate-400' ?>">
                    <?= $e ?>
                </button>
            <?php endforeach; ?>
        </form>
    </div>

    <!-- Lista de Uniformes -->
    <div class="space-y-4">
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest px-2">Detalles de Confección (<?= count($items) ?> uniformes)</h2>
        
        <?php foreach ($items as $idx => $item): ?>
            <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
                <div class="absolute -top-4 -right-4 opacity-5">
                    <span class="text-8xl font-black italic"><?= $idx + 1 ?></span>
                </div>

                <div class="border-b border-slate-800 pb-4 mb-4">
                    <p class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Uniforme #<?= $idx + 1 ?></p>
                    <h3 class="text-xl font-black uppercase italic"><?= htmlspecialchars($item['producto_nombre']) ?></h3>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-800/50 p-3 rounded-2xl border border-slate-700 text-center">
                        <p class="text-slate-500 text-[9px] font-black uppercase mb-1">Talla Sup.</p>
                        <span class="text-2xl font-black text-blue-400"><?= $item['talla_superior'] ?></span>
                    </div>
                    <div class="bg-slate-800/50 p-3 rounded-2xl border border-slate-700 text-center">
                        <p class="text-slate-500 text-[9px] font-black uppercase mb-1">Talla Inf.</p>
                        <span class="text-2xl font-black text-blue-400"><?= $item['talla_inferior'] ?></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-slate-500 text-[9px] font-black uppercase mb-1">Color / Tela</p>
                        <p class="text-sm font-bold uppercase"><?= htmlspecialchars($item['color']) ?> / <?= htmlspecialchars($item['tela']) ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-500 text-[9px] font-black uppercase mb-1">Cantidad</p>
                        <p class="text-sm font-black italic text-yellow-400">x<?= $item['cantidad'] ?></p>
                    </div>
                </div>

                <div class="bg-slate-800/80 p-4 rounded-2xl border-l-4 border-blue-500">
                    <p class="text-blue-400 text-[9px] font-black uppercase tracking-widest mb-1 text-center">Notas de Bordado</p>
                    <p class="text-xs font-medium leading-relaxed italic text-slate-300 text-center">
                        "<?= nl2br(htmlspecialchars($item['estampado_bordado'] ?: 'Sin notas.')) ?>"
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Contacto del Cliente -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4">
        <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Información de Entrega</h2>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cliente</p>
                    <p class="font-black text-slate-800 text-sm"><?= htmlspecialchars($pedido['cliente_nombre']) ?></p>
                </div>
            </div>
            <a href="https://api.whatsapp.com/send?phone=<?= preg_replace('/[^0-9]/', '', $pedido['cliente_telefono']) ?>" class="bg-green-50 text-green-600 p-2.5 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.522.902 3.222 1.387 4.953 1.388 5.485 0 9.95-4.466 9.952-9.953.001-2.659-1.035-5.158-2.917-7.04s-4.381-2.919-7.04-2.92c-5.488 0-9.954 4.465-9.956 9.953-.002 1.751.459 3.457 1.336 4.953l-1.01 3.693 3.782-.992zm11.496-7.613c-.301-.15-1.779-.879-2.053-.979-.275-.1-.475-.15-.675.15-.2.3-.775 1.05-1.025 1.3-.25.25-.5.275-.8.125-.3-.15-1.265-.467-2.414-1.491-.893-.797-1.495-1.782-1.671-2.081-.176-.3-.019-.462.13-.611.134-.134.3-.35.45-.525.151-.175.201-.3.301-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.588-.493-.509-.675-.518-.175-.009-.375-.01-.575-.01-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.075 2.9 1.225 3.1c.15.2 2.115 3.231 5.123 4.527.715.308 1.273.492 1.707.63.718.228 1.372.196 1.889.12.576-.085 1.779-.726 2.029-1.426.25-.7.25-1.3.175-1.425-.075-.125-.275-.2-.575-.35z"/></svg>
            </a>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-purple-50 p-2 rounded-xl text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Metodo</p>
                <p class="font-black text-slate-800 text-sm"><?= $pedido['metodo_entrega'] ?></p>
            </div>
        </div>
    </div>

    <!-- Resumen de Pago -->
    <div class="bg-blue-600 text-white p-7 rounded-[2.5rem] shadow-xl shadow-blue-100 flex justify-between items-center">
        <div>
            <p class="text-blue-200 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total del Pedido</p>
            <p class="text-3xl font-black italic tracking-tighter"><?= formatMoney($pedido['total']) ?></p>
        </div>
        <div class="text-right">
            <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black uppercase"><?= $pedido['estado_pago'] ?></span>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

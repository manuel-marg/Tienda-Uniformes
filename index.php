<?php 
require_once 'config.php';
include 'header.php'; 

$filtro = $_GET['estado'] ?? 'Todos';
$sql = "SELECT * FROM pedidos";
if ($filtro !== 'Todos') { $sql .= " WHERE estado_pedido = :estado"; }
$sql .= " ORDER BY fecha_registro DESC";

$stmt = $pdo->prepare($sql);
if ($filtro !== 'Todos') { $stmt->execute(['estado' => $filtro]); }
else { $stmt->execute(); }
$pedidos = $stmt->fetchAll();
?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">PEDIDOS</h1>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest"><?= date('l, d M') ?></p>
    </div>
    <div class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg text-xs font-black">
        <?= count($pedidos) ?> TOTAL
    </div>
</div>

<!-- Filtros en Scroll Horizontal -->
<div class="flex overflow-x-auto pb-6 gap-2 no-scrollbar -mx-4 px-4">
    <?php 
    $estados = ['Todos', 'Pendiente', 'Listo', 'Entregado'];
    foreach ($estados as $e): 
        $active = ($filtro === $e) ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white text-slate-500 border border-slate-100';
    ?>
        <a href="index.php?estado=<?= $e ?>" class="px-6 py-3 rounded-2xl text-sm font-bold whitespace-nowrap transition-all active:scale-90 <?= $active ?>">
            <?= $e ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Grid de Tarjetas -->
<div class="space-y-4">
    <?php if (empty($pedidos)): ?>
        <div class="bg-white p-12 rounded-3xl border border-dashed border-slate-200 text-center">
            <p class="text-slate-400 font-bold">No hay pedidos en esta sección</p>
        </div>
    <?php endif; ?>

    <?php foreach ($pedidos as $p): 
        $ws_num = preg_replace('/[^0-9]/', '', $p['cliente_telefono']);
        $msg = "Hola {$p['cliente_nombre']}, tu pedido de uniformes está en estado: *{$p['estado_pedido']}*.";
        $ws_link = "https://api.whatsapp.com/send?phone={$ws_num}&text=" . urlencode($msg);
    ?>
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-50 flex flex-col gap-4 relative overflow-hidden group">
            <!-- Indicador lateral de estado -->
            <div class="absolute left-0 top-0 bottom-0 w-1.5 <?= 
                $p['estado_pedido'] == 'Pendiente' ? 'bg-slate-300' : (
                $p['estado_pedido'] == 'En Confección' ? 'bg-yellow-400' : (
                $p['estado_pedido'] == 'Listo' ? 'bg-green-500' : 'bg-blue-500')) 
            ?>"></div>

            <div class="flex justify-between items-start pl-2">
                <div>
                    <h3 class="font-black text-slate-800 text-lg leading-tight uppercase"><?= htmlspecialchars($p['cliente_nombre']) ?></h3>
                    <p class="text-xs font-bold text-slate-400 mt-1"><?= date('d M, Y - h:i A', strtotime($p['fecha_registro'])) ?></p>
                </div>
                <?= getStatusBadge($p['estado_pedido']) ?>
            </div>

            <div class="flex items-center justify-between border-t border-slate-50 pt-4 pl-2">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pedido</span>
                    <span class="text-2xl font-black text-blue-600 tracking-tighter"><?= formatMoney($p['total']) ?></span>
                </div>
                
                <div class="flex gap-2">
                    <a href="<?= $ws_link ?>" target="_blank" class="bg-green-500 text-white p-3.5 rounded-2xl shadow-lg shadow-green-100 active:scale-90 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.012 2c-5.508 0-9.987 4.479-9.987 9.988 0 1.757.455 3.409 1.25 4.846l-1.328 4.852 4.966-1.303c1.405.765 3.007 1.201 4.71 1.201 5.508 0 9.988-4.479 9.988-9.988s-4.48-9.988-9.988-9.988zm5.952 14.281c-.244.686-1.42 1.311-1.956 1.384-.471.064-.91.082-1.464-.101-.321-.106-.723-.244-1.21-.444-2.07-.852-3.411-2.964-3.514-3.102-.104-.138-.847-1.127-.847-2.166 0-1.039.544-1.549.739-1.761.195-.212.423-.265.565-.265.141 0 .282.001.405.007.13.006.304-.049.476.362.177.422.607 1.481.659 1.589.053.108.088.235.016.381-.072.146-.108.235-.216.362-.108.127-.228.283-.325.381-.108.109-.221.228-.095.444.127.216.564.931 1.211 1.509.833.743 1.536.973 1.754 1.082.217.109.345.091.472-.055.127-.145.544-.633.689-.851.146-.217.292-.182.493-.109.201.073 1.275.602 1.497.712.222.11.369.164.423.254.054.091.054.526-.19 1.213z"/></svg>
                    </a>
                    <a href="ver-pedido.php?id=<?= $p['id'] ?>" class="bg-slate-900 text-white px-6 py-3.5 rounded-2xl font-bold text-sm active:scale-95 transition-transform flex items-center justify-center">
                        DETALLES
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>

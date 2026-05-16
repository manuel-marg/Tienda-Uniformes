<?php 
require_once 'config.php';
include 'header.php'; 

// Obtener todos los productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY activo DESC, nombre ASC");
$productos = $stmt->fetchAll();
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Catálogo de Productos</h1>
    <p class="text-slate-500 text-sm">Gestiona los modelos disponibles.</p>
</div>

<!-- Formulario de Registro Rápido (Acordeón Simple) -->
<details class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden group">
    <summary class="flex items-center justify-between p-5 cursor-pointer list-none">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <span class="font-bold text-slate-700">Añadir Nuevo Modelo</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </summary>
    <div class="px-5 pb-5 pt-2 border-t border-slate-50">
        <form action="guardar-producto.php" method="POST" class="space-y-4">
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-600">Nombre del Modelo</label>
                <input type="text" name="nombre" required placeholder="Ej: Mono Quirúrgico Clásico" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-600">Descripción</label>
                <textarea name="descripcion" rows="2" placeholder="Detalles de la tela o corte..." 
                    class="w-full p-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-600">Precio Base</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">$</span>
                    <input type="number" step="0.01" name="precio_base" required placeholder="0.00" 
                        class="w-full h-12 pl-8 pr-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>
            </div>
            <button type="submit" class="w-full h-14 bg-blue-600 text-white rounded-xl font-bold text-lg shadow-lg active:scale-95 transition-transform">
                Registrar Producto
            </button>
        </form>
    </div>
</details>

<!-- Listado en Tarjetas -->
<div class="space-y-4">
    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest px-1">Productos Registrados</h2>
    
    <?php foreach ($productos as $prod): ?>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 relative <?= !$prod['activo'] ? 'opacity-60 bg-slate-50' : '' ?>">
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-bold text-slate-800 text-lg"><?= htmlspecialchars($prod['nombre']) ?></h3>
                <span class="text-blue-600 font-black text-xl"><?= formatMoney($prod['precio_base']) ?></span>
            </div>
            <p class="text-slate-500 text-sm mb-4"><?= htmlspecialchars($prod['descripcion'] ?: 'Sin descripción') ?></p>
            
            <div class="flex gap-2">
                <?php if ($prod['activo']): ?>
                    <form action="guardar-producto.php" method="POST" class="w-full">
                        <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                        <input type="hidden" name="accion" value="desactivar">
                        <button type="submit" class="w-full h-10 border border-red-200 text-red-500 rounded-lg text-sm font-bold active:bg-red-50">
                            Desactivar Modelo
                        </button>
                    </form>
                <?php else: ?>
                    <form action="guardar-producto.php" method="POST" class="w-full">
                        <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                        <input type="hidden" name="accion" value="activar">
                        <button type="submit" class="w-full h-10 border border-green-200 text-green-500 rounded-lg text-sm font-bold active:bg-green-50">
                            Reactivar Modelo
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>

<?php 
require_once 'config.php';
include 'header.php'; 

// Obtener productos activos para el selector dinámico
$stmt = $pdo->query("SELECT id, nombre, precio_base FROM productos WHERE activo = 1 ORDER BY nombre ASC");
$productos = $stmt->fetchAll();
?>

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-800">Nuevo Pedido</h1>
    <p class="text-slate-500 text-sm">Registre los datos del cliente y agregue los uniformes.</p>
</div>

<form action="guardar-pedido.php" method="POST" id="pedidoForm" class="space-y-6">
    <!-- Bloque 1: Cliente -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold text-sm">1</div>
            <h2 class="text-lg font-bold text-slate-700 uppercase tracking-tight">Datos del Cliente</h2>
        </div>
        
        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nombre Completo</label>
            <input type="text" name="cliente_nombre" required placeholder="Ej: Dra. María García" 
                class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">WhatsApp</label>
                <input type="tel" name="cliente_telefono" required placeholder="10 dígitos" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Entrega</label>
                <select name="metodo_entrega" class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Retiro en Tienda">Retiro en Tienda</option>
                    <option value="Envío">Envío a Domicilio</option>
                </select>
            </div>
        </div>
        <!-- NUEVO CAMPO: Monto Cancelado -->
        <div class="space-y-1 pt-1">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Monto Cancelado (Abono inicial)</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                <input type="number" step="0.01" min="0" name="monto_abonado" placeholder="0.00" 
                    class="w-full h-12 pl-8 pr-4 rounded-xl border border-slate-200 text-base font-bold text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Déjalo en 0 o vacío si no han cancelado nada aún.</p>
        </div>
    </div>

    <!-- Bloque 2: Contenedor de Uniformes -->
    <div class="space-y-4" id="itemsContainer">
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold text-sm">2</div>
                <h2 class="text-lg font-bold text-slate-700 uppercase tracking-tight">Detalles de Confección</h2>
            </div>
        </div>

        <!-- El primer uniforme se carga por defecto -->
        <div class="item-block bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-5 relative overflow-hidden">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Uniforme #1</span>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Modelo de Uniforme</label>
                <select name="items[0][producto_id]" required class="w-full h-14 px-4 rounded-xl border border-slate-200 text-base bg-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-600">
                    <option value="">-- Seleccione Modelo --</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?> (<?= formatMoney($p['precio_base']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Color</label>
                    <input type="text" name="items[0][color]" placeholder="Ej: Azul Rey" 
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tela</label>
                    <input type="text" name="items[0][tela]" placeholder="Ej: Antifluido" 
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <!-- Tallas Pills -->
            <?php foreach (['superior' => 'Talla Superior', 'inferior' => 'Talla Inferior'] as $key => $label): ?>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest"><?= $label ?></label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (['XS', 'S', 'M', 'L', 'XL'] as $t): ?>
                            <div class="flex-1 min-w-[50px]">
                                <input type="radio" name="items[0][talla_<?= $key ?>]" id="items_0_<?= $key ?>_<?= $t ?>" value="<?= $t ?>" class="hidden pill-input" <?= $t == 'M' ? 'checked' : '' ?>>
                                <label for="items_0_<?= $key ?>_<?= $t ?>" class="flex items-center justify-center h-12 rounded-xl border border-slate-200 text-sm font-black cursor-pointer transition-all active:scale-90">
                                    <?= $t ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bordado / Notas</label>
                <textarea name="items[0][estampado_bordado]" rows="2" placeholder="Ej: Logo IMSS, Nombre 'Dra. García'..." 
                    class="w-full p-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Cantidad</label>
                <input type="number" name="items[0][cantidad]" value="1" min="1" 
                    class="w-full h-14 px-4 rounded-xl border border-slate-200 text-xl font-bold text-center focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>
    </div>

    <!-- Botón para añadir más -->
    <button type="button" id="addItemBtn" class="w-full h-14 border-2 border-dashed border-blue-200 text-blue-600 rounded-3xl font-bold flex items-center justify-center gap-2 active:bg-blue-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Añadir otro uniforme a este pedido
    </button>

    <button type="submit" class="w-full h-16 bg-blue-600 text-white rounded-3xl font-black text-xl shadow-xl shadow-blue-100 active:scale-95 transition-all mb-10">
        GUARDAR PEDIDO COMPLETO
    </button>
</form>

<!-- Template para JS (Oculto) -->
<template id="itemTemplate">
    <div class="item-block bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-5 relative overflow-hidden animate-in fade-in slide-in-from-top-4 duration-300">
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Uniforme #{{INDEX_PLUS_1}}</span>
            <button type="button" class="remove-item text-red-400 hover:text-red-600 p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Modelo de Uniforme</label>
            <select name="items[{{INDEX}}][producto_id]" required class="w-full h-14 px-4 rounded-xl border border-slate-200 text-base bg-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-600">
                <option value="">-- Seleccione Modelo --</option>
                <?php foreach ($productos as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?> (<?= formatMoney($p['precio_base']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Color</label>
                <input type="text" name="items[{{INDEX}}][color]" placeholder="Ej: Verde" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tela</label>
                <input type="text" name="items[{{INDEX}}][tela]" placeholder="Ej: Lino" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <!-- Tallas Superior -->
        <div class="space-y-2">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Talla Superior</label>
            <div class="flex flex-wrap gap-2">
                <?php foreach (['XS', 'S', 'M', 'L', 'XL'] as $t): ?>
                    <div class="flex-1 min-w-[50px]">
                        <input type="radio" name="items[{{INDEX}}][talla_superior]" id="items_{{INDEX}}_sup_<?= $t ?>" value="<?= $t ?>" class="hidden pill-input" <?= $t == 'M' ? 'checked' : '' ?>>
                        <label for="items_{{INDEX}}_sup_<?= $t ?>" class="flex items-center justify-center h-12 rounded-xl border border-slate-200 text-sm font-black cursor-pointer transition-all active:scale-90">
                            <?= $t ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tallas Inferior -->
        <div class="space-y-2">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Talla Inferior</label>
            <div class="flex flex-wrap gap-2">
                <?php foreach (['XS', 'S', 'M', 'L', 'XL'] as $t): ?>
                    <div class="flex-1 min-w-[50px]">
                        <input type="radio" name="items[{{INDEX}}][talla_inferior]" id="items_{{INDEX}}_inf_<?= $t ?>" value="<?= $t ?>" class="hidden pill-input" <?= $t == 'M' ? 'checked' : '' ?>>
                        <label for="items_{{INDEX}}_inf_<?= $t ?>" class="flex items-center justify-center h-12 rounded-xl border border-slate-200 text-sm font-black cursor-pointer transition-all active:scale-90">
                            <?= $t ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bordado / Notas</label>
            <textarea name="items[{{INDEX}}][estampado_bordado]" rows="2" placeholder="Notas específicas..." 
                class="w-full p-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Cantidad</label>
            <input type="number" name="items[{{INDEX}}][cantidad]" value="1" min="1" 
                class="w-full h-14 px-4 rounded-xl border border-slate-200 text-xl font-bold text-center focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
    </div>
</template>

<script>
let itemIndex = 1;

document.getElementById('addItemBtn').addEventListener('click', () => {
    const container = document.getElementById('itemsContainer');
    const template = document.getElementById('itemTemplate').innerHTML;
    
    const rendered = template
        .replace(/{{INDEX}}/g, itemIndex)
        .replace(/{{INDEX_PLUS_1}}/g, itemIndex + 1);
    
    const div = document.createElement('div');
    div.innerHTML = rendered;
    const block = div.firstElementChild;
    
    // Listener para eliminar
    block.querySelector('.remove-item').addEventListener('click', () => {
        block.classList.add('animate-out', 'fade-out', 'slide-out-to-top-4');
        setTimeout(() => block.remove(), 250);
    });
    
    container.appendChild(block);
    itemIndex++;
    
    // Scroll suave al nuevo item
    block.scrollIntoView({ behavior: 'smooth', block: 'center' });
});
</script>

<?php include 'footer.php'; ?>

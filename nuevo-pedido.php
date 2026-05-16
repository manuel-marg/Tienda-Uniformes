<?php 
require_once 'config.php';
include 'header.php'; 

// Obtener productos activos para el selector dinámico
$stmt = $pdo->query("SELECT id, nombre, precio_base FROM productos WHERE activo = 1 ORDER BY nombre ASC");
$productos = $stmt->fetchAll();
?>

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-800">Nuevo Pedido</h1>
    <p class="text-slate-500 text-sm">Registre los detalles del cliente y uniforme.</p>
</div>

<form action="guardar-pedido.php" method="POST" class="space-y-6">
    <!-- Bloque Cliente -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold">1</div>
            <h2 class="text-lg font-bold text-slate-700">Datos del Cliente</h2>
        </div>
        
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-500">Nombre del Cliente</label>
            <input type="text" name="cliente_nombre" required placeholder="Ej: Dra. María García" 
                class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-500">WhatsApp</label>
                <input type="tel" name="cliente_telefono" required placeholder="10 dígitos" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-500">Entrega</label>
                <select name="metodo_entrega" class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Retiro en Tienda">Retiro</option>
                    <option value="Envío">Envío</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bloque Confección -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-5">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold">2</div>
            <h2 class="text-lg font-bold text-slate-700">Detalles de Confección</h2>
        </div>

        <!-- Selector de Modelo Dinámico -->
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-500">Modelo de Uniforme</label>
            <select name="producto_id" required class="w-full h-14 px-4 rounded-xl border border-slate-200 text-base bg-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-600">
                <option value="">-- Seleccione Modelo --</option>
                <?php foreach ($productos as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?> (<?= formatMoney($p['precio_base']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-500">Color</label>
                <input type="text" name="color" placeholder="Ej: Azul Rey" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-500">Tela</label>
                <input type="text" name="tela" placeholder="Ej: Antifluido" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <!-- Tallas Pills -->
        <?php foreach (['superior' => 'Talla Superior', 'inferior' => 'Talla Inferior'] as $key => $label): ?>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-500"><?= $label ?></label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach (['XS', 'S', 'M', 'L', 'XL', 'N/A'] as $t): ?>
                        <div class="flex-1 min-w-[50px]">
                            <input type="radio" name="talla_<?= $key ?>" id="<?= $key ?>_<?= $t ?>" value="<?= $t ?>" class="hidden pill-input" <?= $t == 'M' ? 'checked' : '' ?>>
                            <label for="<?= $key ?>_<?= $t ?>" class="flex items-center justify-center h-12 rounded-xl border border-slate-200 text-sm font-black cursor-pointer transition-all active:scale-90">
                                <?= $t ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-500">Bordado / Estampado / Notas</label>
            <textarea name="estampado_bordado" rows="3" placeholder="Ej: Logo IMSS pecho izq, Nombre 'Dra. García' en cursiva..." 
                class="w-full p-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
        </div>

        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-500">Cantidad</label>
            <input type="number" name="cantidad" value="1" min="1" 
                class="w-full h-14 px-4 rounded-xl border border-slate-200 text-xl font-bold text-center focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
    </div>

    <button type="submit" class="w-full h-16 bg-blue-600 text-white rounded-3xl font-black text-xl shadow-xl shadow-blue-100 active:scale-95 transition-all mb-10">
        GUARDAR PEDIDO
    </button>
</form>

<?php include 'footer.php'; ?>

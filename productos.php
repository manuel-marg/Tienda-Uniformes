<?php 
require_once 'config.php';
include 'header.php'; 

// Obtener todos los productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-800 tracking-tight">CATÁLOGO</h1>
    <p class="text-slate-500 text-sm">Gestiona los modelos y precios disponibles.</p>
</div>

<!-- Alertas de Éxito / Error -->
<?php if ($success === 'registered'): ?>
    <div class="mb-4 p-4 bg-green-50 text-green-700 border border-green-100 rounded-2xl text-sm font-bold text-center">
        ¡Producto registrado correctamente!
    </div>
<?php elseif ($success === 'modified'): ?>
    <div class="mb-4 p-4 bg-blue-50 text-blue-700 border border-blue-100 rounded-2xl text-sm font-bold text-center">
        ¡Producto modificado con éxito!
    </div>
<?php elseif ($success === 'deleted'): ?>
    <div class="mb-4 p-4 bg-red-50 text-red-700 border border-red-100 rounded-2xl text-sm font-bold text-center">
        ¡Producto eliminado de forma permanente!
    </div>
<?php elseif ($error === 'foreign_key'): ?>
    <div class="mb-4 p-4 bg-yellow-50 text-yellow-700 border border-yellow-100 rounded-2xl text-sm font-bold text-left space-y-1">
        <p class="font-black">⚠️ No se puede eliminar el producto</p>
        <p class="text-xs leading-relaxed">Este modelo está asociado a pedidos guardados en el historial. Para no dañar tus reportes contables y registros, el sistema bloquea su eliminación física.</p>
    </div>
<?php endif; ?>

<!-- Formulario de Registro Rápido -->
<details class="bg-white rounded-3xl shadow-sm border border-slate-100 mb-6 overflow-hidden group">
    <summary class="flex items-center justify-between p-5 cursor-pointer list-none">
        <div class="flex items-center gap-3">
            <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <span class="font-bold text-slate-700 uppercase tracking-tight text-sm">Nuevo Modelo</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </summary>
    <div class="px-5 pb-5 pt-2 border-t border-slate-50">
        <form action="guardar-producto.php" method="POST" class="space-y-4">
            <input type="hidden" name="accion" value="registrar">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nombre del Modelo</label>
                <input type="text" name="nombre" required placeholder="Ej: Mono Quirúrgico Clásico" 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Descripción</label>
                <textarea name="descripcion" rows="2" placeholder="Detalles de la tela o corte..." 
                    class="w-full p-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Precio Base</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">$</span>
                    <input type="number" step="0.01" name="precio_base" required placeholder="0.00" 
                        class="w-full h-12 pl-8 pr-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>
            </div>
            <button type="submit" class="w-full h-14 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-wider shadow-lg shadow-blue-100 active:scale-95 transition-all">
                Registrar Producto
            </button>
        </form>
    </div>
</details>

<!-- Listado en Tarjetas -->
<div class="space-y-4">
    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-2">Modelos Registrados (<?= count($productos) ?>)</h2>
    
    <?php foreach ($productos as $prod): ?>
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-50 flex flex-col gap-4 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-black text-slate-800 text-lg uppercase leading-tight"><?= htmlspecialchars($prod['nombre']) ?></h3>
                    <p class="text-slate-400 text-xs mt-1 leading-relaxed"><?= htmlspecialchars($prod['descripcion'] ?: 'Sin descripción') ?></p>
                </div>
                <span class="text-blue-600 font-black text-2xl tracking-tighter"><?= formatMoney($prod['precio_base']) ?></span>
            </div>
            
            <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-4 mt-1">
                <!-- Botón Modificar -->
                <button type="button" 
                    onclick="openEditModal(<?= $prod['id'] ?>, '<?= addslashes(htmlspecialchars($prod['nombre'])) ?>', '<?= addslashes(htmlspecialchars($prod['descripcion'])) ?>', <?= $prod['precio_base'] ?>)"
                    class="h-12 border border-slate-200 text-slate-600 rounded-2xl text-xs font-bold active:bg-slate-50 flex items-center justify-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    MODIFICAR
                </button>
                
                <!-- Botón Eliminar -->
                <form action="guardar-producto.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar permanentemente este modelo del catálogo?')">
                    <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                    <input type="hidden" name="accion" value="eliminar">
                    <button type="submit" class="w-full h-12 border border-red-100 text-red-500 rounded-2xl text-xs font-bold active:bg-red-50 flex items-center justify-center gap-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        ELIMINAR
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Flotante de Edición (Mobile-First) -->
<div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-md rounded-t-[2.5rem] sm:rounded-[2.5rem] p-6 shadow-2xl space-y-6 transform translate-y-full transition-transform duration-300 ease-out" id="modalContainer">
        
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Modificar Modelo</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Edita los detalles del catálogo</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="p-2 bg-slate-100 rounded-full text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="guardar-producto.php" method="POST" class="space-y-4">
            <input type="hidden" name="accion" value="modificar">
            <input type="hidden" name="id" id="edit_id">

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nombre del Modelo</label>
                <input type="text" name="nombre" id="edit_nombre" required 
                    class="w-full h-12 px-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Descripción</label>
                <textarea name="descripcion" id="edit_descripcion" rows="2"
                    class="w-full p-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Precio Base</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">$</span>
                    <input type="number" step="0.01" name="precio_base" id="edit_precio_base" required 
                        class="w-full h-12 pl-8 pr-4 rounded-xl border border-slate-200 text-base focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>
            </div>

            <button type="submit" class="w-full h-16 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-wider shadow-xl shadow-blue-100 active:scale-95 transition-all">
                GUARDAR CAMBIOS
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, nombre, descripcion, precio) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_descripcion').value = descripcion;
    document.getElementById('edit_precio_base').value = precio;
    
    const modal = document.getElementById('editModal');
    const container = document.getElementById('modalContainer');
    
    modal.classList.remove('hidden');
    // Pequeño delay para permitir que Tailwind renderice el hidden antes de la animación
    setTimeout(() => {
        container.classList.remove('translate-y-full');
    }, 10);
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const container = document.getElementById('modalContainer');
    
    container.classList.add('translate-y-full');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>

<?php include 'footer.php'; ?>

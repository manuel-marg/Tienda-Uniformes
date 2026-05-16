    </main>

    <!-- Navegación Inferior Persistente (Mobile First) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 h-20 flex items-center justify-around max-w-md mx-auto z-50 px-4 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] rounded-t-3xl">
        <a href="index.php" class="flex flex-col items-center gap-1 group">
            <div class="p-2 rounded-xl transition-colors <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-blue-50 text-blue-600' : 'text-slate-400 group-hover:text-blue-500' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-tighter <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-blue-600' : 'text-slate-400' ?>">Pedidos</span>
        </a>

        <a href="nuevo-pedido.php" class="flex flex-col items-center -mt-10">
            <div class="bg-blue-600 text-white p-4 rounded-2xl shadow-xl shadow-blue-200 active:scale-90 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-tighter text-slate-400 mt-2">Nuevo</span>
        </a>

        <a href="productos.php" class="flex flex-col items-center gap-1 group">
            <div class="p-2 rounded-xl transition-colors <?= basename($_SERVER['PHP_SELF']) == 'productos.php' ? 'bg-blue-50 text-blue-600' : 'text-slate-400 group-hover:text-blue-500' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-tighter <?= basename($_SERVER['PHP_SELF']) == 'productos.php' ? 'text-blue-600' : 'text-slate-400' ?>">Catálogo</span>
        </a>
    </div>
</body>
</html>

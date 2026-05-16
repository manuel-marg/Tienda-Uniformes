<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Uniformes Médicos - Gestión</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; -webkit-tap-highlight-color: transparent; }
        .pill-input:checked + label { background-color: #3b82f6; color: white; border-color: #3b82f6; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 pb-24">
    <!-- Navegación Superior -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <a href="index.php" class="text-xl font-black text-blue-600 flex items-center gap-2">
                <span class="bg-blue-600 text-white p-1.5 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                </span>
                UNIFORMES
            </a>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">En Línea</span>
            </div>
        </div>
    </nav>

    <main class="max-w-md mx-auto p-4">

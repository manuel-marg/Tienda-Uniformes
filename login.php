<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = cleanInput($_POST['usuario']);
    $password = $_POST['password']; // Texto plano según requerimiento

    $stmt = $pdo->prepare("SELECT id, usuario, password, nombre FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        header("Location: index.php");
        exit();
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Uniformes Médicos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200 border border-slate-100">
        <div class="text-center mb-8">
            <div class="bg-blue-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">BIENVENIDO</h1>
            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.2em] mt-1">Ingresa tus credenciales</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-500 p-4 rounded-2xl text-sm font-bold mb-6 text-center border border-red-100">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="space-y-1">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Usuario</label>
                <input type="text" name="usuario" required autofocus
                    class="w-full h-14 px-6 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-lg font-medium">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full h-14 px-6 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-lg font-medium">
            </div>

            <button type="submit" class="w-full h-16 bg-blue-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-blue-100 active:scale-95 transition-all">
                ACCEDER
            </button>
        </form>
    </div>
</body>
</html>

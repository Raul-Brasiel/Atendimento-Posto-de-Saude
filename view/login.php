<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso - Sistema de Saúde</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../View/CSS/global.css">
    <link rel="stylesheet" href="../View/CSS/login.css">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <header class="w-full border-b border-gray-200 py-3 px-4 flex justify-between items-center">
        <div class="flex items-center select-none text-2xl">
            <span class="logo-g">g</span><span class="logo-o">o</span><span class="logo-v">v</span><span class="logo-br">.br</span>
        </div>
    </header>
    <main class="flex-grow flex items-center justify-center p-4">
        <div class="w-full max-w-md mx-auto">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Acesse sua conta:</h2>
            
            <?php if(!empty($erro)): ?>
                <div class="bg-red-50 border-l-4 border-red-600 text-red-700 p-4 mb-6 text-sm font-bold">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <form action="../Control/login.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Usuário</label>
                    <input type="text" name="login" placeholder="CPF, CRM ou Nome" class="w-full p-3 border border-gray-400 rounded outline-none focus:ring-2 focus:ring-blue-800" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Senha</label>
                    <input type="password" name="senha" placeholder="Sua senha" class="w-full p-3 border border-gray-400 rounded outline-none focus:ring-2 focus:ring-blue-800">
                </div>
                <button type="submit" class="w-full btn-gov py-3 font-bold shadow-md hover:bg-blue-900 transition">Entrar</button>
            </form>
        </div>
    </main>
</body>
</html>
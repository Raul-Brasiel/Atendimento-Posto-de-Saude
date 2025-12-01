<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Fila</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../View/CSS/global.css">
    <link rel="stylesheet" href="../View/CSS/fila.css">
</head>
<body class="flex flex-col min-h-screen" onload="iniciarRelogio()">

    <header class="bg-white border-b py-2 px-8 shadow-sm flex justify-between items-center sticky top-0 z-50 h-20">
        <div class="font-bold text-xl text-gray-700 flex items-center gap-2 w-1/3">
            <i class="fas fa-hospital-user text-blue-800"></i> <span>Gestão de Saúde</span>
        </div>
        <div class="flex flex-col items-center justify-center w-1/3">
            <div id="relogio-hora" class="text-3xl font-black text-blue-900 leading-none">00:00:00</div>
            <div id="relogio-data" class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-1">Data</div>
        </div>
        <div class="w-1/3 flex justify-end items-center gap-4">
            <span class="text-sm bg-gray-100 px-3 py-1 rounded-full border font-bold text-gray-600">
                <?php echo ucfirst($tipoUsuario); ?>
            </span>
            <a href="login.php" class="text-red-600 text-sm font-bold hover:underline">Sair</a>
        </div>
    </header>

    <div class="bg-gray-100 px-4 py-3 flex justify-center gap-4 border-b border-gray-200">
        <button onclick="mudarAba('painel')" id="btn-painel" class="px-6 py-2 rounded-full font-bold bg-blue-600 text-white shadow transition">Painel Público</button>
        <?php if($tipoUsuario != 'paciente'): ?>
            <button onclick="mudarAba('recepcao')" id="btn-recepcao" class="px-6 py-2 rounded-full font-bold bg-white text-gray-600 border hover:bg-gray-50 transition">Recepção & Fila</button>
        <?php endif; ?>
        <?php if($tipoUsuario == 'medico'): ?>
            <button onclick="mudarAba('medico')" id="btn-medico" class="px-6 py-2 rounded-full font-bold bg-white text-gray-600 border hover:bg-indigo-50 transition">Meu Consultório</button>
        <?php endif; ?>
    </div>

    <main class="flex-grow p-6 max-w-7xl mx-auto w-full">
        <!-- PAINEL -->
        <div id="view-painel">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Painel de Chamadas</h2>
            </div>
            
            <?php if (count($listaPainel) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($listaPainel as $p): 
                        preg_match('/Tipo: (.*?) \|/', $p['descricao'], $matches);
                        $tipoTitulo = isset($matches[1]) ? $matches[1] : 'Atendimento';
                        $status = $p['status'];
                        
                        $cardClass = 'bg-white border-l-4 border-gray-300 shadow-sm opacity-80';
                        $badgeClass = 'bg-gray-100 text-gray-600';
                        $textoStatus = 'AGUARDANDO CHEGADA';

                        if ($status == 'Na Fila' || $status == 'Aguardando') {
                            $cardClass = 'bg-white border-l-4 border-blue-500 shadow-md';
                            $badgeClass = 'bg-blue-100 text-blue-800';
                            $textoStatus = 'NA FILA DE ESPERA';
                        }
                        elseif ($status == 'Em Atendimento') {
                            $cardClass = 'bg-green-50 border-l-8 border-green-500 shadow-xl scale-105';
                            $badgeClass = 'bg-green-600 text-white animate-pulse';
                            $textoStatus = 'EM ATENDIMENTO';
                        }
                    ?>
                        <div class="p-6 rounded-lg <?php echo $cardClass; ?> transition-all">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold px-2 py-1 rounded <?php echo $badgeClass; ?>"><?php echo $textoStatus; ?></span>
                                <span class="text-xs text-gray-400"><?php echo date('H:i', strtotime($p['data_hora'])); ?></span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 truncate"><?php echo htmlspecialchars($p['nome_paciente']); ?></h3>
                            <p class="text-blue-700 font-bold uppercase text-sm mt-1"><?php echo $tipoTitulo; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20 text-gray-400 border-2 border-dashed rounded bg-white">
                    <p>Nenhum paciente na fila.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- RECEPÇÃO -->
        <?php if($tipoUsuario != 'paciente'): ?>
            <div id="view-recepcao" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded shadow-md border-t-4 border-gray-400">
                        <h3 class="font-bold text-lg text-gray-700 mb-4 border-b pb-2">Aguardando Chegada</h3>
                        <?php foreach ($listaChegada as $p): ?>
                        <div class="flex justify-between items-center border-b py-2">
                            <span><?php echo $p['nome_paciente']; ?> (<?php echo date('H:i', strtotime($p['data_hora'])); ?>)</span>
                            <form method="POST" action="fila_atendimento.php">
                                <input type="hidden" name="acao" value="confirmar_chegada">
                                <input type="hidden" name="id_atendimento" value="<?php echo $p['id_atendimento']; ?>">
                                <button class="bg-blue-600 text-white text-xs font-bold px-3 py-2 rounded hover:bg-blue-700">CONFIRMAR</button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="bg-white p-6 rounded shadow-md border-t-4 border-blue-500">
                        <h3 class="font-bold text-lg text-gray-700 mb-4 border-b pb-2">Na Fila (Prontos)</h3>
                        <?php foreach ($listaNaFila as $p): ?>
                        <div class="flex justify-between items-center border-b py-2">
                            <span><?php echo $p['nome_paciente']; ?></span>
                            <div class="flex gap-2">
                                <form method="POST" action="fila_atendimento.php">
                                    <input type="hidden" name="acao" value="chamar">
                                    <input type="hidden" name="id_atendimento" value="<?php echo $p['id_atendimento']; ?>">
                                    <button class="bg-green-600 text-white text-xs font-bold px-3 py-2 rounded hover:bg-green-700 shadow">CHAMAR</button>
                                </form>
                                <form method="POST" action="fila_atendimento.php" onsubmit="return confirm('Cancelar?');">
                                    <input type="hidden" name="acao" value="cancelar">
                                    <input type="hidden" name="id_atendimento" value="<?php echo $p['id_atendimento']; ?>">
                                    <button class="bg-red-100 text-red-600 text-xs font-bold px-3 py-2 rounded hover:bg-red-200">X</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- CONSULTÓRIO -->
        <?php if($tipoUsuario == 'medico'): ?>
            <div id="view-medico" class="hidden">
                <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg mb-6">
                    <h3 class="font-bold text-indigo-900 text-lg">Dr(a). <?php echo $_SESSION['user_nome']; ?></h3>
                    <p class="text-sm text-indigo-700">Pacientes em atendimento.</p>
                </div>
                <?php foreach ($listaMeuAtendimento as $at): ?>
                <div class="bg-white rounded-lg shadow-lg border-l-4 border-indigo-500 overflow-hidden mb-6">
                    <div class="bg-gray-50 px-6 py-3 border-b">
                        <h3 class="font-bold text-xl text-gray-800"><?php echo htmlspecialchars($at['nome_paciente']); ?></h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-yellow-50 p-4 rounded border border-yellow-100 mb-6 text-sm text-gray-700">
                            <?php echo nl2br(htmlspecialchars($at['descricao'])); ?>
                        </div>
                        <form method="POST" action="fila_atendimento.php">
                            <input type="hidden" name="acao_medico" value="finalizar">
                            <input type="hidden" name="id_atendimento" value="<?php echo $at['id_atendimento']; ?>">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Diagnóstico & Conduta</label>
                            <textarea name="diagnostico" rows="3" required class="w-full p-3 border rounded bg-gray-50 mb-4"></textarea>
                            <button class="bg-indigo-600 text-white font-bold px-6 py-2 rounded hover:bg-indigo-800">Finalizar Atendimento</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function iniciarRelogio() { atualizarRelogio(); setInterval(atualizarRelogio, 1000); }
        function atualizarRelogio() {
            const agora = new Date();
            const h = String(agora.getHours()).padStart(2,'0');
            const m = String(agora.getMinutes()).padStart(2,'0');
            const s = String(agora.getSeconds()).padStart(2,'0');
            document.getElementById('relogio-hora').textContent = `${h}:${m}:${s}`;
            const opts = { weekday:'long', day:'numeric', month:'long' };
            document.getElementById('relogio-data').textContent = agora.toLocaleDateString('pt-BR', opts);
        }
        function mudarAba(aba) {
            ['view-painel', 'view-recepcao', 'view-medico'].forEach(id => { const el = document.getElementById(id); if(el) el.classList.add('hidden'); });
            ['btn-painel', 'btn-recepcao', 'btn-medico'].forEach(id => { const btn = document.getElementById(id); if(btn) { btn.classList.remove('bg-blue-600', 'bg-indigo-600', 'text-white', 'shadow'); btn.classList.add('bg-white', 'text-gray-600', 'border'); }});
            document.getElementById('view-'+aba).classList.remove('hidden');
            const btn = document.getElementById('btn-'+aba);
            btn.classList.remove('bg-white', 'text-gray-600', 'border');
            if(aba=='painel') btn.classList.add('bg-blue-600', 'text-white', 'shadow');
            else if(aba=='medico') btn.classList.add('bg-indigo-600', 'text-white', 'shadow');
            else btn.classList.add('bg-gray-700', 'text-white', 'shadow');
        }
    </script>
</body>
</html>
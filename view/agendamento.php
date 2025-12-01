<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Agendamentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../View/CSS/global.css">
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-white border-b py-3 px-8 shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="font-bold text-xl text-gray-700">
                <span class="text-blue-700">gov.br</span> <span class="font-normal text-gray-500">| Saúde</span>
            </div>
            <a href="pagina_paciente.php" class="text-sm text-gray-600 hover:text-blue-600 flex items-center gap-1">
                <i class="fas fa-home"></i> Início
            </a>
        </div>
    </header>

    <main class="flex-grow py-10 px-4">
        <div class="max-w-3xl mx-auto">
            
            <?php echo $msg; ?>

            <!-- TELA LISTA -->
            <div id="tela-lista" class="<?php echo $mostrarFormulario ? 'hidden' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gov-blue flex items-center gap-2">
                        <i class="fas fa-list-alt"></i> Meus Agendamentos
                    </h1>
                    <button onclick="mostrarFormulario()" class="bg-gov-blue text-white px-4 py-2 rounded-full font-bold hover:bg-blue-900 transition shadow flex items-center gap-2">
                        <i class="fas fa-plus"></i> Novo Agendamento
                    </button>
                </div>

                <?php if (count($meusAgendamentos) > 0): ?>
                    <div class="space-y-4">
                        <?php foreach ($meusAgendamentos as $agendamento): ?>
                            <?php 
                                preg_match('/Tipo: (.*?) \|/', $agendamento['descricao'], $matches);
                                $tipoTitulo = isset($matches[1]) ? $matches[1] : 'Atendimento';
                                
                                $status = $agendamento['status'];
                                $statusCor = 'border-gray-400';
                                $statusBadge = 'bg-gray-100 text-gray-800';

                                if ($status == 'Agendado' || $status == 'Aguardando Chegada') { $statusCor = 'border-blue-400'; $statusBadge = 'bg-blue-100 text-blue-800'; }
                                if ($status == 'Aguardando' || $status == 'Na Fila') { $statusCor = 'border-yellow-400'; $statusBadge = 'bg-yellow-100 text-yellow-800'; }
                                if ($status == 'Em Atendimento') { $statusCor = 'border-green-500'; $statusBadge = 'bg-green-100 text-green-800 animate-pulse'; }
                                if ($status == 'Finalizado') { $statusCor = 'border-gray-300'; $statusBadge = 'bg-gray-200 text-gray-600'; }
                                if ($status == 'Cancelado') { $statusCor = 'border-red-300'; $statusBadge = 'bg-red-100 text-red-800 line-through'; }
                            ?>

                            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 <?php echo $statusCor; ?> hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-xl text-gray-800 mb-1"><?php echo $tipoTitulo; ?></h3>
                                        <p class="text-gray-600 flex items-center gap-2">
                                            <i class="fas fa-hospital text-gov-blue"></i> <?php echo $agendamento['nome_posto']; ?>
                                        </p>
                                        <p class="text-gray-600 flex items-center gap-2">
                                            <i class="fas fa-calendar-alt text-gov-blue"></i> 
                                            <?php echo date('d/m/Y', strtotime($agendamento['data_hora'])); ?> 
                                            às <?php echo date('H:i', strtotime($agendamento['data_hora'])); ?>
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo $statusBadge; ?>">
                                        <?php echo $agendamento['status']; ?>
                                    </span>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-gray-500">
                                    <p class="font-mono bg-gray-50 p-2 rounded overflow-x-auto">
                                        <?php echo str_replace("\n", "<br>", $agendamento['descricao']); ?>
                                    </p>
                                    <?php if($agendamento['status'] == 'Finalizado' && !empty($agendamento['diagnostico'])): ?>
                                        <div class="mt-2 bg-green-50 p-2 rounded text-green-800 border border-green-100">
                                            <strong>Diagnóstico:</strong> <?php echo $agendamento['diagnostico']; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($status == 'Agendado' || $status == 'Aguardando Chegada'): ?>
                                        <form method="POST" action="agendamento.php" onsubmit="return confirm('Cancelar este agendamento?');" class="mt-3 text-right">
                                            <input type="hidden" name="acao" value="cancelar_agendamento">
                                            <input type="hidden" name="id_atendimento" value="<?php echo $agendamento['id_atendimento']; ?>">
                                            <button type="submit" class="text-red-600 text-sm font-bold hover:underline hover:text-red-800 flex items-center justify-end gap-1 w-full">
                                                <i class="fas fa-times-circle"></i> Cancelar Agendamento
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                        <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Você ainda não possui agendamentos.</p>
                        <button onclick="mostrarFormulario()" class="mt-4 text-gov-blue font-bold hover:underline">Clique aqui para agendar</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TELA FORMULÁRIO -->
            <div id="tela-formulario" class="<?php echo $mostrarFormulario ? '' : 'hidden'; ?>">
                <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gov-blue flex items-center gap-2">
                            <i class="fas fa-calendar-plus"></i> Nova Solicitação
                        </h1>
                        <button type="button" onclick="mostrarLista()" class="text-gray-500 hover:text-gov-blue text-sm font-bold">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                    <form method="POST" action="agendamento.php">
                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2">Unidade de Saúde</label>
                            <select name="posto" required class="w-full p-3 border rounded bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <?php foreach ($postos as $p): ?>
                                    <option value="<?php echo $p['id_posto']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2">Tipo de Atendimento</label>
                            <select name="tipo_atendimento" required class="w-full p-3 border rounded bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <option value="Clínica Geral">Clínica Geral</option>
                                <option value="Odontologia">Odontologia (Dentista)</option>
                                <option value="Vacinação">Vacinação</option>
                                <option value="Enfermagem">Enfermagem</option>
                                <option value="Psicologia">Psicologia</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Idade</label>
                                <input type="number" name="idade" required min="0" class="w-full p-3 border rounded">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">PCD?</label>
                                <select name="pcd" required class="w-full p-3 border rounded">
                                    <option value="Nao">Não</option>
                                    <option value="Sim - Motora">Sim - Motora</option>
                                    <option value="Sim - Visual">Sim - Visual</option>
                                    <option value="Sim - Auditiva">Sim - Auditiva</option>
                                    <option value="Sim - Intelectual">Sim - Intelectual</option>
                                    <option value="Sim - Outra">Sim - Outra</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-5 bg-gray-50 p-4 rounded border">
                            <span class="block text-gray-700 font-bold mb-2">Doença crônica?</span>
                            <div class="flex gap-6 mb-3">
                                <label class="flex items-center gap-2"><input type="radio" name="doenca_cronica" value="nao" checked onclick="toggleDoenca(false)"> Não</label>
                                <label class="flex items-center gap-2"><input type="radio" name="doenca_cronica" value="sim" onclick="toggleDoenca(true)"> Sim</label>
                            </div>
                            <div id="campo_qual_doenca" class="hidden mt-2">
                                <input type="text" name="qual_doenca" id="input_doenca" placeholder="Qual doença?" class="w-full p-2 border rounded">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Data</label>
                                <input type="date" name="data" required min="<?php echo date('Y-m-d'); ?>" class="w-full p-3 border rounded">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Período</label>
                                <select name="periodo" required class="w-full p-3 border rounded">
                                    <option value="">Selecione...</option>
                                    <option value="manha">Manhã (07h - 12h)</option>
                                    <option value="tarde">Tarde (13h - 18h)</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gov-blue text-white font-bold py-4 rounded-full hover:bg-blue-900 shadow-lg">Confirmar Agendamento</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        function mostrarFormulario() { document.getElementById('tela-lista').classList.add('hidden'); document.getElementById('tela-formulario').classList.remove('hidden'); }
        function mostrarLista() { document.getElementById('tela-formulario').classList.add('hidden'); document.getElementById('tela-lista').classList.remove('hidden'); }
        function toggleDoenca(temDoenca) {
            const campo = document.getElementById('campo_qual_doenca');
            const input = document.getElementById('input_doenca');
            if (temDoenca) { campo.classList.remove('hidden'); input.required = true; } 
            else { campo.classList.add('hidden'); input.required = false; input.value = ''; }
        }
    </script>
</body>
</html>
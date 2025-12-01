<?php
session_start();
require_once '../model/Classes.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$postoModel = new PostoSaude();
$medicoModel = new Medico();
$atendimentoModel = new Atendimento();
$pacienteModel = new Paciente();

$pacienteModel->buscarPorId($_SESSION['user_id']);
$idUsuario = $_SESSION['user_id'];

$msg = "";
$mostrarFormulario = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ação: Cancelar
    if (isset($_POST['acao']) && $_POST['acao'] == 'cancelar_agendamento') {
        if($atendimentoModel->cancelarAgendamento($_POST['id_atendimento'])) {
            $msg = "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded'><p class='font-bold'>Sucesso!</p><p>Agendamento cancelado.</p></div>";
        } else {
            $msg = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded'>Erro ao cancelar.</div>";
        }
    } 
    // Ação: Novo Agendamento
    else {
        $mostrarFormulario = true; 
        $idPosto = $_POST['posto'];
        $tipoAtendimento = $_POST['tipo_atendimento'];
        $idade = $_POST['idade'];
        $pcd = $_POST['pcd'];
        $temDoenca = $_POST['doenca_cronica']; 
        $nomeDoenca = ($temDoenca == 'sim') ? $_POST['qual_doenca'] : 'Nenhuma';
        $data = $_POST['data'];
        $periodo = $_POST['periodo'];

        if ($atendimentoModel->verificarDuplicidade($idUsuario, $tipoAtendimento)) {
            $msg = "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded'><p class='font-bold'>Atenção!</p><p>Você já possui um agendamento <strong>ATIVO</strong>.</p></div>";
        } else {
            $hora = ($periodo == 'manha') ? '08:00:00' : '13:00:00';
            $dataHoraCompleta = $data . ' ' . $hora;
            $descricaoDetalhada = "Tipo: $tipoAtendimento | Idade: $idade | PCD: $pcd | Doença: $nomeDoenca | PACIENTE: " . $pacienteModel->getNome() . " | CPF: " . $pacienteModel->getCpf();
            $medicosDoPosto = $medicoModel->listarPorPosto($idPosto);
            
            if (count($medicosDoPosto) > 0) {
                $idMedicoAuto = $medicosDoPosto[0]['id_medico'];
                if ($atendimentoModel->registrarAgendamento($idUsuario, $idMedicoAuto, $idPosto, $dataHoraCompleta, $descricaoDetalhada)) {
                    $msg = "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded'><p class='font-bold'>Sucesso!</p><p>Agendamento realizado.</p></div>";
                    $mostrarFormulario = false; 
                } else {
                    $msg = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded'>Erro ao gravar.</div>";
                }
            } else {
                $msg = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded'>Posto sem médicos.</div>";
            }
        }
        header("Location: fila_atendimento.php");
    }
}

// Dados para a View
$postos = $postoModel->listarTodos();
$meusAgendamentos = $atendimentoModel->listarPorPaciente($idUsuario);

// CARREGA A VIEW
include '../view/agendamento.php';
?>
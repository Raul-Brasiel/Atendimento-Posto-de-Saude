<?php
session_start();
require_once '../model/Classes.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$tipoUsuario = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'paciente';
$idUsuarioLogado = $_SESSION['user_id'];

$filaModel = new Fila();
$postoModel = new PostoSaude();
$atendimentoModel = new Atendimento();

$postos = $postoModel->listarTodos();
$idPostoAtual = isset($_GET['posto']) ? $_GET['posto'] : (isset($postos[0]['id_posto']) ? $postos[0]['id_posto'] : 1);

// --- DADOS PARA A VIEW ---
$listaPainel = $filaModel->listarFilaPainel($idPostoAtual);

$listaChegada = [];
$listaNaFila = [];
if ($tipoUsuario == 'recepcionista' || $tipoUsuario == 'medico') {
    $listaChegada = $filaModel->listarAguardandoChegada($idPostoAtual);
    $listaNaFila = array_filter($listaPainel, function($p) { 
        return in_array($p['status'], ['Na Fila', 'Aguardando']); 
    });
}

$listaMeuAtendimento = [];
if ($tipoUsuario == 'medico') {
    $listaMeuAtendimento = $atendimentoModel->listarEmAtendimentoMedico($idUsuarioLogado);
}

// --- PROCESSAMENTO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] == 'confirmar_chegada') {
        $filaModel->confirmarChegada($_POST['id_atendimento']);
        header("Location: fila_atendimento.php?posto=$idPostoAtual"); exit;
    }
    if (isset($_POST['acao']) && $_POST['acao'] == 'chamar') {
        $filaModel->chamarPaciente($_POST['id_atendimento']);
        header("Location: fila_atendimento.php?posto=$idPostoAtual"); exit;
    }
    if (isset($_POST['acao']) && $_POST['acao'] == 'cancelar') {
        $filaModel->cancelarFila($_POST['id_atendimento']);
        header("Location: fila_atendimento.php?posto=$idPostoAtual"); exit;
    }
    if (isset($_POST['acao_medico']) && $_POST['acao_medico'] == 'finalizar') {
        $atendimentoModel->finalizarAtendimento($_POST['id_atendimento'], $_POST['diagnostico']);
        header("Location: fila_atendimento.php?posto=$idPostoAtual&msg=sucesso"); exit;
    }
}

// CARREGA A VIEW
include '../view/fila_atendimento.php';
?>
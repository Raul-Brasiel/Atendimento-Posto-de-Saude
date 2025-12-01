<?php
session_start();
require_once '../model/Classes.php';

// Segurança
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

// Prepara dados para a View
$nomeUsuario = $_SESSION['user_nome'];
$iniciais = strtoupper(substr($nomeUsuario, 0, 2));

$servicoModel = new Servico();
$listaServicos = $servicoModel->listarTodos();

// CARREGA A VIEW
include '../view/pagina_paciente.php';
?>
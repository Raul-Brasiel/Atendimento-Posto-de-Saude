<?php
session_start();
require_once '../model/Classes.php'; 

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login']; 
    $senha = $_POST['senha'];
    
    $paciente = new Paciente();
    $medico = new Medico();
    $recepcionista = new Recepcionista();

    if ($paciente->autenticar($login, $senha)) {
        $_SESSION['user_id'] = $paciente->getId();
        $_SESSION['user_nome'] = $paciente->getNome();
        $_SESSION['user_tipo'] = 'paciente';
        header("Location: pagina_paciente.php");
        exit;
    }
    else if ($medico->autenticar($login, $senha)) {
        $_SESSION['user_id'] = $medico->getId();
        $_SESSION['user_nome'] = $medico->getNome();
        $_SESSION['user_tipo'] = 'medico';
        header("Location: fila_atendimento.php");
        exit;
    }
    else if ($recepcionista->autenticar($login, $senha)) {
        $_SESSION['user_id'] = $recepcionista->getId();
        $_SESSION['user_nome'] = $recepcionista->getNome();
        $_SESSION['user_tipo'] = 'recepcionista';
        header("Location: fila_atendimento.php");
        exit;
    }
    else {
        $erro = "Dados inválidos. Verifique CPF/CRM e senha.";
    }
}

// CARREGA A VIEW (HTML)
include '../view/login.php';
?>
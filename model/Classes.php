<?php
require_once 'ConnectionFactory.php';

/* --- CLASSE PACIENTE --- */
class Paciente {
    private $id; private $nome; private $cpf; private $dataNascimento;
    private $endereco; private $telefone; private $email; private $cartaoSus;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getCpf() { return $this->cpf; }
    public function getTelefone() { return $this->telefone; }
    public function getCartaoSus() { return $this->cartaoSus; }

    public function verificarCpfExistente($cpf) {
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE cpf = ?");
        $stmt->execute([$cpf]);
        return $stmt->fetch() ? true : false;
    }

    public function autenticar($cpf, $senha) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "SELECT * FROM pacientes WHERE cpf = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cpf]);
        $dados = $stmt->fetch();

        if ($dados && password_verify($senha, $dados['senha_hash'])) {
            $this->preencherDados($dados);
            return true;
        }
        return false;
    }

    public function buscarPorId($id) {
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->execute([$id]);
        $dados = $stmt->fetch();
        if ($dados) { $this->preencherDados($dados); return true; }
        return false;
    }

    private function preencherDados($dados) {
        $this->id = $dados['id'];
        $this->nome = $dados['nome'];
        $this->cpf = $dados['cpf'];
        $this->telefone = isset($dados['telefone']) ? $dados['telefone'] : null;
        $this->cartaoSus = isset($dados['cartao_sus']) ? $dados['cartao_sus'] : null;
    }
}

/* --- CLASSE SERVIÇO --- */
class Servico {
    public function listarTodos() {
        $pdo = ConnectionFactory::getConnection();
        try { return $pdo->query("SELECT * FROM servicos ORDER BY ordem ASC")->fetchAll(); } 
        catch (Exception $e) { return []; }
    }
}

/* --- CLASSE POSTO DE SAÚDE --- */
class PostoSaude {
    public function listarTodos() {
        $pdo = ConnectionFactory::getConnection();
        return $pdo->query("SELECT id_posto, nome, endereco FROM postos_saude")->fetchAll();
    }
}

/* --- CLASSE MÉDICO --- */
class Medico {
    private $id; private $nome; private $especialidade;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }

    public function listarPorPosto($idPosto) {
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare("SELECT id_medico, nome, especialidade FROM medicos WHERE id_posto = ?");
        $stmt->execute([$idPosto]);
        return $stmt->fetchAll();
    }

    public function autenticar($crm, $senha) {
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM medicos WHERE crm = ?");
        $stmt->execute([$crm]);
        $dados = $stmt->fetch();

        if ($dados && $senha == $dados['senha_acesso']) { 
            $this->id = $dados['id_medico'];
            $this->nome = $dados['nome'];
            return true;
        }
        return false;
    }
}

/* --- CLASSE RECEPCIONISTA --- */
class Recepcionista {
    private $id; private $nome;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }

    public function autenticar($nome, $senha) {
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM recepcionistas WHERE nome = ?");
        $stmt->execute([$nome]);
        $dados = $stmt->fetch();

        if ($dados && $senha == $dados['senha_acesso']) {
            $this->id = $dados['id_recepcionista'];
            $this->nome = $dados['nome'];
            return true;
        }
        return false;
    }
}

/* --- CLASSE ATENDIMENTO --- */
class Atendimento {
    public function registrarAgendamento($idPaciente, $idMedico, $idPosto, $dataHora, $descricao) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "INSERT INTO atendimentos (data_hora, descricao, status, id_paciente, id_medico, id_posto) 
                VALUES (?, ?, 'Aguardando Chegada', ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$dataHora, $descricao, $idPaciente, $idMedico, $idPosto]);
    }

    public function listarPorPaciente($idPaciente) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "SELECT a.*, p.nome as nome_posto FROM atendimentos a 
                JOIN postos_saude p ON a.id_posto = p.id_posto 
                WHERE a.id_paciente = ? ORDER BY a.data_hora DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idPaciente]);
        return $stmt->fetchAll();
    }

    public function verificarDuplicidade($idPaciente, $tipoAtendimento) {
        $pdo = ConnectionFactory::getConnection();
        $termo = "%Tipo: " . $tipoAtendimento . "%";
        $stmt = $pdo->prepare("SELECT id_atendimento FROM atendimentos WHERE id_paciente = ? AND descricao LIKE ? AND status NOT IN ('Finalizado', 'Cancelado')");
        $stmt->execute([$idPaciente, $termo]);
        return $stmt->fetch() ? true : false;
    }

    public function listarEmAtendimentoMedico($idMedico) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "SELECT a.*, p.nome as nome_paciente, p.cpf 
                FROM atendimentos a
                JOIN pacientes p ON a.id_paciente = p.id
                WHERE a.id_medico = ? AND a.status = 'Em Atendimento'
                ORDER BY a.data_hora ASC"; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMedico]);
        return $stmt->fetchAll();
    }

    public function listarPendentesMedico($idMedico) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "SELECT a.*, p.nome as nome_paciente, p.cpf 
                FROM atendimentos a
                JOIN pacientes p ON a.id_paciente = p.id
                WHERE a.id_medico = ? AND a.status != 'Finalizado' AND a.status != 'Cancelado'
                ORDER BY a.data_hora ASC, a.id_atendimento ASC"; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMedico]);
        return $stmt->fetchAll();
    }

    public function finalizarAtendimento($idAtendimento, $diagnostico) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "UPDATE atendimentos SET diagnostico = ?, status = 'Finalizado' WHERE id_atendimento = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$diagnostico, $idAtendimento]);
    }

    public function cancelarAgendamento($idAtendimento) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "UPDATE atendimentos SET status = 'Cancelado' WHERE id_atendimento = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$idAtendimento]);
    }
}

/* --- CLASSE FILA --- */
class Fila {
    public function listarAguardandoChegada($idPosto) {
        $pdo = ConnectionFactory::getConnection();
        $hoje = date('Y-m-d');
        $sql = "SELECT a.*, p.nome as nome_paciente 
                FROM atendimentos a
                JOIN pacientes p ON a.id_paciente = p.id
                WHERE a.id_posto = ? 
                AND a.status IN ('Aguardando Chegada', 'Agendado') 
                AND DATE(a.data_hora) = ?
                ORDER BY a.data_hora ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idPosto, $hoje]);
        return $stmt->fetchAll();
    }

    public function listarFilaPainel($idPosto) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "SELECT a.*, p.nome as nome_paciente 
                FROM atendimentos a
                JOIN pacientes p ON a.id_paciente = p.id
                WHERE a.id_posto = ? 
                AND a.status IN ('Aguardando Chegada', 'Agendado', 'Na Fila', 'Aguardando', 'Em Atendimento')
                ORDER BY FIELD(a.status, 'Em Atendimento', 'Na Fila', 'Aguardando', 'Aguardando Chegada', 'Agendado'), a.data_hora ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idPosto]);
        return $stmt->fetchAll();
    }

    public function confirmarChegada($idAtendimento) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "UPDATE atendimentos SET status = 'Na Fila' WHERE id_atendimento = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$idAtendimento]);
    }

    public function chamarPaciente($idAtendimento) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "UPDATE atendimentos SET status = 'Em Atendimento' WHERE id_atendimento = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$idAtendimento]);
    }

    public function cancelarFila($idAtendimento) {
        $pdo = ConnectionFactory::getConnection();
        $sql = "UPDATE atendimentos SET status = 'Cancelado' WHERE id_atendimento = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$idAtendimento]);
    }
    
    public function entrarNaFila($idPaciente, $idFila) { return true; }
    public function removerDaFila($idTicket) { return true; }
    public function listarPorPosto($idPosto) { return $this->listarFilaPainel($idPosto); }
    public function buscarFilasDoPosto($idPosto) {
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM filas WHERE id_posto = ?");
        $stmt->execute([$idPosto]);
        return $stmt->fetchAll();
    }
}
?>
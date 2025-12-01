-- 1. CRIAÇÃO DO BANCO
CREATE DATABASE IF NOT EXISTS posto_saude;
USE posto_saude;

-- Tabela Paciente
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    endereco VARCHAR(255),
    telefone VARCHAR(20),
    email VARCHAR(100),
    cartao_sus VARCHAR(20) UNIQUE,
    senha_hash VARCHAR(255) NOT NULL
);

-- Tabela PostoSaude
CREATE TABLE postos_saude (
    id_posto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    horario_funcionamento VARCHAR(100)
);

-- Tabela Medico
CREATE TABLE medicos (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    crm VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL,
    senha_acesso VARCHAR(255) NOT NULL,
    id_posto INT NOT NULL,
    FOREIGN KEY (id_posto) REFERENCES postos_saude(id_posto)
);

-- Tabela Recepcionista
CREATE TABLE recepcionistas (
    id_recepcionista INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    senha_acesso VARCHAR(255) NOT NULL,
    id_posto INT NOT NULL,
    FOREIGN KEY (id_posto) REFERENCES postos_saude(id_posto)
);

-- Tabela Fila (Tipos de Fila disponíveis no posto)
CREATE TABLE filas (
    id_fila INT AUTO_INCREMENT PRIMARY KEY,
    tipo_atendimento VARCHAR(100) NOT NULL,
    data_criacao DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'Ativa',
    id_posto INT NOT NULL,
    FOREIGN KEY (id_posto) REFERENCES postos_saude(id_posto)
);

-- Tabela Pacientes na Fila (Quem está esperando agora)
CREATE TABLE pacientes_filas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_fila INT NOT NULL,
    data_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
    FOREIGN KEY (id_fila) REFERENCES filas(id_fila)
);

-- Tabela Atendimento (Histórico e Agendamentos)
CREATE TABLE atendimentos (
    id_atendimento INT AUTO_INCREMENT PRIMARY KEY,
    data_hora DATETIME NOT NULL,
    descricao TEXT,
    diagnostico TEXT,
    status VARCHAR(50),
    id_paciente INT NOT NULL,
    id_medico INT NOT NULL,
    id_posto INT NOT NULL,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico),
    FOREIGN KEY (id_posto) REFERENCES postos_saude(id_posto)
);

-- Tabela Serviços (Dashboard Web)
CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    icone_class VARCHAR(50) NOT NULL,
    link_destino VARCHAR(255) DEFAULT '#',
    ordem INT DEFAULT 0
);

-- 1. Posto
INSERT INTO postos_saude (id_posto, nome, endereco, telefone, horario_funcionamento) VALUES 
(1, 'UBS Santa Terezinha', 'R. Oscar Rodarte, 1604 - Bairro Santa Terezinha', '(11) 4002-8922', 'Seg-Sex 07h às 18h');

-- 2. Pacientes (Senha para todos: 123456)
INSERT INTO pacientes (cpf, nome, data_nascimento, endereco, telefone, email, cartao_sus, senha_hash) VALUES 
('123.456.789-00', 'João da Silva', '1985-04-12', 'R. das Flores, 123', '(11) 98888-7777', 'joao@email.com', '700000000000001', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
('111.222.333-44', 'Maria Santos', '1990-06-20', 'Av. Brasil, 500', '(11) 97777-6666', 'maria@email.com', '700000000000002', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
('555.666.777-88', 'José Lima', '1955-01-15', 'R. do Bosque, 45', '(11) 95555-4444', 'jose@email.com', '700000000000003', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
('999.888.777-66', 'Ana Costa', '2000-12-05', 'Travessa da Paz, 8', '(11) 94444-3333', 'ana@email.com', '700000000000004', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa');

-- 3. Equipe
INSERT INTO medicos (id_medico, crm, nome, especialidade, senha_acesso, id_posto) VALUES 
(1, 'SP123456', 'Dra. Maria Oliveira', 'Clínica Geral', 'maria123', 1);

INSERT INTO recepcionistas (id_recepcionista, nome, senha_acesso, id_posto) VALUES 
(101, 'Carla Mendes', 'carla@ubs', 1);

-- 4. Filas Disponíveis
INSERT INTO filas (id_fila, tipo_atendimento, data_criacao, status, id_posto) VALUES 
(10, 'Clínico Geral', '2025-10-01', 'Ativa', 1),
(11, 'Vacinação', '2025-10-01', 'Ativa', 1),
(12, 'Odontologia', '2025-10-01', 'Ativa', 1),
(13, 'Prioritária (Idoso/PCD)', '2025-10-01', 'Ativa', 1);

-- 5. Simulando Pessoas na Fila de Espera (Para o Painel)
-- João (Clínico Geral)
INSERT INTO pacientes_filas (id_paciente, id_fila) VALUES 
((SELECT id FROM pacientes WHERE cpf='123.456.789-00'), 10);

-- Maria (Vacinação)
INSERT INTO pacientes_filas (id_paciente, id_fila) VALUES 
((SELECT id FROM pacientes WHERE cpf='111.222.333-44'), 11);

-- José (Prioritária)
INSERT INTO pacientes_filas (id_paciente, id_fila) VALUES 
((SELECT id FROM pacientes WHERE cpf='555.666.777-88'), 13);

-- 6. Histórico de Atendimentos
INSERT INTO atendimentos (id_atendimento, data_hora, descricao, diagnostico, status, id_paciente, id_medico, id_posto) VALUES 
(1, '2025-10-20 10:30:00', 'Consulta para dor de cabeça e febre', 'Virose leve', 'Finalizado', 
 (SELECT id FROM pacientes WHERE cpf='123.456.789-00'), 1, 1);

-- 7. Serviços do Dashboard
INSERT INTO servicos (titulo, icone_class, ordem, link_destino) VALUES 
('AGENDAMENTO DE CONSULTAS', 'fas fa-calendar-check', 1, 'agendamento.php'),
('CARTEIRA DE VACINAÇÃO', 'fas fa-syringe', 2, 'vacinas.php'),
('FARMÁCIA POPULAR', 'fas fa-pills', 3, 'farmacia.php'),
('RESULTADOS DE EXAMES', 'fas fa-file-medical-alt', 4, 'exames.php'),
('ATENDIMENTO DOMICILIAR', 'fas fa-user-nurse', 5, 'domiciliar.php'),
('MEU CARTÃO SUS', 'fas fa-id-card-alt', 6, 'cartao.php'),
('OUVIDORIA', 'fas fa-bullhorn', 7, 'ouvidoria.php'),
('ENCONTRAR POSTO', 'fas fa-map-marked-alt', 8, 'mapa.php');

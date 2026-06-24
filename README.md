# 🏥 Sistema de Agendamento - Posto de Saúde Digital

![Status do Projeto](https://img.shields.io/badge/Status-Concluído-brightgreen)
![PHP](https://img.shields.io/badge/Backend-PHP%208%2B-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Tests](https://img.shields.io/badge/Tests-Selenium-yellow)

Este é um projeto de **Engenharia de Software** e **Experiência do Usuário** que simula um sistema completo de gestão para Unidades Básicas de Saúde (UBS). O sistema permite que pacientes agendem consultas online (com interface similar ao Gov.br), recepcionistas gerenciem a fila de espera e médicos realizem o atendimento clínico.

O projeto foi desenvolvido seguindo rigorosamente o padrão de arquitetura **MVC (Model-View-Controller)**.

---

## 🚀 Funcionalidades

### 🧑‍🦱 Módulo do Paciente
* **Login Estilo Gov.br:** Autenticação simulada com validação de formato de CPF e senha.
* **Dashboard:** Visualização rápida de campanhas de saúde (Ex: Vacinação, Dengue) e serviços disponíveis.
* **Agendamento Online:** Permite ao usuário escolher data, período (manhã/tarde) e especialidade.
* **Histórico:** Visualização de agendamentos anteriores e opção de cancelamento.
* **Painel de Fila:** Acompanhamento em tempo real da sua posição na fila de espera (após o check-in).

### 👩‍💼 Módulo da Recepção
* **Triagem:** Visualização de todos os pacientes agendados para o dia corrente.
* **Check-in:** Confirmação da presença física do paciente no posto.
* **Gestão de Fila:** Capacidade de chamar pacientes para o consultório ou removê-los da fila em casos de desistência.

### 👨‍⚕️ Módulo do Médico
* **Agenda do Dia:** Lista filtrada de pacientes que já passaram pela triagem/check-in.
* **Atendimento:** Acesso aos dados básicos do paciente e motivo da consulta.
* **Prontuário:** Registro do diagnóstico, evolução clínica e finalização do atendimento.

### 📺 Painel Público (TV)
* Exibição em **tempo real** das senhas chamadas e lista de espera.
* Ordenação automática por horário de chegada.

---

## 🛠️ Tecnologias Utilizadas

| Camada | Tecnologias |
| :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP 8+ (PDO para segurança) |
| **Banco de Dados** | MySQL |
| **Testes** | Python + Selenium |

---

## 📂 Estrutura do Projeto (MVC)

O sistema está organizado separando a lógica de negócios, a visualização e o controle de requisições:

[Image of MVC architecture diagram web application]

```text
/posto_saude
│
├── /Model           # Regras de Negócio e Acesso ao Banco (DAO)
│   ├── ConnectionFactory.php
│   └── Classes.php  (Paciente, Médico, Atendimento, Fila)
│
├── /View            # Interfaces Gráficas (HTML/PHP Visual)
│   ├── /CSS         (Estilos customizados)
│   ├── /imagens     (Assets do sistema)
│   ├── login.php
│   ├── pagina_paciente.php
│   ├── agendamento.php
│   └── fila_atendimento.php
│
├── /Control         # Controladores (Recebem requisições e orquestram)
│   ├── login.php
│   ├── pagina_paciente.php
│   ├── agendamento.php
│   └── fila_atendimento.php
│
├── sistema_saude_completo.sql  # Script SQL de criação do Banco
└── testes_sistema.py           # Script Python de Testes Automatizados
```

## ⚙️ Instalação e Execução

### Pré-requisitos
* Servidor Web local (**XAMPP**, **WAMP** ou **Docker**).
* Navegador Web atualizado.

### Passo a Passo

1.  **Clone o repositório** para a pasta pública do seu servidor (ex: `C:\xampp\htdocs`):
    ```bash
    git clone [https://github.com/seu-usuario/posto-saude.git](https://github.com/seu-usuario/posto-saude.git)
    ```

2.  **Configuração do Banco de Dados**:
    * Abra o **phpMyAdmin** (geralmente em `http://localhost/phpmyadmin`).
    * Crie um banco de dados chamado `posto_saude`.
    * Importe o arquivo `sistema_saude.sql` localizado na raiz do projeto.

3.  **Verifique a Conexão**:
    * Certifique-se de que o arquivo `model/ConnectionFactory.php` está com as credenciais corretas do seu banco local (usuário `root`, senha vazia por padrão no XAMPP).

4.  **Acesse o Sistema**:
    * Abra o navegador e digite:
    ```
    http://localhost/posto_saude/control/login.php
    ```

---

## 🔑 Credenciais de Acesso (Dados de Teste)

O banco de dados já vem populado com usuários para teste de todos os perfis. A senha padrão para todos os **pacientes** listados abaixo é `123456`.

| Perfil | Login (Usuário / CPF / CRM) | Senha |
| :--- | :--- | :--- |
| **Paciente 1** | `123.456.789-00` | `123456` |
| **Paciente 2** | `111.222.333-44` | `123456` |
| **Médico** | `SP123456` | `maria123` |
| **Recepcionista** | `Carla Mendes` | `carla@ubs` |

---

## 🧪 Testes Automatizados

Para executar os testes de sistema (Login automatizado de todos os perfis):

1.  **Instale o Python e as dependências:**
    ```bash
    pip install selenium webdriver-manager
    ```

2.  **Execute o script na raiz do projeto:**
    ```bash
    python testes_sistema.py
    ```

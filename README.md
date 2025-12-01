🏥 Sistema de Agendamento - Posto de Saúde DigitalEste é um projeto de Engenharia de Software que simula um sistema completo de gestão para Unidades Básicas de Saúde (UBS). O sistema permite que pacientes agendem consultas online (com interface similar ao Gov.br), recepcionistas gerenciem a fila de espera e médicos realizem o atendimento clínico.O projeto foi desenvolvido utilizando o padrão de arquitetura MVC (Model-View-Controller).🚀 Funcionalidades🧑‍🦱 Módulo do PacienteLogin Gov.br: Autenticação simulada com validação de CPF e senha.Dashboard: Visualização de campanhas de saúde (Vacinação, Dengue) e serviços disponíveis.Agendamento Online: Escolha de data, período (manhã/tarde) e tipo de atendimento.Histórico: Visualização de agendamentos passados e opção de cancelamento.Painel de Fila: Acompanhamento em tempo real da sua posição na fila.👩‍💼 Módulo da RecepçãoTriagem: Visualização dos pacientes agendados para o dia.Check-in: Confirmação da chegada do paciente no posto.Gestão de Fila: Chamada de pacientes para o consultório ou remoção da fila.👨‍⚕️ Módulo do MédicoAgenda do Dia: Visualização dos pacientes que já passaram pela triagem.Atendimento: Acesso aos dados do paciente e motivo da consulta.Prontuário: Registro do diagnóstico/evolução clínica e finalização do atendimento.📺 Painel Público (TV)Exibição em tempo real das senhas chamadas e pacientes em espera, com ordenação por prioridade e horário de chegada.🛠️ Tecnologias UtilizadasFrontend: HTML5, CSS3 (Tailwind CSS via CDN), JavaScript (Vanilla).Backend: PHP 8+ (PDO para conexão segura).Banco de Dados: MySQL / MariaDB.Testes: Selenium (Python) para testes automatizados de sistema.📂 Estrutura do Projeto (MVC)O projeto está organizado nas seguintes camadas:/posto_saude
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
├── /Control         # Controladores (Recebem requisições)
│   ├── login.php
│   ├── pagina_paciente.php
│   ├── agendamento.php
│   └── fila_atendimento.php
│
├── sistema_saude_completo.sql  # Script de criação do Banco
└── testes_sistema.py           # Script de Testes Automatizados

⚙️ Instalação e ExecuçãoPré-requisitosServidor Web local (XAMPP, WAMP ou Docker).Navegador Web atualizado.Passo a PassoClone o repositório para a pasta pública do seu servidor (ex: C:\xampp\htdocs):git clone [https://github.com/seu-usuario/posto-saude.git](https://github.com/seu-usuario/posto-saude.git)

Configuração do Banco de Dados:Abra o phpMyAdmin (geralmente http://localhost/phpmyadmin).Crie um banco de dados chamado posto_saude.Importe o arquivo sistema_saude_completo.sql localizado na raiz do projeto.Verifique a Conexão:Certifique-se de que o arquivo Model/ConnectionFactory.php está com as credenciais corretas do seu banco local (usuário root, senha vazia por padrão no XAMPP).Acesse o Sistema:Abra o navegador e digite:http://localhost/posto_saude/Control/login.php🔑 Credenciais de Acesso (Dados de Teste)O banco de dados já vem populado com usuários para teste de todos os perfis. A senha padrão para todos os pacientes listados abaixo é 123456.PerfilLogin (Usuário/CPF/CRM)SenhaPaciente 1123.456.789-00123456Paciente 2111.222.333-44123456MédicoSP123456maria123RecepcionistaCarla Mendescarla@ubs🧪 Testes AutomatizadosPara executar os testes de sistema (Login de todos os perfis):Instale o Python e as dependências:pip install selenium webdriver-manager

Execute o script na raiz do projeto:python testes_sistema.py

📝 LicençaEste projeto foi desenvolvido para fins acadêmicos na disciplina de Engenharia de Software.

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
import time

# --- CONFIGURAÇÕES GERAIS ---
# Ajuste o caminho da pasta se necessário (ex: /posto_saude/Control/...)
BASE_URL = "http://localhost/posto_saude/Control/login.php"

# URLs de Sucesso Esperadas
URL_PACIENTE = "http://localhost/posto_saude/Control/pagina_paciente.php"
URL_EQUIPE   = "http://localhost/posto_saude/Control/fila_atendimento.php"

# Credenciais do Banco de Dados (Seeds)
# Paciente
CPF_PACIENTE = "123.456.789-00"
SENHA_PACIENTE = "123456"

# Médico
CRM_MEDICO = "SP123456"
SENHA_MEDICO = "maria123"

# Recepcionista
USER_RECEP = "Carla Mendes"
SENHA_RECEP = "carla@ubs"

def iniciar_driver():
    """Inicia o navegador Chrome."""
    service = ChromeService(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service)
    driver.maximize_window()
    driver.implicitly_wait(5) # Espera até 5s para encontrar elementos
    return driver

def realizar_login(driver, usuario, senha, tipo_teste):
    """Função genérica para preencher o formulário de login."""
    print(f"\n--- Iniciando Teste: Login de {tipo_teste} ---")
    
    driver.get(BASE_URL)
    print(f"Acessou: {BASE_URL}")

    # 1. Preencher Usuário (name='login')
    campo_login = driver.find_element(By.NAME, "login")
    campo_login.clear()
    campo_login.send_keys(usuario)
    print(f" -> Usuário preenchido: {usuario}")

    # 2. Preencher Senha (name='senha')
    campo_senha = driver.find_element(By.NAME, "senha")
    campo_senha.clear()
    campo_senha.send_keys(senha)
    print(" -> Senha preenchida.")

    # 3. Clicar em Entrar
    # Busca o botão pelo texto ou pela classe CSS
    botao = driver.find_element(By.XPATH, "//button[contains(text(), 'Entrar')]")
    botao.click()
    print(" -> Botão 'Entrar' clicado.")

    time.sleep(2) # Pausa breve para o redirecionamento acontecer

def teste_paciente():
    driver = iniciar_driver()
    try:
        realizar_login(driver, CPF_PACIENTE, SENHA_PACIENTE, "Paciente")
        
        # Validação
        if URL_PACIENTE in driver.current_url:
            print("✅ SUCESSO: Redirecionado para Área do Paciente.")
        else:
            print(f"❌ FALHA: URL atual ({driver.current_url}) não corresponde ao esperado.")
            
    except Exception as e:
        print(f"❌ ERRO TÉCNICO: {e}")
    finally:
        driver.quit()

def teste_medico():
    driver = iniciar_driver()
    try:
        realizar_login(driver, CRM_MEDICO, SENHA_MEDICO, "Médico")
        
        # Validação
        if URL_EQUIPE in driver.current_url:
            print("✅ SUCESSO: Redirecionado para Fila de Atendimento (Equipe).")
            
            # Validação Extra: Verificar se aparece a aba "Meu Consultório"
            if "Meu Consultório" in driver.page_source:
                print("✅ SUCESSO EXTRA: Aba 'Meu Consultório' visível.")
            else:
                print("⚠️ ALERTA: Médico logou, mas não viu a aba exclusiva.")
        else:
            print(f"❌ FALHA: URL atual ({driver.current_url}) incorreta.")

    except Exception as e:
        print(f"❌ ERRO TÉCNICO: {e}")
    finally:
        driver.quit()

def teste_recepcionista():
    driver = iniciar_driver()
    try:
        realizar_login(driver, USER_RECEP, SENHA_RECEP, "Recepcionista")
        
        # Validação
        if URL_EQUIPE in driver.current_url:
            print("✅ SUCESSO: Redirecionado para Fila de Atendimento.")
            
            # Validação Negativa: Recepcionista NÃO pode ver "Meu Consultório"
            if "Meu Consultório" not in driver.page_source:
                print("✅ SUCESSO EXTRA: Acesso restrito respeitado (Sem aba médica).")
            else:
                print("❌ FALHA DE SEGURANÇA: Recepcionista está vendo aba médica!")
        else:
            print(f"❌ FALHA: URL atual incorreta.")

    except Exception as e:
        print(f"❌ ERRO TÉCNICO: {e}")
    finally:
        driver.quit()

def teste_login_invalido():
    driver = iniciar_driver()
    try:
        realizar_login(driver, "000.000.000-00", "senhaerrada", "Usuário Inválido")
        
        # Validação: Deve continuar na página de login
        if "login.php" in driver.current_url:
            print("✅ SUCESSO: Sistema bloqueou acesso inválido.")
            
            # Verifica se apareceu mensagem de erro
            if "Dados inválidos" in driver.page_source or "Verifique" in driver.page_source:
                print("✅ SUCESSO EXTRA: Mensagem de erro exibida corretamente.")
        else:
            print("❌ FALHA: Sistema permitiu login ou redirecionou incorretamente.")

    except Exception as e:
        print(f"❌ ERRO TÉCNICO: {e}")
    finally:
        driver.quit()

# --- EXECUÇÃO DOS TESTES ---
if __name__ == "__main__":
    print("=== INICIANDO BATERIA DE TESTES DO POSTO DE SAÚDE ===")
    teste_paciente()
    teste_medico()
    teste_recepcionista()
    teste_login_invalido()
    print("\n=== TESTES FINALIZADOS ===")
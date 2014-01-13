<?php

require_once APPLICATION_PATH . '/../library/PagSeguroLibrary/PagSeguroLibrary.php';

class UsuariosController extends Application_Controller {

    const URL_ATIVAR = "http://newfinances.com.br/usuarios/ativar-usuario/id_usuario/";
    //const URL_ATIVAR = "http://localhost/newfinances/public/usuarios/ativar-usuario/id_usuario/";
    
    public $_formUsuariosLogin;
    public $_formNovoUsuario;
    public $_formPlanoUsuario;
    
    public $_formUsuariosRecuperarSenha;
    
    const PLANO_BASICO = 8;
    const VALOR_PLANO_BASICO = 9;

    public function init() {
        
        parent::init();
        
        $this->_formUsuariosLogin = new Form_Usuarios_Login();
        $this->_formNovoUsuario = new Form_Usuarios_NovoUsuario();
        $this->_formPlanoUsuario = new Form_Usuarios_PlanoUsuario();
        
        $this->_formUsuariosRecuperarSenha = new Form_Usuarios_RecuperarSenha();
        
        $this->view->messages = Controller_Helper_Messeges::getMesseges();
    }

    public function indexAction() {
        // action body
    }

    public function loginAction()
    {
     
        // desabilitando o layout
        $this->_helper->layout->setLayout("login");
        
        $this->_formUsuariosLogin = new Form_Usuarios_Login();
        $this->view->formUsuariosLogin = $this->_formUsuariosLogin;
        
        if ($this->getRequest()->isPost()) {
            $dadosUsuariosLogin = $this->getRequest()->getPost();
            if ($this->_formUsuariosLogin->isValid($dadosUsuariosLogin)) {
                $dadosUsuariosLogin = $this->_formUsuariosLogin->getValues();                
                
                $ZendAuth = Zend_Auth::getInstance();                
                $adapter = $this->_modelUsuario->login($dadosUsuariosLogin);
                $usuarioRow = $this->_modelUsuario->getDadosUsuario($dadosUsuariosLogin['email_usuario']);                
                
                $result = $ZendAuth->authenticate($adapter); 

                if ($result->isValid()) {
                    $ZendAuth->getStorage()->write($usuarioRow);   
                    
                    // gravando o log
                    $dadosInsertLog['id_usuario'] = $usuarioRow->id_usuario;
                    $modelUsuarioLogin = new Model_UsuarioLogin();
                    $modelUsuarioLogin->insert($dadosInsertLog);
                    
                    $this->_redirect("index/");
                } else {                    
                    $this->_helper->FlashMessenger->addMessage(array(
                            "Login e/ou senha inválidos!",
                            "error"
                        )
                    );                                        
                }                                
                
            }
        }
        
    }
    
    public function logoutAction() {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        
        $this->_redirect("site/index/index");
        
    }
    
    /**
     * novo usuario
     */
    public function novoUsuarioAction() {
        
        $this->_helper->layout->setLayout("site");
        
        $this->_formNovoUsuario->politica->getDecorator('Label')->setOption('escape',false);
        
        $this->view->formNovoUsuario = $this->_formNovoUsuario;
        
        if ($this->_request->isPost()) {
            $dadosNovoUsuario = $this->_request->getPost();
            if ($this->_formNovoUsuario->isValid($dadosNovoUsuario)) {
                $dadosNovoUsuario = $this->_formNovoUsuario->getValues();
                
                // valida cpf 
                if ($this->validaCPF($dadosNovoUsuario['cpf_usuario'])) {                    
                    // verifica se já existe este usuário 
                    $verificaUsuario = $this->_modelUsuario->fetchRow("
                        cpf_usuario = '{$dadosNovoUsuario['cpf_usuario']}'
                    ");

                    if ($verificaUsuario == null) {                    
                        // verifica se o usuario preencheu a politica de privacidade
                        if ($dadosNovoUsuario['politica'] == 1) {                    
                            // verifica se a senha de confirmacao confere
                            if ($dadosNovoUsuario['senha_usuario'] == $dadosNovoUsuario['confirma_senha']) {

                                // para a autenticacao
                                $email = $dadosNovoUsuario['email_usuario'];
                                $senha = $dadosNovoUsuario['senha_usuario'];

                                $dadosNovoUsuario['data_cadastro'] = Controller_Helper_Date::getDatetimeNowDb();
                                $dadosNovoUsuario['data_alteracao'] = Controller_Helper_Date::getDatetimeNowDb();
                                $dadosNovoUsuario['data_nascimento'] = Controller_Helper_Date::getDateDb($dadosNovoUsuario['data_nascimento']);
                                $dadosNovoUsuario['ativo_usuario'] = 0;
                                $dadosNovoUsuario['senha_usuario'] = md5($dadosNovoUsuario['senha_usuario']);

                                unset($dadosNovoUsuario['confirma_senha']);

                                try {
                                    $this->_modelUsuario->insert($dadosNovoUsuario);
                                    $last_id = $this->_modelUsuario->lastInsertId();         

                                    // insere o novo usuario no plano basico
                                    $planoBasico['id_usuario'] = $last_id;
                                    $planoBasico['id_plano'] = self::PLANO_BASICO;
                                    $planoBasico['data_aderido'] = Controller_Helper_Date::getDatetimeNowDb();
                                    $planoBasico['data_encerramento'] = Controller_Helper_Date::getDataEncerramentoPlano($planoBasico['data_aderido'], 8);
                                    $planoBasico['ativo_plano'] = 1;
                                    $planoBasico['id_plano_valor'] = self::VALOR_PLANO_BASICO;

                                    $this->_modelUsuarioPlano->insert($planoBasico);

                                    try {
                                        // envia o email para o novo usuario                                
                                        $mail = new Zend_Mail('utf-8');

                                        $message = "
                                            <p>Olá {$dadosNovoUsuario['nome_completo']}</p>
                                            <p>Seu cadastro foi realizado com sucesso! Seja Bem vindo ao NewFinances</p>
                                            <p>Acesse o link abaixo para ativar sua conta: </p>
                                            <p><a href='" . self::URL_ATIVAR . "{$last_id}'>Ativar Minha Conta</a></p>
                                        ";
                                        
                                        $mail->setBodyHtml($message);
                                        $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                                        $mail->addTo($dadosNovoUsuario['email_usuario']);
                                        $mail->setSubject('Seja Bem Vindo');

                                        $mail->send(Zend_Registry::get('mail_transport'));

                                        // envia o email para o gestor do sistema
                                        $mail = new Zend_Mail('utf-8');

                                        $message = "
                                            <p>Novo Usuário Cadastrado</p>
                                            <p>Nome: {$dadosNovoUsuario['nome_completo']}</p>
                                            <p>E-mail: {$dadosNovoUsuario['email_usuario']}</p>
                                            <p>Cidade: {$dadosNovoUsuario['cidade']}</p>                                            
                                        ";
                                        
                                        $mail->setBodyHtml($message);
                                        $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');                                                                                
                                        $mail->setSubject('Novo Usuário');
                                        $mail->addTo("nandorodpires@gmail.com");
                                        $mail->send(Zend_Registry::get('mail_transport'));
                                        
                                        // redireciona o usuario para a mensagem de ativar a conta
                                        $this->_redirect("usuarios/ativar");
                                        
                                        
                                    } catch (Exception $error) {
                                        $messeges = array(
                                            array(                                         
                                                "alert" => "Houve um problema ao mandar o e-mail: {$error->getMessage()}"
                                            )
                                        );

                                        $this->view->messages = $messeges; 
                                    }
                                    
                                } catch (Zend_Exception $error) {

                                    if ($error->getCode() == 1062) {
                                        $messeges = array(
                                            array(                                         
                                                "error" => "Este e-mail já está cadastrado!"
                                            )
                                        );
                                    }

                                    $this->view->messages = $messeges; 

                                }

                            } else {
                                $this->_formNovoUsuario->populate($dadosNovoUsuario);
                                $messeges = array(
                                    array( 
                                        "error" => "A senha digitada não confere com a confirmação da mesma."                          
                                    )
                                );
                                $this->view->messages = $messeges;                                       
                            }
                        } else {
                            $this->_formNovoUsuario->populate($dadosNovoUsuario);
                            $messeges = array(
                                array( 
                                    "error" => "Você deve ler e concordar com a política de privacidade para continuar o cadastro."                          
                                )
                            );
                            $this->view->messages = $messeges;                                       
                        }
                    } else {
                        $this->_formNovoUsuario->populate($dadosNovoUsuario);
                        $messages = array(
                            array(
                                'error' => "Já exite um usuário com este CPF cadastrado"
                            )
                        );
                        $this->view->messages = $messages;                    
                    }
                } else {
                    $this->_formNovoUsuario->populate($dadosNovoUsuario);
                    $messages = array(
                        array(
                            'error' => "CPF inválido"
                        )
                    );
                    $this->view->messages = $messages;
                }
            }
        }
    }
    
    public function ativarAction() {
        $this->_helper->layout->setLayout("site");
    }

    public function ativarUsuarioAction() {
    
        // desabilita layout e view
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    
        // recupera o id do usuario
        $id_usuario = $this->_getParam("id_usuario");
    
        // ativa o usuario
        $dadosAtivaUsuario['ativo_usuario'] = 1;
        $whereAtivaUsuario = "id_usuario = " . $id_usuario;
        $this->_modelUsuario->update($dadosAtivaUsuario, $whereAtivaUsuario);
        
        // busca dados do usuario
        $dadosUsuario = $this->_modelUsuario->getUsuario($id_usuario);
        $usuarioRow = $this->_modelUsuario->getDadosUsuario($dadosUsuario->email_usuario);                
                        
        // autentica usuario
        $ZendAuth = Zend_Auth::getInstance();                
        $ZendAuth->getStorage()->write($usuarioRow);   
        
        // envia o email        
        $mail = new Zend_Mail('utf-8');

        $message = "
            <p>Olá {$dadosUsuario->nome_completo}</p>
            <p>Seu cadastro foi ativado com sucesso!</p>
            <p>Obrigado por fazer parte da equipe NewFinances</p>            
        ";

        $mail->setBodyHtml($message);
        $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
        $mail->addTo($dadosUsuario->email_usuario);
        //$mail->addTo('tiago@realter.com.br');
        //$mail->setReplyTo('email@portal.redemorar.com.br');
        $mail->setSubject('Cadastro ativado');

        $mail->send(Zend_Registry::get('mail_transport'));
        
        // gravando o log
        $dadosInsertLog['id_usuario'] = $dadosUsuario->id_usuario;
        $modelUsuarioLogin = new Model_UsuarioLogin();
        $modelUsuarioLogin->insert($dadosInsertLog);

        $this->_redirect("/planos/plano-usuario");
        
    }
    
    /**
     * valida CPF usuario (Ajax)
     */
    public function validaCPF($cpf) {
        
        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara        
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
                
        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
            return false;
        }        
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {   

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
        
    }

    /**
     * mostra os dados do usuario caso ele queria alterar
     */
    public function meusDadosAction() {
        
        $this->view->messages = Controller_Helper_Messeges::getMesseges();
        
        $id_usuario = $this->_session->id_usuario;
        
        // busca os dados do Usuario
        $dadosUsuario = $this->_modelUsuario->getUsuario($id_usuario)->toArray();
        
        if ($dadosUsuario['data_nascimento']) {
            $dadosUsuario['data_nascimento'] = View_Helper_Date::getDataView($dadosUsuario['data_nascimento']);
        }
        
        // populando o form de usuario
        $this->_formNovoUsuario->populate($dadosUsuario);
        $this->_formNovoUsuario->removeElement("politica");
        $this->_formNovoUsuario->removeElement("senha_usuario");
        $this->_formNovoUsuario->removeElement("confirma_senha");
        
        $this->_formNovoUsuario->cpf_usuario->setAttrib('readonly', true);
        
        /*
        $modelCidade = new Model_Cidade();
        $cidade = $modelCidade->getCidade($dadosUsuario['id_cidade']);        
        $multiOptions[$cidade->id_cidade] = $cidade->descricao_cidade;
        $this->_formNovoUsuario->id_cidade->setMultiOptions($multiOptions);        
        $this->_formNovoUsuario->id_cidade->setValue($dadosUsuario['id_cidade']);
        $this->_formNovoUsuario->id_cidade->setAttrib('disabled', null); 
        */
        
        $this->_formNovoUsuario->submit->setLabel("Alterar");
        $this->_formNovoUsuario->getElement('email_usuario')->setAttrib('readonly', 'readonly');
        
        $this->view->formMeusDados = $this->_formNovoUsuario;
        
        if ($this->_request->isPost()) {
            $dadosUpdateUsuario = $this->_request->getPost();
            if ($this->_formNovoUsuario->isValid($dadosUpdateUsuario)) {
                $dadosUpdateUsuario = $this->_formNovoUsuario->getValues();
                
                $dadosUpdateUsuario['data_nascimento'] = Controller_Helper_Date::getDateDb($dadosUpdateUsuario['data_nascimento']);                
                $whereUpdate = "id_usuario = " . $id_usuario;
                
                try {
                    $this->_modelUsuario->update($dadosUpdateUsuario, $whereUpdate);
                    
                    $messeges = array(
                        array(                                         
                            "success" => "Dados alterado com sucesso!"
                        )
                    );

                    $this->view->messages = $messeges;
                    
                    $this->_redirect("usuarios/meus-dados");
                } catch (Zend_Exception $error) {
                    echo $error->getMessage();
                }
                            
                
            }
        }
        
        
    }
    
    /**
     * recuperar senha do usuario
     */
    public function recuperarSenhaAction() {
        $this->_helper->layout->setLayout("login");
        
        $this->view->formUsuariosRecuperarSenha = $this->_formUsuariosRecuperarSenha;
        
        if ($this->_request->isPost()) {
            $dadosRecuperarSenha = $this->_request->getPost();
            if ($this->_formUsuariosRecuperarSenha->isValid($dadosRecuperarSenha)) {
                $dadosRecuperarSenha = $this->_formUsuariosRecuperarSenha->getValues();
                
                // verificar se existe o email
                $dadosUsuario = $this->_modelUsuario->getDadosUsuario($dadosRecuperarSenha['email_usuario']);
                
                if ($dadosUsuario) {                
                    // caso exista gera um novo codigo
                    $senha = Controller_Helper_Application::geraSenhaUsuario();                    
                    
                    // atualiza o codigo e a flag de alterar 
                    $dadosUpdateSenha['senha_usuario'] = md5($senha);
                    $whereUpdateSenha = "id_usuario = $dadosUsuario->id_usuario";
                    $this->_modelUsuario->update($dadosUpdateSenha, $whereUpdateSenha);                    
                    
                    // envia o codigo pro e-mail
                    $mail = new Zend_Mail('utf-8');

                    $bodyHtml = "
                        <p>Sua senha foi alterada:</p>
                        <p>Senha: {$senha}</p>
                    ";
                    
                    $mail->setBodyHtml($bodyHtml);
                    $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo("nandorodpires@gmail.com");
                    //$mail->addTo('tiago@realter.com.br');
                    //$mail->setReplyTo('email@portal.redemorar.com.br');
                    $mail->setSubject('Nova Senha');

                    $mail->send(Zend_Registry::get('mail_transport'));
                    
                } else {
                    die('false');
                }
                
            }
        }
        
    }

}




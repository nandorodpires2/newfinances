<?php

require_once APPLICATION_PATH . '/../library/PagSeguroLibrary/PagSeguroLibrary.php';

class UsuariosController extends Zend_Controller_Action {

    protected $_session;
    
    protected $_modelUsuario;
    protected $_modelPlano;
    protected $_modelUsuarioPlano;
    protected $_modelPlanoValor;

    protected $_formUsuariosLogin;
    protected $_formNovoUsuario;
    protected $_formPlanoUsuario;
    
    const PLANO_BASICO = 8;
    const VALOR_PLANO_BASICO = 9;

    public function init() {
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();        
        
        $this->_modelUsuario = new Model_Usuario();        
        $this->_modelPlano = new Model_Plano();
        $this->_modelUsuarioPlano = new Model_UsuarioPlano();
        $this->_modelPlanoValor = new Model_PlanoValor();
        
        $this->_formUsuariosLogin = new Form_Usuarios_Login();
        $this->_formNovoUsuario = new Form_Usuarios_NovoUsuario();
        $this->_formPlanoUsuario = new Form_Usuarios_PlanoUsuario();
        
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
        
        $this->_redirect("usuarios/login");
        
    }
    
    /**
     * novo usuario
     */
    public function novoUsuarioAction() {
        
        $this->_helper->layout->setLayout("login");
        
        $this->_formNovoUsuario->politica->getDecorator('Label')->setOption('escape',false);
        
        $this->view->formNovoUsuario = $this->_formNovoUsuario;
        
        if ($this->_request->isPost()) {
            $dadosNovoUsuario = $this->_request->getPost();
            if ($this->_formNovoUsuario->isValid($dadosNovoUsuario)) {
                $dadosNovoUsuario = $this->_formNovoUsuario->getValues();
                
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
                            $dadosNovoUsuario['data_nascimento'] = Controller_Helper_Date::getDatetimeNowDb();
                            $dadosNovoUsuario['ativo_usuario'] = 1;
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
                                
                                // envia o email para o novo usuario                                
                                $mail = new Zend_Mail('utf-8');

                                $mail->setBodyHtml("Seu cadastro foi realizado com sucesso! Seja Bem vindo ao NewFinances");
                                $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
                                $mail->addTo("nandorodpires@gmail.com");
                                //$mail->addTo('tiago@realter.com.br');
                                //$mail->setReplyTo('email@portal.redemorar.com.br');
                                $mail->setSubject('Seja Bem Vindo');

                                $mail->send(Zend_Registry::get('mail_transport'));
                                
                                
                                // autenticando o novo usuario
                                $ZendAuth = Zend_Auth::getInstance();                
                                $adapter = $this->_modelUsuario->validaUsuario($email, md5($senha));
                                $usuarioRow = $this->_modelUsuario->getDadosUsuario($email);

                                if ($adapter) {
                                    $ZendAuth->getStorage()->write($usuarioRow);                    
                                    $this->_redirect("planos/plano-usuario/id/{$last_id}");
                                } else {
                                    echo "erro ao autenticar novo usuario";
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
            }
        }
    }
    
    /**
     * mostra os dados do usuario caso ele queria alterar
     */
    public function meusDadosAction() {
        
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
        
        $this->view->formMeusDados = $this->_formNovoUsuario;
        
        if ($this->_request->isPost()) {
            $dadosUpdateUsuario = $this->_request->getPost();
            if ($this->_formNovoUsuario->isValid($dadosUpdateUsuario)) {
                $dadosUpdateUsuario = $this->_formNovoUsuario->getValues();
                
                $dadosUpdateUsuario['data_nascimento'] = Controller_Helper_Date::getDateDb($dadosUpdateUsuario['data_nascimento']);                
                $whereUpdate = "id_usuario = " . $id_usuario;
                
                try {
                    $this->_modelUsuario->update($dadosUpdateUsuario, $whereUpdate);
                    $this->_redirect("usuarios/meus-dados");
                } catch (Zend_Exception $error) {
                    echo $error->getMessage();
                }
                            
                
            }
        }
        
        
    }

}



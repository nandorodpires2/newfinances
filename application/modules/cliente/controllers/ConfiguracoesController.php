<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfiguracoesController
 *
 * @author Realter
 */
class ConfiguracoesController extends Zend_Controller_Action {

    protected $_session;
            
    protected $_modelCartao;
    protected $_modelConta;
    protected $_modelMovimentacao;

    protected $_formConfiguracoesCartao;
    protected $_formConfiguracoesConta;

    public function init() {
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();
        
        $this->_modelCartao = new Model_Cartao();
        $this->_modelConta = new Model_Conta();
        $this->_modelMovimentacao = new Model_Movimentacao();
        
        $this->_formConfiguracoesCartao = new Form_Configuracoes_Cartao();
        $this->_formConfiguracoesConta = new Form_Configuracoes_Conta();
        
        $this->view->messages = Controller_Helper_Messeges::getMesseges();
        
    }
    
    public function indexAction() {
        
        $cartoes = $this->_modelCartao->getCartoesUsuario($this->_session->id_usuario);
        $this->view->cartoes = $cartoes;
        
        $contas = $this->_modelConta->getContasUsuario($this->_session->id_usuario);
        $this->view->contas = $contas;        
        
    }
    
    public function novoCartaoAction() {
        
        $this->view->formConfiguracoesCartao = $this->_formConfiguracoesCartao;
        
        if ($this->_request->isPost()) {
            $dadosCartao = $this->_request->getPost();
            if ($this->_formConfiguracoesCartao->isValid($dadosCartao)) {
                $dadosCartao = $this->_formConfiguracoesCartao->getValues();
                               
                $dadosCartao['ativo_cartao'] = 1;
                $dadosCartao['limite_cartao'] = View_Helper_Currency::setCurrencyDb($dadosCartao['limite_cartao'], 'positivo');
                
                try {
                    $this->_modelCartao->insert($dadosCartao);
                    $this->_redirect("configuracoes/");
                } catch (Zend_Db_Exception $db_exception) {
                    echo $db_exception->getMessage();
                }
            }
        }
        
    }
    
    public function novaContaAction() {
        
        
        $messages = $this->_helper->FlashMessenger->getMessages();
        $this->view->messages = $messages;
        
        $this->view->formConfoguracoesConta = $this->_formConfiguracoesConta;
        
        if ($this->_request->isPost()) {            
            $dadosConta = $this->_request->getPost();
            if ($this->_formConfiguracoesConta->isValid($dadosConta)) {
                $dadosConta = $this->_formConfiguracoesConta->getValues();
                
                if ($dadosConta['id_banco'] == '') {
                    $dadosConta['id_banco'] = null;
                }
                
                $dadosConta['ativo_conta'] = 1;
                $dadosConta['data_inclusao'] = Controller_Helper_Date::getDatetimeNowDb();
                $dadosConta['saldo_inicial'] = View_Helper_Currency::setCurrencyDb($dadosConta['saldo_inicial'], 'positivo');
                
                try {                
                    $this->_modelConta->insert($dadosConta);                                        
                    $this->_redirect("configuracoes/");
                } catch (Zend_Exception $erro) {
                    echo $erro->getMessage();
                }   
                
            }
        }
        
    }
    
    /**
     * editar conta
     */
    public function editarContaAction() {
        
        $id_conta = (int)$this->_request->getParam("id_conta");
        $dadosConta = $this->_modelConta->fetchRow("id_conta = {$id_conta} and id_usuario = {$this->_session->id_usuario}")->toArray();
        
        $this->_formConfiguracoesConta->populate($dadosConta);
        // alterando a label do submit
        $this->_formConfiguracoesConta->submit->setLabel("Editar");
        $this->view->formConta = $this->_formConfiguracoesConta;
        
        // alterando a conta
        if ($this->_request->isPost()) {
            $dadosUpdateConta = $this->_request->getPost();
            if ($this->_formConfiguracoesConta->isValid($dadosUpdateConta)) {
                $dadosUpdateConta = $this->_formConfiguracoesConta->getValues();
                
                if (empty($dadosUpdateConta['id_banco'])) {
                    $dadosUpdateConta['id_banco'] = null;
                }
                
                //Zend_Debug::dump($dadosUpdateConta); die();
                
                $where_update = "id_conta = {$id_conta} and id_usuario = {$this->_session->id_usuario}";
                
                try {
                    $this->_modelConta->update($dadosUpdateConta, $where_update);
                    $this->_redirect("configuracoes/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
            }
        }
        
    }
    
    /**
     * editar cartao
     */
    public function editarCartaoAction() {
        
        $id_cartao = (int)$this->_getParam("id_cartao");
        
        // buscando os dados do carta
        $dadosCartao = $this->_modelCartao->find($id_cartao)->current()->toArray();
        
        // formatando alguns dados
        $dadosCartao['limite_cartao'] = number_format($dadosCartao['limite_cartao'], 2, ',', '.');
        
        $this->_formConfiguracoesCartao->populate($dadosCartao);
        $this->view->formCartao = $this->_formConfiguracoesCartao;
        
        if ($this->_request->isPost()) {
            $dadosUpdateCartao = $this->_request->getPost();
            if ($this->_formConfiguracoesCartao->isValid($dadosUpdateCartao)) {
                $dadosUpdateCartao = $this->_formConfiguracoesCartao->getValues();
                
                $dadosUpdateCartao['limite_cartao'] = View_Helper_Currency::setCurrencyDb($dadosUpdateCartao['limite_cartao'], "postitivo");
                
                try {
                    $this->_modelCartao->update($dadosUpdateCartao, "id_cartao = {$id_cartao}");
                    Controller_Helper_Messeges::setMesseges(array(
                        "success" => "Cartão alterado com sucesso"
                    ));
                    $this->_redirect("configuracoes/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
        
    }
    
    /**
     * excluir conta
     */
    public function excluirContaAction() {
        
        // desabilitando a view
        $this->_helper->viewRenderer->setNoRender(true);
        
        // recuperando o id da conta
        $id_conta = (int)$this->_request->getParam("id_conta");
        
        // busca os dados da conta
        $dadosConta = $this->_modelConta->find($id_conta)->current();
        
        // verifica se a conta e do usuario logado
        if ($dadosConta->id_usuario === $this->_session->id_usuario) {
            
            // atualiza
            $dadosUpdate['ativo_conta'] = 0;
            $whereUpdate = "id_conta = " . $id_conta;
            
            try {
                $this->_modelConta->update($dadosUpdate, $whereUpdate);
                
                Controller_Helper_Messeges::setMesseges(array(
                    "success" => "Conta desativada com sucesso"
                ));
                $this->_redirect("configuracoes/");                
                
            } catch (Exception $error) {
                echo $error->getMessage();
            }
                    
        } else {
            $msm = array(                
                "error" => "Permissão negada"                
            );
            Controller_Helper_Messeges::setMesseges($msm);
            $this->_redirect("configuracoes/");
        }       
    }
    
    /**
     * excluir cartao
     */
    public function excluirCartaoAction() {
        
        // desabilitando a view
        $this->_helper->viewRenderer->setNoRender(true);
        
        // recuperando o id do cartao
        $id_cartao = $this->_getParam("id_cartao");
        
        // recuperando os dados do cartao
        $dadosCartao = $this->_modelCartao->find($id_cartao)->current();
        
        // verificando se o cartao e do usuario logado
        if ($dadosCartao->id_usuario === $this->_session->id_usuario) {
          
            $dadosUpdateCartao['ativo_cartao'] = 0;
            $whereUpdateCartao = "id_cartao = " . $id_cartao;
            
            try {
                $this->_modelCartao->update($dadosUpdateCartao, $whereUpdateCartao);
                Controller_Helper_Messeges::setMesseges(array(
                    "success" => "Cartão desativado com sucesso"
                ));
                $this->_redirect("configuracoes/");
            } catch (Exception $error) {
                echo $error->getMessage();
            }
                    
        } else {
            $msm = array(                
                "error" => "Permissão negada"                
            );
            Controller_Helper_Messeges::setMesseges($msm);
            $this->_redirect("configuracoes/");
        }  
        
    }
}

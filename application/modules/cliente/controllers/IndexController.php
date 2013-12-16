<?php

class IndexController extends Zend_Controller_Action
{

    protected $_session;

    protected $_modelMovimentacao;
    protected $_modelConta;
    protected $_modelMeta;
    protected $_modelCartao;
    protected $_modelCategoria;

    public function init() {
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();        
                
        $this->_modelMovimentacao = new Model_Movimentacao();
        $this->_modelConta = new Model_Conta();
        $this->_modelMeta = new Model_Meta();        
        $this->_modelCartao = new Model_Cartao();
        $this->_modelCategoria = new Model_Categoria();
        
        // verifica se tem pelo menos uma conta cadastrada
        if (!Controller_Helper_Application::hasConta()) {        
            Controller_Helper_Messeges::setMesseges(array('alert' => 'Cadastre uma conta'));
            $this->_redirect("configuracoes/nova-conta");
        }      
        
    }

    public function indexAction() {
     
        /**
         * saldo das metas
         */
        $metas = $this->_modelMeta->getGastosMetasUsuario($this->_session->id_usuario);
        $this->view->metas = $metas;
        $this->view->total_meta = $this->_modelMeta->getTotalMetaMes(
            $this->_session->id_usuario,
            (int)date('m'),
            (int)date('Y')
        );        
        $this->view->total_gastos = $this->_modelMeta->getTotalGastoMetas(
            $this->_session->id_usuario,
            (int)date('m'),
            (int)date('Y')
        );
        
        /**
         * fatura cartao
         */     
        // seleciona os cartoes do usuario
        $cartoes = $this->_modelCartao->fetchAll("id_usuario = {$this->_session->id_usuario}");
        $this->view->cartoes = $cartoes;
        
        /**
         * avisos
         */        
        // verifica se existe pendencias
        $pendencias = $this->_modelMovimentacao->getPendencias($this->_session->id_usuario);
        $this->view->pendencias = $pendencias;      
        
        // proximas 3 receitas
        $receitas = $this->_modelMovimentacao->getProximasReceitas($this->_session->id_usuario);
        $this->view->receitas = $receitas;
        
        // proximas 5 despesas
        $despesas = $this->_modelMovimentacao->getProximasDespesas($this->_session->id_usuario);
        $this->view->despesas = $despesas;
        
    }

}


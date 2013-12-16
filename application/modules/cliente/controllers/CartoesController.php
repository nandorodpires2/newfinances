<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartoesController
 *
 * @author Realter
 */
class CartoesController extends Zend_Controller_Action {

    protected $_session;

    protected $_modelMovimentacao;

    protected $_formCartoesPagamento;

    public function init() {
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();
        
        $this->_modelMovimentacao = new Model_Movimentacao();
                
        $this->_formCartoesPagamento = new Form_Cartoes_Pagamento();
        
        // enviando as mensagens para a view
        $this->view->messages = Controller_Helper_Messeges::getMesseges();
        
    }
    
    public function indexAction() {
        
    }

    /**
     * ver fatura
     */
    public function verFaturaAction() {
     
        $id_cartao = (int)$this->_getParam("id_cartao");
        $vencimento_fatura = $this->_getParam("vencimento");
                
    }
    
    /**
     * pagar fatura
     */
    public function pagarFaturaAction() {
        
        $id_cartao = (int)$this->_getParam("id_cartao");
        $vencimento_fatura = $this->_getParam("vencimento");        
        
    }
    
}


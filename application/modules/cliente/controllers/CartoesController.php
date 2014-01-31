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
class CartoesController extends Application_Controller {

    public function init() {
        
        parent::init();
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
        
        // busca as movimentacoes do cartao
        $lancamentosFatura = $this->_modelVwLancamentoCartao->getLancamentosFatura(
                $id_cartao,
                $vencimento_fatura,
                $this->_session->id_usuario
        );
        
        $this->view->lancamentosFatura = $lancamentosFatura; 
        // total da fatura
        $totalFatura = $this->_modelVwLancamentoCartao->getTotalFatura(                
                $id_cartao,
                $vencimento_fatura,
                $this->_session->id_usuario
        );
        $this->view->totalFatura = $totalFatura;
                
    }
    
    /**
     * pagar fatura
     */
    public function pagarFaturaAction() {
        
        $id_cartao = (int)$this->_getParam("id_cartao");
        $vencimento_fatura = $this->_getParam("vencimento");        
        
    }
    
}


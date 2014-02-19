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
        // caso exista um filtro de fatura de cartao de credito
        // seta o valor fatura, caso contrario, seta o valor vencimento
        $vencimento_fatura = $this->_request->isPost() ? $this->_getParam("fatura") : $this->_getParam("vencimento");
        
        // busca as faturas para filtro
        $faturasCartao = $this->_modelVwLancamentoCartao->getProximaFaturas($id_cartao, $this->_session->id_usuario);
        // criando um multiOption
        $multOptions = array();
        foreach ($faturasCartao as $fatura) {
            
            $vencimento_faturas = View_Helper_Date::getDataView($fatura->vencimento_fatura);
            //$valor_faturas = View_Helper_Currency::getCurrency($fatura->valor_fatura);
            
            $multOptions[$fatura->vencimento_fatura] = $vencimento_faturas;
        }
        $form = new Zend_Form();
        $form->setAttrib('id', 'formFaturas');
        $form->setMethod('post');        
        $form->addElement("select", "fatura", array(
            'label' => "Selecione a fatura: ",
            'multioptions' => $multOptions,
            'value' => $vencimento_fatura,
            'decorators' => array(
                'ViewHelper',
                'Description',
                'Errors',
                array(array('td' => 'HtmlTag'), array('tag' => 'td')),
                array('Label', array('tag' => 'td')),
            )
        ));
        $this->view->formFaturas = $form;
        
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


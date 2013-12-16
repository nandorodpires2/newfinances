<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application
 *
 * @author Realter
 */
class Plugin_Application extends Zend_Controller_Plugin_Abstract {
    
    protected $_auth;

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        $this->_auth = Zend_Auth::getInstance();
        
        if ($this->_auth->hasIdentity()) {
            $this->getSaldoAtualUsuario();
        }
        
    }
    
    protected function getSaldoAtualUsuario() {
        
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
        
        $modelConta = new Model_Conta();
        $saldoContas = $modelConta->getSaldoContas($this->_auth->getIdentity()->id_usuario);
        
        $saldo_total = 0;
        foreach ($saldoContas as $saldo) {
            $saldo_total += $saldo->saldo;
        }
        
        $view->saldo_contas = $saldo_total;
        
    }
    
}


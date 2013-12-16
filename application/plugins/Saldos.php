<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Saldos
 *
 * @author Realter
 */
class Plugin_Saldos extends Zend_Controller_Plugin_Abstract {
 
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        $auth = Zend_Auth::getInstance();
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
        
        if ($auth->hasIdentity()) {        
            /**
             * saldo das contas
             */
            $modelConta = new Model_Conta();
            $saldos = $modelConta->getSaldoContas($auth->getIdentity()->id_usuario);

            $view->saldos = $saldos;

            $saldo_total = 0;
            foreach ($saldos as $saldo) {
                $saldo_total += $saldo->saldo;
            }

            // saldo previsto
            
            $data_ini = '2013-12-01';
            $data_fim = '2013-12-31';
            
            $saldo_previsto = Controller_Helper_Movimentacao::saldoDiaPrevisto(
                $data_fim, 
                $auth->getIdentity()->id_usuario, 
                $data_ini
            );            
            
            $view->saldo_previsto = $saldo_previsto;
            $view->saldo_total = $saldo_total;
        
        }
        
    }
    
}

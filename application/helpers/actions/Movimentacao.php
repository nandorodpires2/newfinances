<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Movimentacao
 *
 * @author Realter
 */
class Controller_Helper_Movimentacao extends Zend_Controller_Action_Helper_Abstract {
 
    public static function getStatusMovimentacao($dataMovimentacao, $id_tipo_movimentacao = null) {
        
        if ($id_tipo_movimentacao == 3) {
            return 1;
        }
        
        $zendDate = new Zend_Date();
        $zendDateMovimentacao = new Zend_Date($dataMovimentacao);
        
        return ($zendDate->isLater($zendDateMovimentacao) ? 1 : 0); 
        
    }
    
    /**
     * saldo do dia previsto
     */
    public static function saldoDiaPrevisto($data_fim, $id_usuario, $data_ini = null) {
        
        if (!$data_ini) {
            $data_ini = "2013-01-01";
        }
        
        $modelVwMovimentacao = new Model_VwMovimentacao();        
        $saldo_previsto = $modelVwMovimentacao->getSaldoPrevisto($data_fim, $id_usuario, $data_ini);
        
        return $saldo_previsto;
    }    
    
    /**
     * saldo do dia realizado
     */
    public static function saldoDiaRealizado($data_fim, $id_usuario, $data_ini = null) {
        
        if (!$data_ini) {
            $data_ini = "2013-01-01";
        }
        
        $modelVwMovimentacao = new Model_VwMovimentacao();        
        $saldo_realizado = $modelVwMovimentacao->getSaldoRealizado($data_fim, $id_usuario, $data_ini);
        
        return $saldo_realizado;
    }
    
    
    
    /**
     * Busca o saldo anterior
     */
    public static function getSaldoAnterior($data_fim, $id_usuario, $data_ini = null) {
        
        if (!$data_ini) {
            $data_ini = "2013-01-01";
        }
        
        // retira um dia na data
        $zendDate = new Zend_Date($data_fim);
        $zendDate->subDay(1);
        $data_fim = $zendDate->toString("YYYY-MM-DD");
        
        $modelVwMovimentacao = new Model_VwMovimentacao();        
        $saldo_realizado = $modelVwMovimentacao->getSaldoRealizado($data_fim, $id_usuario, $data_ini);
        
        return $saldo_realizado;
        
    }
    
}

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Date
 *
 * @author Realter
 */
class Controller_Helper_Date extends Zend_Controller_Action_Helper_Abstract {

    public static function getDatetimeNowDb() {
        $zendDate = new Zend_Date();
        return $zendDate->toString('yyyy-MM-dd H:m:s');        
    }
    
    public static function getDateNowDb() {
        $zendDate = new Zend_Date();
        return $zendDate->toString('yyyy-MM-dd');        
    }
    
    public static function getDateDb($date) {
        $datePart = explode("/", $date);
        return $datePart[2] . '-' . $datePart[1] . '-' . $datePart[0];
    }
    
    public static function getDateViewComplete($date) {
        $zendDate = new Zend_Date($date);
        return $zendDate->get(Zend_Date::DATE_FULL);
    }
    
    public static function getDataEncerramentoPlano($data_aderido, $id_plano) {
        
        $modelPlano = new Model_Plano();
        $plano = $modelPlano->fetchRow("id_plano = {$id_plano} and ativo_plano = 1");
        
        // caso o plano nao tenha data de expiracao
        if (!$plano->tempo_plano) {
            return null;
        }
        
        $zendDate = new Zend_Date($data_aderido);        
        
        if ($plano->unidade_tempo_plano == 'ano') {
            $zendDate->addYear($plano->tempo_plano);
        } elseif ($plano->unidade_tempo_plano == 'meses') {
            $zendDate->addMonth($plano->tempo_plano);
        } elseif ($plano->unidade_tempo_plano == 'dias') {
            $zendDate->addDay($plano->tempo_plano);
        }                           
                
        return $zendDate->toString('yyyy-MM-dd HH:mm:ss');
        
    }
    
    public static function isLater($data) {
        
        $zendDate = new Zend_Date($data);
        
        if ($zendDate->isLater(Zend_Date::now())) {
            return true;
        }
        
        return false;
        
    }
    
}


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
class View_Helper_Date extends Zend_View_Helper_Abstract {

    /**
     * 
     * @param type $date
     * @return type
     */
    public static function getDataView($date) {
        $zendDate = new Zend_Date($date);
        
        return $zendDate->get(Zend_Date::DATE_MEDIUM);
    }
    
    public static function getDateCompleteView($date) {
        $zendDate = new Zend_Date($date);
        
        return $zendDate->get(Zend_Date::DATE_FULL);
    }

    /**
<<<<<<< .mine
     * 
     * @param type $date
     * @return type
     */
    public static function getDateTimeView($date) {
        $zendDate = new Zend_Date($date);
        
        return $zendDate->get(Zend_Date::DATETIME_MEDIUM);
    }
    
    /**
=======
     * retorna o nome do mes
     * @param type $mes
     */
    public static function getMonthName($mes) {
        $zendDate = new Zend_Date();
        $zendDate->setMonth($mes);
        
        return $zendDate->get(Zend_Date::MONTH_NAME);
    }

    /**
>>>>>>> .theirs
     * verifica se uma movimentacao nao paga esta atrasada
     */
    public static function isPending($movimentacao) {
        
        $zendDate = new Zend_Date($movimentacao->data_movimentacao);
        $ZendDateNow = new Zend_Date();
        
        if ($movimentacao->id_tipo_movimentacao != 3) {
            if ($movimentacao->realizado == 0) {
                if (!$zendDate->isLater($ZendDateNow->now()->subDay(1))) {
                    return true;
                }            
            }
        }
        
        return false;
        
    }
    
    /**
     * verifica se a data é a data de hoje
     */
    public static function isToday($data) {
        
        $zendDate = new Zend_Date($data);
        
        if ($zendDate->isToday()) {
            return true;
        }
        return false;
        
    }
    
}


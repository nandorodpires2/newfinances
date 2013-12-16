<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Currency
 *
 * @author Realter
 */
class View_Helper_Currency extends Zend_View_Helper_Abstract {

    public static function getCurrency($value) {
        $zendCurrency = new Zend_Currency();        
        return $zendCurrency->toCurrency($value);                
    }
    
    /**
     * 
     * caso tipo seja nulo retorna o valor negativo para despesas
     * senao verifica o tipo e retorna
     * 
     * @param type $value
     * @param type $tipo
     * @return type
     */
    public static function setCurrencyDb($value, $tipo = null) {
        
        if (!$tipo) {        
            $value = str_replace('.', '', $value);
            $value = (float)str_replace(',', '.', $value) * -1;        
        } else {
            $value = str_replace('.', '', $value);
            $value = (float)str_replace(',', '.', $value);        
        }
        
        return $value;
    }
    
    /**
     * set currency view edit values
     */
    public static function valueEditInput($value) {
        return number_format((string)$value, 2, ',', '.');
    }
    
}


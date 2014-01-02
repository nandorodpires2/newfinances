<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Planos
 *
 * @author Realter
 */
class View_Helper_Planos extends Zend_View_Helper_Abstract {

    /**
     * 
     * retorna o banner correspondente ao valor do plano
     * 
     * @param type $id_plano
     * @return string
     */
    public static function getClassBannerPlano($id_plano) {
    
        $class = "";
        switch ($id_plano) {
            case Application_Controller::PLANO_TRIMESTRAL:
                $class = "plano_trimestral";
                break;
            case Application_Controller::PLANO_SEMESTRAL:
                $class = "plano_semestral";
                break;
            case Application_Controller::PLANO_ANUAL:
                $class = "plano_anual";
                break;
            default:
                $class = "";
                break;
        }
        
        return $class;
        
    }
    
}


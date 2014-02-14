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
     * retorna a porcentagem de economia do plano
     */
    public static function getEconomiaPlano($dadosPlano) {
        
        if ($dadosPlano->tempo_plano == 1) {
            $dadosPlano->tempo_plano = 12;
        }
        
        $modelPlanoValor = new Model_PlanoValor();
        
        // buscar o valor do plano trimestral
        $planoTrimestral = $modelPlanoValor->getPlanoValorUsuario(3);
        
        // calcula o valor
        $valorPlano = ($planoTrimestral->valor_plano * $dadosPlano->tempo_plano) / $planoTrimestral->tempo_plano;
        
        // calcula a porcentagem
        $porcentagem = 100 - ($dadosPlano->valor_plano * 100) / $valorPlano;
        
        return ceil($porcentagem);
        
    }

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


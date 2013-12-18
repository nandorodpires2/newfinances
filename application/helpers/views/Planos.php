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
    public static function getBannerPlano($id_plano) {
    
        $zendView = new Zend_View();
        
        $img = "<img src=";
        
        $modelPlanoValor = new Model_PlanoValor;
        $planoValor = $modelPlanoValor->getPlanoValorUsuario($id_plano);        
        $banner = $planoValor->banner;
        
        $img .= $zendView->baseUrl('views/img/banners/' . $banner) . " width=100% />";
        
        return $img;
        
    }
    
}


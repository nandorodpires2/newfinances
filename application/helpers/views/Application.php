<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application
 *
 * @author Fernando Rodrigues
 */
class View_Helper_Application extends Zend_View_Helper_Abstract {

    public static function isGestor($id_usuario) {        
        $modelUsuarioPlano = new Model_UsuarioPlano();
        $plano = $modelUsuarioPlano->getPlanoAtual($id_usuario);
        
        return $plano->id_plano == Application_Controller::PLANO_GESTOR ? true : false;
    }
    
}


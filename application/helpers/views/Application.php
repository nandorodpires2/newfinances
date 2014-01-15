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

    public static function isGestor($id_usuario = null) {        
        
        if (!$id_usuario) {
            $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
        }
        
        $modelUsuarioPlano = new Model_UsuarioPlano();
        $plano = $modelUsuarioPlano->getPlanoAtual($id_usuario);
        
        return $plano->id_plano == Application_Controller::PLANO_GESTOR ? true : false;
    }    
    
    public static function hasAcl($resource) {
        
        if (Zend_Auth::getInstance()->hasIdentity()) {
        
            $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;

            if (View_Helper_Application::isGestor($id_usuario)) {
                return true;
            }

            $acl = new Zend_Acl();              
            return $acl->has($resource);
            
        }
    }
    
}


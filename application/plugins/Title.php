<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Title
 *
 * @author Realter
 */
class Plugin_Title extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        $modelFuncionalidade = new Model_Funcionalidade();
        $title = $modelFuncionalidade->fetchRow("
            module = '{$request->getModuleName()}'
            and controller = '{$request->getControllerName()}'
            and action = '{$request->getActionName()}'
            and ativo = 1
        ");
            
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
        if ($title) {
            $view->headTitle($title->titulo);
            $view->titulo = $title->titulo;
        }
        
    }
    
}


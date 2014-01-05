<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author rosousas
 */
class Plugin_Auth extends Zend_Controller_Plugin_Abstract {
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();
        
        $modelFuncionalidade = new Model_Funcionalidade();
        $funcionalidade = $modelFuncionalidade->fetchRow("
            module = '{$request->getModuleName()}'
            and controller = '{$request->getControllerName()}'
            and action = '{$request->getActionName()}'
            and ativo = 1
        ");
            
        $auth = Zend_Auth::getInstance();        
                
        if ($moduleName !== 'site') {
            if ($funcionalidade) {                 
                if ($funcionalidade->auth) {
                    if (!$auth->hasIdentity()) {
                        $request->setModuleName("site")
                                ->setControllerName("index")
                                ->setActionName("index")                        
                                ->setDispatched();
                        /*
                        $request->setModuleName("site")
                                ->setControllerName("index")
                                ->setActionName("index")                        
                                ->setDispatched();
                         * 
                         */
                    }
                }
            } else {
                $request->setModuleName("cliente")
                            ->setControllerName("error")
                            ->setActionName("error")                        
                            ->setDispatched();
            }
        }
    }
    
}



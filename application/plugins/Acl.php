<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acl
 *
 * @author Realter
 */
class Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    protected $_acl;
    protected $_module;
    protected $_controller;
    protected $_action;

    protected $_request;


    public function preDispatch(\Zend_Controller_Request_Abstract $request) {
        
        $this->_acl = new Zend_Acl();        
        
        $this->_module = $request->getModuleName();
        $this->_controller = $request->getControllerName();
        $this->_action = $request->getActionName();
        
        $this->_request = $request;
        
        //$this->startAcl();
        
    }
    
    protected function roles() {        
        // plano gestor pode tudo
        $this->_acl->addRole(new Zend_Acl_Role("gestor"));        
    }
    
    protected function resources() {
        $this->_acl->add(new Zend_Acl_Resource("gestor:index", "index"));
        $this->_acl->add(new Zend_Acl_Resource("cliente:error", "error"));
    }   
    
    protected function previleges() {
        $this->_acl->allow("gestor", "gestor:index", "index");
    }
    
    protected function isAllowed() {
        
         if (!$this->_acl->isAllowed("gestor", $this->_request->getModuleName().':'.$this->_request->getControllerName(), $this->_request->getActionName())) {
            $this->_request->setModuleName('cliente');
            $this->_request->setControllerName("index");
            $this->_request->setActionName('deny');
            $this->_request->setDispatched();                    
        }
        
    }

    protected function startAcl() {        
        $this->roles();
        $this->resources();
        $this->previleges();     
        $this->isAllowed();
    }
    
}


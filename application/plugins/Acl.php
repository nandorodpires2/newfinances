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

    // constante do id do plano gestor
    const ID_PLANO_GESTOR = 7;
    
    protected $_acl;
    protected $_module;
    protected $_controller;
    protected $_action;
    protected $_request;

    // grava as funcionalidades do usuario
    protected $_funcionalidades;
    
    // grava o plano do usario
    protected $_role;
    
    // grava os recuros
    protected $_resources;

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $modelUsuarioPlano = new Model_UsuarioPlano();
        $modelPlanoFuncionalidade = new Model_PlanoFuncionalidade();
        $modelFuncionalidade = new Model_Funcionalidade();
        
        $this->_acl = new Zend_Acl();

        $this->_module = $request->getModuleName();
        $this->_controller = $request->getControllerName();
        $this->_action = $request->getActionName();

        $this->_request = $request;
       
        // caso o modulo seja de site nao habilita o ACL
        if ($this->_module !== 'site') {
            /**
            * caso nao tenha usuario logado ou o usuario logado seja gestor do
            * sistema, nao "starta" o ACL
            */ 
            if (Zend_Auth::getInstance()->hasIdentity()) {
                $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
                // busca o plano do usuario logado
                $planoUsuario = $modelUsuarioPlano->getPlanoAtual($id_usuario);
                $this->_role = $planoUsuario->descricao_plano;
                // verifica se o usuario e gestor
                if ($planoUsuario->id_plano != self::ID_PLANO_GESTOR) {                
                    // busca os recursos do plano
                    $this->_resources = $modelFuncionalidade->getResourcesPlano($planoUsuario->id_plano);
                    // busca as funcionalidades que o plano pode acessar
                    $this->_funcionalidades = $modelPlanoFuncionalidade->getFuncionalidadesPlano($planoUsuario->id_plano);                
                    $this->startAcl();
                }            
            }
        }
    }

    protected function roles() {
        $this->_acl->addRole(new Zend_Acl_Role($this->_role));
    }

    protected function resources() {        
        foreach ($this->_resources as $resource) {            
            $this->_acl->add(new Zend_Acl_Resource($resource->resource));
        }
    }

    protected function previleges() {        
        foreach ($this->_funcionalidades as $funcionalidade) {
            $moduleController = $funcionalidade->module .":".$funcionalidade->controller;
            $action = $funcionalidade->action;
            $this->_acl->allow($this->_role, $moduleController, $action);
        }
    }

    protected function isAllowed() {        
        $url = $this->_request->getModuleName() . ':' . $this->_request->getControllerName();

        if (!$this->_acl->isAllowed($this->_role, $url, $this->_request->getActionName())) {           
            $this->_request->setModuleName("cliente")
                    ->setControllerName("index")
                    ->setActionName("deny")
                    ->setDispatched();
        } 
    }

    protected function startAcl() {
        $this->roles();
        $this->resources();
        $this->previleges();
        $this->isAllowed();
    }

}


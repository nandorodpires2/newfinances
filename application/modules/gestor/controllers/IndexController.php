<?php

class Gestor_IndexController extends Zend_Controller_Action
{
    
    protected $_modelUsuario;
    protected $_modelUsuarioPlano;
    protected $_modelPlano;
    
    protected $_session;

    public function init() {
        
        $this->_modelUsuario = new Model_Usuario();
        $this->_modelUsuarioPlano = new Model_UsuarioPlano();
        $this->_modelPlano = new Model_Plano();
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();
        
    }

    public function indexAction() {
        
    }

}


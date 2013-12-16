<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuariosController
 *
 * @author Realter
 */
class Gestor_UsuariosController extends Zend_Controller_Action {

    protected $_modelUsuario;

    public function init() {
        
        $this->_modelUsuario = new Model_Usuario();
        
    }
    
    public function indexAction() {
        
        // busca os usuários
        $usuarios = $this->_modelUsuario->getListaDadosUsuarios();
        $this->view->usuarios = $usuarios;
        
    }
    
    public function buscarUsuarioAction() {
        
    }
    
    public function novoUsuarioAction() {
        
    }
    
}


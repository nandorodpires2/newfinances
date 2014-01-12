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
class Gestor_UsuariosController extends Application_Controller {

    public function init() {
        parent::init();
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
    
    public function acessosAction() {
        
        $id_usuario = $this->_getParam("id_usuario");
        
        $acessos = $this->_modelUsuarioLogin->getTotalAcessos($id_usuario);
        $this->view->acessos = $acessos;
                
    }
    
}


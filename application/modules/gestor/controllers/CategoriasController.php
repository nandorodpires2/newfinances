<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoriasController
 *
 * @author Realter
 */
class Gestor_CategoriasController extends Application_Controller {

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        $this->view->form = $this->_formNovaCategoria;
        
        $categorias = $this->_modelCategoria->fetchAll(null, "descricao_categoria asc");                        
        $this->view->categorias = $categorias;        
     
        if ($this->_request->isPost()) {
            $dadosCategoria = $this->_request->getPost();
            if ($this->_formNovaCategoria->isValid($dadosCategoria)) {
                $dadosCategoria = $this->_formNovaCategoria->getValues();
                
                try {
                    $this->_modelCategoria->insert($dadosCategoria);
                    $this->_redirect("gestor/categorias/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                
            }
                    
        }
        
    }
    
}


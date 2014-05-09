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
    
    public function editarCategoriaAction() {
        
        $id_categoria = $this->_getParam('id_categoria');
        
        $dadosCategoria = $this->_modelCategoria->fetchRow("id_categoria = {$id_categoria}");
        $this->_formNovaCategoria->populate($dadosCategoria->toArray());
        $this->view->form = $this->_formNovaCategoria;
        
        if ($this->_request->isPost()) {
            $dadosUpdate = $this->_request->getPost();
            if ($this->_formNovaCategoria->isValid($dadosUpdate)) {
                $dadosUpdate = $this->_formNovaCategoria->getValues();
                
                $where = "id_categoria = {$id_categoria}";
                try {
                    $this->_modelCategoria->update($dadosUpdate, $where);
                    $this->_redirect('gestor/categorias/');
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                
            }
        }
        
    }
    
}


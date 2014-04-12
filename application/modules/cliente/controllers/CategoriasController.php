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
class CategoriasController extends Application_Controller {

    public function init() {
        parent::init();
    }
    
    public function listarGastosAction() {
        
        // recupera o id da categoria
        $id_categoria = $this->_getParam("id_categoria");
        
        // busca os gastos da categoria do mes e ano atual
        $gastosCategoria = $this->_modelCategoria->getGastosCategoriaMes($this->_session->id_usuario, $id_categoria);        
        $this->view->gastosCategoria = $gastosCategoria;
        
        // total gasto da categoria
        $totalGastoCategoria = $this->_modelCategoria->getTotalGastoCategoriaMes($id_categoria);
        $this->view->totalGastoCategoria = $totalGastoCategoria->total;
        
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChamadosController
 *
 * @author Fernando Rodrigues
 */
class Gestor_ChamadosController extends Application_Controller {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        
        $chamados = $this->_modelChamado->fetchAll(null, "status desc");
        $this->view->chamados = $chamados;
        
    }
    
    /**
     * ver chamados
     */
    public function verChamadoAction() {
        
        // recuperando o id do chamado
        $id_chamado = $this->_getParam("id_chamado");
        
        // buscando os dados do chamado
        $dadosChamado = $this->_modelChamado->getDadosChamado($id_chamado);
        $this->view->dadosChamado = $dadosChamado;
        
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanosController
 *
 * @author Realter
 */
class Gestor_PlanosController extends Application_Controller {

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        // busca os planos cadastrados
        $planos = $this->_modelPlano->fetchAll();
        $this->view->planos = $planos;
        
        // busca os valores cadastrados para os planos
        $planosValores = $this->_modelPlanoValor->getValoresPlanos();           
        $this->view->planosValores = $planosValores;        
        
    }
    
    public function novoValorPlanoAction() {
        
    }
    
}


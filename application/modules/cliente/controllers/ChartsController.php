<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChartsController
 *
 * @author Realter
 */
class ChartsController extends Zend_Controller_Action {

    protected $_session;
    
    protected $_modelCategoria;

    public function init() {
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();        
        
        $this->_modelCategoria = new Model_Categoria();
    }
    
    /**
     * retorna os gastos anuais
     */
    public function gastosAnuaisAction() {
        $this->_disabledLayout();
        $this->_disabledView();
         
        $gastos = $this->_modelCategoria->getGastosCategoriasMes($this->_session->id_usuario)->toArray();
        
        echo "
            ['Supermercado', 100],
            ['Títulos', 200]
        ";
        
    }

    protected function _disabledLayout() {
        $this->_helper->layout->disableLayout();
    }
    
    protected function _disabledView() {
        $this->_helper->viewRenderer->setNoRender(true);
    }
}


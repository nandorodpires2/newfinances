<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BancosController
 *
 * @author Realter
 */
class Gestor_BancosController extends Zend_Controller_Action {

    protected $_modelBanco;
    
    protected $_formBancosCadastro;

    public function init() {
        
        $this->_modelBanco = new Model_Banco();
        $this->_formBancosCadastro = new Form_Bancos_Cadastro();
        
    }
    
    public function indexAction() {
        $this->view->form = $this->_formBancosCadastro;
        
        $bancos = $this->_modelBanco->fetchAll(null, "2 asc");
        $this->view->bancos = $bancos;
        
        if ($this->_request->isPost()) {
            $dadosBanco = $this->_request->getPost();
            if ($this->_formBancosCadastro->isValid($dadosBanco)) {
                $dadosBanco = $this->_formBancosCadastro->getValues();
                
                try {
                    $this->_modelBanco->insert($dadosBanco);
                    $this->_redirect("gestor/bancos/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
            }            
        }
    }
    
}


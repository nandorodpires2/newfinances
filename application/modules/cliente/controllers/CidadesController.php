<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CidadesController
 *
 * @author Realter
 */
class CidadesController extends Zend_Controller_Action {

    protected $_modelCidade;
    protected $_formUsuarioNovoUsuario;

    public function init() {
        
        $this->_modelCidade = new Model_Cidade();
        $this->_formUsuarioNovoUsuario = new Form_Usuarios_NovoUsuario();
        
    }
    
    public function buscaCidadesAction() {
        
        // esabilita o layout 
        $this->_helper->layout()->disableLayout();
        // desabilita a view
        $this->_helper->viewRenderer->setNoRender(true);
        
        $id_estado = $this->_getParam("id_estado");
        $cidades = $this->_modelCidade->fetchAll("id_estado = {$id_estado}");
        
        $multiOptions = array();
        foreach ($cidades as $cidade) {            
            $multiOptions[$cidade->id_cidade] = $cidade->descricao_cidade;
        }
        
        $this->_formUsuarioNovoUsuario->id_cidade->setMultiOptions($multiOptions);        
        echo $this->_formUsuarioNovoUsuario->id_cidade;
        
    }
    
}


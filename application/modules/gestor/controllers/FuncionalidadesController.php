<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FuncionalidadesController
 *
 * @author Realter
 */
class Gestor_FuncionalidadesController extends Zend_Controller_Action  {

    protected $_modelFuncionalidade;
    
    protected $_formFuncionalidades;

    protected $_grid;

    public function init() {
        
        $this->_modelFuncionalidade = new Model_Funcionalidade();        
        $this->_formFuncionalidades = new Form_Funcionalidades_NovaFuncionalidade();
        $this->_grid = new Plugin_Grid();
        
    }
    
    public function indexAction() {
        
        // busca todas as funcionalidades
        $funcionalidades = $this->_modelFuncionalidade->getFuncionalidades();                 
        $this->view->funcionalidades = $funcionalidades;
                
    }
    
    /**
     * cadastra uma nova funcionalidade
     */
    public function novaFuncionalidadeAction() {
        
        // envia o form de nova funcionalidade para a view
        $this->view->form = $this->_formFuncionalidades;
        
        if ($this->_request->isPost()) {
            $dadosFuncionalidade = $this->_request->getPost();
            if ($this->_formFuncionalidades->isValid($dadosFuncionalidade)) {
                $dadosFuncionalidade = $this->_formFuncionalidades->getValues();
                
                try {
                    $this->_modelFuncionalidade->insert($dadosFuncionalidade);
                    $this->_redirect("gestor/planos/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
        
    }
    
    /**
     * edita uma funcionalidade
     */
    public function editarFuncionalidadeAction() {
        
        $id_funcionalidade = $this->_getParam("id_funcionalidade");
        
        $dadosFuncionalidade = $this->_modelFuncionalidade->find($id_funcionalidade)->current()->toArray();
        $this->_formFuncionalidades->populate($dadosFuncionalidade);
        $this->view->form = $this->_formFuncionalidades;
        
        if ($this->_request->isPost()) {
            $dadosUpdateFuncionalidade = $this->_request->getPost();
            if ($this->_formFuncionalidades->isValid($dadosUpdateFuncionalidade)) {
                $dadosUpdateFuncionalidade = $this->_formFuncionalidades->getValues();
                
                $whereUpdate = "id_funcionalidade = " . $id_funcionalidade;
                
                try {
                    $this->_modelFuncionalidade->update($dadosUpdateFuncionalidade, $whereUpdate);
                    $this->_redirect("gestor/funcionalidades/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                
            }
        }
        
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccessController
 *
 * @author Realter
 */
class Gestor_AccessController extends Application_Controller {

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        $id_plano = $this->_getParam('id_plano');
        
        // busca dados do plano
        $plano = $this->_modelPlano->find($id_plano)->current();
        
        $this->view->plano = $plano;
        
        // buscar as funcionalidades
        $funcionalidades  = $this->_modelFuncionalidade->getFuncionalidades();        
        $funcionalidadesPlano = $this->_modelPlanoFuncionalidade->getFuncionalidadesPlano($id_plano);
        
        $populate = array();
        $populate['id_plano'] = $id_plano;
        foreach ($funcionalidadesPlano as $funcionalidadePlano) {
            $populate['id_funcionalidade'][] = $funcionalidadePlano->id_funcionalidade;
        }
        
        $this->_formAccessPermissao->populate($populate);
        $this->view->formAccessPermissao = $this->_formAccessPermissao;
        
        $dadosFuncionalidade = array();
        foreach ($funcionalidades as $key => $funcionalidade) {
            
            if ($funcionalidade->module === 'cliente') {
                $dadosFuncionalidade['cliente'][$key] = $funcionalidade;
            } else {
                $dadosFuncionalidade['gestor'][$key] = $funcionalidade;                
            }            
            
        }
        
        $this->view->dadosFuncionalidade = $dadosFuncionalidade;
        
        /**
         * atualiza as permissoes
         */
        if ($this->_request->isPost()) {
            $dadosPermissao = $this->_request->getPost();
            if ($this->_formAccessPermissao->isvalid($dadosPermissao)) {
                $dadosPermissao = $this->_formAccessPermissao->getValues();
                
                // apaga todas as permissoes do plano 
                $whereDeletePlanoFuncionalidade = "id_plano = " . $id_plano;
                $this->_modelPlanoFuncionalidade->delete($whereDeletePlanoFuncionalidade);
                
                // cadastra as novas permissoes
                foreach ($dadosPermissao['id_funcionalidade'] as $id_funcionalidade) {
                    $dadosNovasPermissoesPlano['id_plano'] = $dadosPermissao['id_plano'];
                    $dadosNovasPermissoesPlano['id_funcionalidade'] = $id_funcionalidade;                    
                    $this->_modelPlanoFuncionalidade->insert($dadosNovasPermissoesPlano);                    
                }
                
                $this->_redirect("gestor/planos/");
                
            }
        }
        
    }
    
}


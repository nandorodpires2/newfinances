<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RelatoriosController
 *
 * @author Realter
 */
class RelatoriosController extends Application_Controller {
 
    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        // alterando o form de filtro
        $this->_formMes->removeElement("mes");
        $this->_formMes->addElement("submit", "submit", array(
            'label' => 'Filtrar', 
            'class' => 'submit',
            'decorators' => array(
                    'ViewHelper',
                    'Description',
                    'Errors',                
                    array(array('td' => 'HtmlTag'), array('tag' => 'td'))
                    
                )
            )
        );
        
        // buscando a lista de relatorio anual
        $ano = $this->_getParam("ano", date("Y"));
        
        $this->_formMes->populate(array('ano' => $ano));
        
        // enviando o form de filtro pra view
        $this->view->formFilter = $this->_formMes;
        
        $dadosRelatorios = $this->_modelRelatorios->getRelatorioAnual($ano, $this->_session->id_usuario);                
        $this->view->dadosRelatorio = $dadosRelatorios;
        
    }
    
    public function categoriasAction() {
        
        $metasCategorias = $this->_modelVwMetasCategorias->getMetasCategoriasMes($this->_session->id_usuario);
        $this->view->metasCategorias = $metasCategorias;
        
    }
    
}

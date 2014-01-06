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
        
        // enviando o form de filtro pra view
        $this->view->formFilter = $this->_formMes;
        
        // buscando a lista de relatorio anual
        $ano = $this->_getParam("ano", date("Y"));
        $relatorioAnual = $this->_modelVwRelatorioAnual->fetchAll("
            id_usuario = {$this->_session->id_usuario}
            and ano = {$ano}
        ");
            
        // buscando o total do relatorio anual
        $totalRelatorioAnual = $this->_modelVwRelatorioAnual->getTotalRelatorioAnual($this->_session->id_usuario, $ano);
            
        $this->view->relatorioAnual = $relatorioAnual;
        $this->view->totalRelatorioAnual = $totalRelatorioAnual;
        
    }
    
}

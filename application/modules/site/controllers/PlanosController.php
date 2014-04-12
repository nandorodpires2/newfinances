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
class Site_PlanosController extends Application_Controller {

    public function init() {
        parent::init();
        $this->_helper->layout()->setLayout(LAYOUT_SITE_DEFAULT);
    }
    
    public function indexAction() {
        // busca os planos atuais
        $this->view->planoTrimestral = $this->_modelPlanoValor->getPlanoValorUsuario(self::PLANO_TRIMESTRAL);
        $this->view->planoSemestral = $this->_modelPlanoValor->getPlanoValorUsuario(self::PLANO_SEMESTRAL);
        $this->view->planoAnual = $this->_modelPlanoValor->getPlanoValorUsuario(self::PLANO_ANUAL);
    }
    
}


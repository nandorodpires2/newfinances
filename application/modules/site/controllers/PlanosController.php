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
class Site_PlanosController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout()->setLayout('site');
    }
    
    public function indexAction() {
        
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VantagensController
 *
 * @author Realter
 */
class Site_VantagensController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout()->setLayout(LAYOUT_SITE_DEFAULT);
    }
    
    public function indexAction() {
        
    }
    
}


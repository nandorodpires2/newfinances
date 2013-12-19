<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Realter
 */
class Site_IndexController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        $this->_helper->layout()->setLayout('site');
    }
    
    public function indexAction() {
        
    }
    
}


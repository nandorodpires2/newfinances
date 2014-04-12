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
class Site_IndexController extends Application_Controller {

    public function init() {                
        parent::init();        
        $this->_helper->layout()->setLayout(LAYOUT_SITE_DEFAULT);
    }
    
    public function indexAction() {

    }
    
}


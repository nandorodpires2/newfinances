<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Date
 *
 * @author Fernando Rodrigues
 */
class Form_Date extends Zend_Form {
    
    public function init() {
        
        $this->setAttrib('id', 'formDate')
                ->setMethod('post');
        
        // date
        $this->addElement('text', 'date', array(
            'size' => '1px',
            'decorators' => array(
                'ViewHelper',
                'Description',
                'Errors',                
                array(array('td' => 'HtmlTag'), array('tag' => 'td')),
                array('Label', array('tag' => 'td')),
            )
        ));
                
    }
    
}

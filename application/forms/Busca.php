<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Busca
 *
 * @author Realter
 */
class Form_Busca extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formBusca')
                ->setMethod('post');
        
        // busca
        $this->addElement("text", "busca", array(
            'label' => 'Digite sua busca: ',
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


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conta
 *
 * @author Fernando Rodrigues
 */
class Form_Conta extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formConta')
                ->setMethod('post');
        
        // categorias
        $this->addElement("select", "conta", array(
            'label' => 'Conta: ',
            'multioptions' => $formDefault->getContasUsuario(),
            'required' => true,
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


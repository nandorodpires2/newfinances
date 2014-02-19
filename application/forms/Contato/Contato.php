<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contato
 *
 * @author Fernando Rodrigues
 */
class Form_Contato_Contato extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formContato')
                ->setMethod('post');
        
        // nome
        $this->addElement('text', 'nome', array(
            'label' => 'Nome: ',
            'required' => true
        ));
        
        // email
        $this->addElement('text', 'email', array(
            'label' => 'E-mail: ',
            'required' => true
        ));
        
        // assunto
        $this->addElement('text', 'assunto', array(
            'label' => 'Assunto: ',
            'required' => true
        ));
        
        // texto
        $this->addElement('textarea', 'texto', array(
            'label' => 'Digite sua mensagem: ',
            'required' => true
        ));
        
        //submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Enviar',
            'class' => 'submit'
        ));
        
    }
    
}




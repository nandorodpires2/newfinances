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
        
        //adicionando validators
        $validatorEmail = new Zend_Validate_EmailAddress;
        $validatorEmail->setMessages(array(
            Zend_Validate_EmailAddress::INVALID_FORMAT => 'O e-mail %value% não é válido!'
        ));    
        
        $this->setAttrib('id', 'formContato')
                ->setMethod('post');
        
        // nome
        $this->addElement('text', 'nome', array(
            'label' => 'Nome: ',
            'size' => 50,
            'required' => true
        ));
        
        // email
        $this->addElement('text', 'email', array(
            'label' => 'E-mail: ',
            'size' => 50,
            'validators' => array(
                $validatorEmail
            ),
            'required' => true
        ));
        
        // assunto
        $this->addElement('text', 'assunto', array(
            'label' => 'Assunto: ',
            'size' => 50,
            'required' => true
        ));
        
        // texto
        $this->addElement('textarea', 'texto', array(
            'label' => 'Digite sua mensagem: ',
            'rows' => 8,
            'cols' => 50,
            'required' => true
        ));
        
        //submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Enviar',
            'class' => 'submit'
        ));
        
    }
    
}




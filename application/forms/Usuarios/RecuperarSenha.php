<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RecuperarSenha
 *
 * @author Realter
 */
class Form_Usuarios_RecuperarSenha extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formUsuariosRecuperarSenha')
                ->setMethod('post');
        
        // email_cadastrado
        $this->addElement('text', 'email_usuario', array(
            'label' => 'Digite o e-mail cadastrado: ',
            'size' => '40px',
            'required' => true
        ));
        
        // submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Enviar',
            'class' => 'submit'
        ));
        
        
    }
    
    
}



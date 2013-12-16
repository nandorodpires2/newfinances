<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Fernando Rodrigues
 */
class Form_Usuarios_Login extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'form_usuarios_login')
                ->setMethod('post');
        
        // email
        $this->addElement('text', 'email_usuario', array(
            'label' => 'E-mail: ',
            'required' => true
        ));
        
        // senha
        $this->addElement('password', 'senha_usuario', array(
            'label' => 'Senha: ',
            'required' => true            
        ));
        
        //submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Entrar',
            'class' => 'submit'
        ));
        
    }
    
}


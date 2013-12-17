<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Senha
 *
 * @author Fernando Rodrigues
 */
class Form_Configuracoes_Senha extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formConfiguracoesSenha')
                ->setMethod('post');
        
        // senha atual
        $this->addElement('password', 'senha_atual', array(
            'label' => 'Senha Atual:',
            'required' => true
        ));
        
        // nova senha
        $this->addElement('password', 'senha_nova', array(
            'label' => 'Nova Senha:',
            'required' => true
        ));
        
        // confirma senha
        $this->addElement('password', 'confirma_senha', array(
            'label' => 'Confirme a Nova Senha:',
            'required' => true
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Alterar',
            'class' => 'submit'
        ));
        
    }
    
}


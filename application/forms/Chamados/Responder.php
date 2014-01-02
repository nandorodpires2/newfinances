<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Responder
 *
 * @author Fernando Rodrigues
 */
class Form_Chamados_Responder extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formChamadosResponder')
                ->setMethod('post');
        
        // id_chamado (hidden)
        $this->addElement('hidden', 'id_chamado');
        
        // id_usuario (hidden)
        $this->addElement('hidden', 'id_usuario');
        
        // resposta
        $this->addElement('textarea', 'resposta', array(
            'label' => 'Mensagem:',
            'rows' => 10,
            'cols' => 40,
            'required' => true
        ));
        
        $this->addElement('submit', 'submit', array(
            'label' => 'Responder',
            'class' => 'submit'
        ));
        
        
    }
    
}


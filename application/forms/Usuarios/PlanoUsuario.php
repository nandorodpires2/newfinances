<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanoUsuario
 *
 * @author Realter
 */
class Form_Usuarios_PlanoUsuario extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formPlanoUsuario')
                ->setMethod('post');
                
        //$this->setAttrib('target', '_blank');;
        
        // id_usuario
        $this->addElement("hidden", "id_usuario", array());
        
        // id_plano
        $this->addElement("radio", "id_plano", array(
            'label' => 'Planos: ',
            'multioptions' => $formDefault->getPlanos(),
            'registerInArrayValidator' => false 
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Escolher',
            'class' => 'submit'
        ));
        
    }
    
}


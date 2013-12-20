<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Permissao
 *
 * @author Realter
 */
class Form_Access_Permissao extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formAccessPermissao')
                ->setMethod('post');
        
        // id_plano (hidden)
        $this->addElement('hidden', 'id_plano', array());
        
        // checkbox
        $this->addElement('multiCheckbox', 'id_funcionalidade', array(
            'multiOptions' => $formDefault->getFuncionalidades()
        ));
        
        // submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Editar',
            'class' => 'submit'
        ));
        
    }
    
}


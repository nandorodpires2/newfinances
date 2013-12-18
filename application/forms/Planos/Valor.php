<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Valor
 *
 * @author Realter
 */
class Form_Planos_Valor extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formPlanosValor')
                ->setMethod('post');
        
        // id_plano
        $this->addElement('select', 'id_plano', array(
            'label' => 'Plano: ',
            'multiOptions' => $formDefault->getPlanos()
        ));
        
        // valor_plano
        $this->addElement('text', 'valor_plano', array(
            'label' => 'Valor: ',
            'required' => true
        ));
        
        // usuario
        $this->addElement('select', 'usuario', array(
            'label' => 'Usuário: ',
            'multiOptions' => array(
                1 => 'Sim',
                0 => 'Não'             
            )
        ));
        
        // banner do plano valor
        $this->addElement("file", "banner", array(
            'label' => 'Banner: ',
            'maxFileSize' => 2097152,
            'required' => true
        ));
        
        // submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Cadastrar',
            'class' => 'submit'
        ));
        
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Questionario
 *
 * @author Fernando Rodrigues
 */
class Form_Usuarios_Questionario extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formUsuariosQuestionario')
                ->setMethod('post');
        
        // id_usuario (hidden)
        $this->addElement('hidden', 'id_usuario');
        
        // questao_1
        $this->addElement('radio', 'questao_1', array(
            'label' => '1 - O sistema NewFinances está sendo útil pra você?',
            'multioptions' => array(
                'Sim' => 'Sim',
                'Não' => 'Não'
            ),
            'required' => true
        ));
        
        // questao_2
        $this->addElement('radio', 'questao_2', array(
            'label' => '2 - Como você classifica a aparência do sistema?',
            'multioptions' => array(
                'Péssima' => 'Péssima',
                'Ruim' => 'Ruim',
                'Boa' => 'Boa',
                'Muito boa' => 'Muito boa',
                'Excelente' => 'Excelente'
            ),
            'required' => true
        ));
        
        // questao_3
        $this->addElement('text', 'questao_3', array(
            'label' => '3 - O que você acha necessário acrestar às funcionalidades do sistema?',            
            'size' => 80,
            'required' => true
        ));
        
        // questao_4
        $this->addElement('text', 'questao_4', array(
            'label' => '4 - O que você acha desnecessário no sistema?',            
            'size' => 80,
            'required' => true
        ));
        
        // questao_5
        $this->addElement('textarea', 'questao_5', array(
            'label' => '5 - Quais são suas considerações sobre o NewFinances?',            
            'rows' => 10,
            'colls' => 15,
            'required' => true
        ));
        
        // submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Enviar',   
            'class' => 'submit'
        ));
        
    }
    
}


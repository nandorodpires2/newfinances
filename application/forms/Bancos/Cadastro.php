<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cadastro
 *
 * @author Realter
 */
class Form_Bancos_Cadastro extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formBancosCadastro')
                ->setMethod('post');
        
        //nome_banco
        $this->addElement("text", "nome_banco", array(
            'label' => 'Nome do Banco:',
            'required' => true
        ));
        
        // logo_banco
        $this->addElement("hidden", "logo_banco", array(
            'value' => null
        ));
        
        // ativo_banco
        $this->addElement("hidden", "ativo_banco", array(
            'value' => 1
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Cadastrar',
            'class' => 'submit'
        ));
        
    }
    
}


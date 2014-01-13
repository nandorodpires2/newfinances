<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cartao
 *
 * @author Realter
 */
class Form_Configuracoes_Cartao extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formConfiguracoesCartao')
                ->setMethod('post');
        
        // id_usuario (hidden)
        $this->addElement("hidden", "id_usuario", array(
            'value' => $formDefault->id_usuario
        ));
        
        // bandeira_cartao
        $this->addElement("select", "bandeira_cartao", array(
            "label" => "Bandeira: ",
            "required" => true,
            "multioptions" => $this->getBandeiras()
        ));
        
        // limite_cartao
        $this->addElement("text", "limite_cartao", array(
            "label" => "Limite: ", 
            "required" => true
        ));
        
        // fechamento_cartao
        $this->addElement("text", "fechamento_cartao", array(
            "label" => "Dia Fechamento: ", 
            "required" => true
        ));
        
        // vencimento_cartao        
        $this->addElement("text", "vencimento_cartao", array(
            "label" => "Dia Vencimento: ", 
            "required" => true
        ));
        
        // descricao_cartao
        $this->addElement("text", "descricao_cartao", array(
            "label" => "Descrição: ", 
            "required" => true
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            "label" => "Cadastrar",
            "class" => "submit"
        ));
        
    }
    
    protected function getBandeiras() {
        
        $multOptions = array(            
            '' => 'Selecione...',
            'visa' => 'Visa',
            'mastercard' => 'MasterCard',
            'american_express' => 'American Express'
        );
        
        return $multOptions;
    }
    
}


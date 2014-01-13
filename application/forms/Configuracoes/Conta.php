<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conta
 *
 * @author Realter
 */
class Form_Configuracoes_Conta extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib("id", "formNovaConta")
                ->setMethod("post");
        
        // id_usuario (hidden)
        $this->addElement("hidden", "id_usuario", array(
            'value' => $formDefault->id_usuario
        ));
        
        // descricao
        $this->addElement("text", "descricao_conta", array(
            "label" => "Descrição: ",
            "required" => true
        ));
        
        // tipo
        $this->addElement("select", "id_tipo_conta", array(
            "label" => "Tipo de Conta: ",
            "multioptions" => $formDefault->getTipoContas(),
            "required" => true
        ));
        
        // saldo inicial
        $this->addElement("text", "saldo_inicial", array(
            "label" => "Saldo Inicial: ",
            "required" => true
        ));
        
        // banco
        $this->addElement("select", "id_banco", array(
            "label" => "Banco: ",
            "multioptions" => $formDefault->getBancos()
        ));
        
        // submit        
        $this->addElement("submit", "submit", array(
            "label" => "Cadastrar",
            "class" => "submit"
        ));
    }
    
}


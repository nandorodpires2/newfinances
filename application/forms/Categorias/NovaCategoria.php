<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NovaCategoria
 *
 * @author Realter
 */
class Form_Categorias_NovaCategoria extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formNovaCategoria')
                ->setMethod("post");
        
        // descricao_categotia
        $this->addElement("text", "descricao_categoria", array(
            "label" => "Descrição: ",
            "required" => true
        ));
        
        // ativo
        $this->addElement("select", "ativo_categoria", array(
            "label" => "Ativo:",
            "multioptions" => array(
                1 => 'Sim',
                0 => 'Não'
            )
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            "label" => "Cadastrar",
            "class" => "submit"
        ));
    }
    
}


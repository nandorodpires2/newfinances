<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transferencia
 *
 * @author Realter
 */
class Form_Movimentacoes_Transferencia extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        $zendDate = new Zend_Date();
        
        // id_usuario (hidden)
        $this->addElement("hidden", "id_usuario", array(
            'value' => $formDefault->id_usuario
        ));
        
        // descricao
        $this->addElement("text", "descricao_movimentacao", array(
            'label' => 'Descrição: '
        ));
        
        // valor 
        $this->addElement("text", "valor_movimentacao", array(
            'label' => 'Valor: '
        ));
        
        // data
        $this->addElement("text", "data_movimentacao", array(
            'label' => 'Data: ',
            'value' => $zendDate->get(Zend_Date::DATE_MEDIUM)
        ));
        
        // categoria
        $this->addElement("select", "id_categoria", array(
            'label' => 'Categoria: ',
            'value' => 9,
            'multioptions' => $formDefault->getCategorias()
        ));
        
        // conta origem
        $this->addElement("select", "id_conta_origem", array(
            'label' => 'Origem: ',
            'multioptions' => $formDefault->getContasUsuario(1)
        ));
        
        // conta destino
        $this->addElement("select", "id_conta", array(
            'label' => 'Destino: ',
            'multioptions' => $formDefault->getContasUsuario(1)
        ));
        
        // submit        
        $this->addElement("submit", "submit", array(
            'label' => 'Salvar',
            'class' => 'submit'
        ));
        
    }
    
}


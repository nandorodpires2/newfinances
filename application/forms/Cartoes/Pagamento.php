<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagamento
 *
 * @author Realter
 */
class Form_Cartoes_Pagamento extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formPgtoCartao')
                ->setMethod("post");
        
        // valor_movimentacao
        $this->addElement('text', 'valor_movimentacao', array(
            'label' => 'Valor a pagar: '
        ));        
        
        // conta
        $this->addElement("select", "id_conta", array(
            'label' => 'Conta: ',
            'multioptions' => $formDefault->getContasUsuario(1)
        ));
        
        // parcelar
        /*
        $this->addElement('checkbox', 'parcelar', array(
            'label' => 'Parcelar'
        ));
        */
        // submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Pagar',
            'class' => 'submit'
        ));
        
    }
    
}

